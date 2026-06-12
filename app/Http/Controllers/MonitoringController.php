<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Kegiatan;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    /**
     * Menampilkan halaman monitoring publik (Dashboard Presentasi).
     */
    public function index()
    {
        // Ambil data Tugas (Tasks)
        // Kita ambil semua tugas yang sedang berlangsung atau baru saja selesai,
        // diurutkan berdasarkan prioritas dan waktu update terakhir.
        $tasks = Task::with(['assignee.unitKerja'])
            ->orderByRaw("CASE 
                WHEN status = 'Berlangsung' THEN 1
                WHEN status = 'Revisi' THEN 2
                WHEN status = 'Menunggu Review' THEN 3
                ELSE 4 END")
            ->orderBy('tgl_selesai', 'asc')
            ->get();

        // Ambil data Jadwal Kegiatan
        // Kita ambil kegiatan bulan ini dan yang akan datang
        $startOfMonth = Carbon::now()->startOfMonth();
        $kegiatans = Kegiatan::with(['lokasi', 'unitKerja', 'peserta'])
            ->where('waktu_mulai', '>=', $startOfMonth)
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        // Data statistik singkat
        $stats = [
            'total_kegiatan' => $kegiatans->count(),
            'tugas_berlangsung' => $tasks->where('status', 'Berlangsung')->count(),
            'tugas_selesai' => $tasks->where('status', 'Selesai')->count(),
        ];

        return view('monitoring', compact('tasks', 'kegiatans', 'stats'));
    }
}
