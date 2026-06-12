<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Kegiatan;
use App\Models\UnitKerja;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    /**
     * Menampilkan halaman monitoring publik (Dashboard Presentasi).
     */
    public function index(Request $request)
    {
        $unit_id = $request->query('unit_id');

        // Ambil data Tugas (Tasks)
        $tasksQuery = Task::with(['assignee.unitKerja'])
            ->orderByRaw("CASE 
                WHEN status = 'Berlangsung' THEN 1
                WHEN status = 'Revisi' THEN 2
                WHEN status = 'Menunggu Review' THEN 3
                ELSE 4 END")
            ->orderBy('tgl_selesai', 'asc');

        if ($unit_id) {
            $tasksQuery->whereHas('assignee', function($q) use ($unit_id) {
                $q->where('unit_id', $unit_id);
            });
        }
        $tasks = $tasksQuery->get();

        // Group tasks by pegawai (assignee) name
        $tasksGrouped = $tasks->groupBy(function ($task) {
            return $task->assignee->nama ?? 'Belum Ditugaskan';
        });

        // Ambil data Jadwal Kegiatan
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfNextMonth = Carbon::now()->addMonth()->endOfMonth();

        $kegiatansQuery = Kegiatan::with(['unitKerja', 'lokasi', 'peserta'])
            ->whereBetween('waktu_mulai', [$startOfMonth, $endOfNextMonth])
            ->orderBy('waktu_mulai', 'asc');

        if ($unit_id) {
            $kegiatansQuery->where('unit_id', $unit_id);
        }
        $kegiatans = $kegiatansQuery->get();

        // Ambil daftar Unit Kerja untuk dropdown filter
        $units = UnitKerja::orderBy('nama_unit', 'asc')->get();

        // Statistik sederhana untuk header
        $stats = [
            'total_tugas' => $tasks->count(),
            'tugas_berlangsung' => $tasks->whereIn('status', ['Berlangsung', 'Revisi', 'Menunggu Review'])->count(),
            'tugas_selesai' => $tasks->where('status', 'Selesai')->count(),
        ];

        return view('monitoring', compact('tasks', 'tasksGrouped', 'kegiatans', 'stats', 'units', 'unit_id'));
    }
}
