<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller {
    public function index() {
        $user = Auth::user();
        $role = $user->role->nama_role;

        $kegiatans = Kegiatan::with('lokasi')->orderBy('waktu_mulai', 'asc')->get();
        $totalKegiatan = $kegiatans->count();

        // Tasks query based on role
        if ($role === 'Admin') {
            $semuaTugas = Task::all();
            $pegawaisQuery = User::whereHas('role', function($q) { $q->where('nama_role', 'Pegawai'); });
        } elseif ($role === 'Pimpinan') {
            $semuaTugas = Task::where('created_by', $user->id)->get();
            $pegawaisQuery = User::whereHas('role', function($q) { $q->where('nama_role', 'Pegawai'); })->where('unit_id', $user->unit_id);
        } else {
            // Pegawai
            $semuaTugas = Task::where('assigned_to', $user->id)->get();
            $pegawaisQuery = null;
        }

        $tugasBerlangsung = $semuaTugas->whereIn('status', ['Berlangsung', 'Menunggu Review', 'Revisi'])->count();
        $tugasSelesai = $semuaTugas->where('status', 'Selesai')->count();
        
        $totalBobot = $semuaTugas->sum('bobot');
        $bobotSelesai = $semuaTugas->where('status', 'Selesai')->sum('bobot');
        $efisiensi = $totalBobot > 0 ? round(($bobotSelesai / $totalBobot) * 100) : 0;

        $pegawaiProgress = [];
        if ($pegawaisQuery) {
            $pegawais = $pegawaisQuery->get();
            foreach ($pegawais as $p) {
                // All tasks assigned to this pegawai
                $tasks = Task::where('assigned_to', $p->id)->get();
                if ($tasks->count() > 0) {
                    $tb = $tasks->sum('bobot');
                    $bs = $tasks->where('status', 'Selesai')->sum('bobot');
                    $persen = $tb > 0 ? round(($bs / $tb) * 100) : 0;
                    $pegawaiProgress[] = [
                        'nama' => $p->nama,
                        'totalBobot' => $tb,
                        'bobotSelesai' => $bs,
                        'persen' => $persen
                    ];
                }
            }
        }

        return view('dashboard.index', compact(
            'kegiatans', 'totalKegiatan', 'tugasBerlangsung', 'tugasSelesai', 'efisiensi', 'pegawaiProgress'
        ));
    }
}