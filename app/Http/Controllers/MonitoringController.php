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

        // Jika ada filter unit, dapatkan semua ID unit turunannya
        $filterUnitIds = [];
        if ($unit_id) {
            $unit = UnitKerja::find($unit_id);
            if ($unit) {
                $filterUnitIds = $unit->getAllDescendantIds();
                $filterUnitIds[] = $unit_id; // Termasuk unit itu sendiri
            }
        }

        // Ambil data Tugas (Tasks)
        $tasksQuery = Task::with(['assignee.unitKerja'])
            ->orderByRaw("CASE 
                WHEN status = 'Berlangsung' THEN 1
                WHEN status = 'Revisi' THEN 2
                WHEN status = 'Menunggu Review' THEN 3
                ELSE 4 END")
            ->orderBy('tgl_selesai', 'asc');

        if (!empty($filterUnitIds)) {
            $tasksQuery->whereHas('assignee', function($q) use ($filterUnitIds) {
                $q->whereIn('unit_id', $filterUnitIds);
            });
        }
        $tasks = $tasksQuery->get();

        // Group tasks by pegawai (assignee) name
        $tasksGrouped = $tasks->groupBy(function ($task) {
            return $task->assignee->nama ?? 'Belum Ditugaskan';
        });

        // Ambil data Jadwal Kegiatan
        // Tampilkan semua jadwal (kapanpun itu diinput) asalkan belum selesai
        $now = Carbon::now();

        $kegiatansQuery = Kegiatan::with(['unitKerja', 'lokasi', 'peserta'])
            ->where('waktu_selesai', '>=', $now)
            ->orderBy('waktu_mulai', 'asc');

        if (!empty($filterUnitIds)) {
            $kegiatansQuery->whereIn('unit_id', $filterUnitIds);
        }
        $kegiatans = $kegiatansQuery->get();

        // Ambil daftar Unit Kerja untuk dropdown filter (Hanya Kepala Bagian / Level 2)
        // Level 2 adalah unit yang parent_id-nya menunjuk ke unit yang parent_id-nya null (Sekretariat)
        $units = UnitKerja::whereHas('parent', function($q) {
            $q->whereNull('parent_id');
        })->orderBy('nama_unit', 'asc')->get();

        // Statistik sederhana untuk header
        $stats = [
            'total_tugas' => $tasks->count(),
            'tugas_berlangsung' => $tasks->whereIn('status', ['Berlangsung', 'Revisi', 'Menunggu Review'])->count(),
            'tugas_selesai' => $tasks->where('status', 'Selesai')->count(),
        ];

        return view('monitoring', compact('tasks', 'tasksGrouped', 'kegiatans', 'stats', 'units', 'unit_id'));
    }
}
