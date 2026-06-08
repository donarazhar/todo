<?php
$controllers = [
    'DashboardController.php' => <<<'EOD'
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;
use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller {
    public function index() {
        $kegiatans = Kegiatan::with('lokasi')->orderBy('waktu_mulai', 'asc')->get();
        $totalKegiatan = $kegiatans->count();
        $semuaTugas = Task::all();
        $tugasBerlangsung = $semuaTugas->where('status', 'Berlangsung')->count();
        $tugasSelesai = $semuaTugas->where('status', 'Selesai')->count();
        
        $totalBobot = $semuaTugas->sum('bobot');
        $bobotSelesai = $semuaTugas->where('status', 'Selesai')->sum('bobot');
        $efisiensi = $totalBobot > 0 ? round(($bobotSelesai / $totalBobot) * 100) : 0;

        $pegawais = User::whereHas('role', function($q) {
            $q->where('nama_role', 'Pegawai');
        })->get();
        
        $pegawaiProgress = [];
        foreach ($pegawais as $p) {
            $tasks = $semuaTugas->where('assigned_to', $p->id);
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

        return view('dashboard.index', compact(
            'kegiatans', 'totalKegiatan', 'tugasBerlangsung', 'tugasSelesai', 'efisiensi', 'pegawaiProgress'
        ));
    }
}
EOD,
    'MasterDataController.php' => <<<'EOD'
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\LokasiKegiatan;
use App\Models\JenisKegiatan;

class MasterDataController extends Controller {
    public function index() {
        $users = User::with(['role', 'unitKerja'])->get();
        $units = UnitKerja::all();
        $lokasi = LokasiKegiatan::all();
        $jenis = JenisKegiatan::all();
        
        return view('admin.master-data', compact('users', 'units', 'lokasi', 'jenis'));
    }
}
EOD
];

$dir = __DIR__ . '/app/Http/Controllers/';
foreach ($controllers as $filename => $content) {
    file_put_contents($dir . $filename, $content);
    echo "Updated Controller: $filename\n";
}
