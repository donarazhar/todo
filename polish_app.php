<?php

// 1. Update Layouts (Toast & Error CSS)
$appFile = __DIR__ . '/resources/views/layouts/app.blade.php';
$appLayout = file_get_contents($appFile);

// Remove old alerts
$appLayout = preg_replace('/@if\(session\(\'success\'\)\).*?@endif/s', '', $appLayout);
$appLayout = preg_replace('/@if\(\$errors->any\(\)\).*?@endif/s', '', $appLayout);

// Add Toast CSS & Error CSS
$cssAdditions = <<<'EOCSS'
        /* Toast Notification */
        .toast {
            position: fixed; bottom: 30px; right: 30px; background: var(--bg-white);
            color: var(--text-900); padding: 14px 24px; border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg); display: flex; align-items: center; gap: 12px;
            transform: translateY(100px); opacity: 0; transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 9999; border-left: 5px solid var(--teal-500); font-weight: 600; font-size: 14px;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast-icon { width: 24px; height: 24px; background: var(--teal-100); color: var(--teal-600); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 14px; }
        
        /* Error Validation UI */
        .form-group input.is-invalid, .form-group select.is-invalid, .form-group textarea.is-invalid {
            border-color: #E53E3E; box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }
        .text-error { color: #E53E3E; font-size: 11px; font-weight: 600; margin-top: 4px; display: block; }
</style>
EOCSS;
$appLayout = str_replace('</style>', $cssAdditions, $appLayout);

// Add Toast HTML & JS
$toastHtml = <<<'EOT'
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div id="toast" class="toast {{ session('success') ? 'show' : '' }}">
        <div class="toast-icon">✓</div>
        <div id="toast-text">{{ session('success') ?? '' }}</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast');
            if(toast.classList.contains('show')) {
                setTimeout(() => { toast.classList.remove('show'); }, 4000);
            }
        });
        function showToast(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-text').innerText = msg;
            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); }, 4000);
        }
    </script>
</body>
EOT;
$appLayout = str_replace("        </div>\n    </div>\n</body>", $toastHtml, $appLayout);
file_put_contents($appFile, $appLayout);


// 2. Update Routes
$routesFile = __DIR__ . '/routes/web.php';
$routes = <<<'EOT'
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\MasterDataController;

Route::get('/', function () { return redirect()->route('login'); });

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
    
    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master.index');
    Route::post('/master-data/unit', [MasterDataController::class, 'storeUnit'])->name('master.unit.store');
    Route::delete('/master-data/unit/{id}', [MasterDataController::class, 'destroyUnit'])->name('master.unit.destroy');
    Route::post('/master-data/lokasi', [MasterDataController::class, 'storeLokasi'])->name('master.lokasi.store');
    Route::delete('/master-data/lokasi/{id}', [MasterDataController::class, 'destroyLokasi'])->name('master.lokasi.destroy');
    Route::post('/master-data/jenis', [MasterDataController::class, 'storeJenis'])->name('master.jenis.store');
    Route::delete('/master-data/jenis/{id}', [MasterDataController::class, 'destroyJenis'])->name('master.jenis.destroy');
    
    // Tasks
    Route::get('/pimpinan/tasks', [TaskController::class, 'pimpinanTasks'])->name('pimpinan.tasks');
    Route::get('/pegawai/tasks', [TaskController::class, 'pegawaiTasks'])->name('pegawai.tasks');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/{id}/report', [TaskController::class, 'submitReport'])->name('tasks.report');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    // Docs
    Route::view('/docs/erd', 'docs.erd')->name('docs.erd');
    Route::view('/docs/alur', 'docs.alur')->name('docs.alur');
});
EOT;
file_put_contents($routesFile, $routes);


// 3. Update Controllers
$dirControllers = __DIR__ . '/app/Http/Controllers/';

// KegiatanController
$kegiatanCtrl = file_get_contents($dirControllers . 'KegiatanController.php');
$kegiatanCtrl = str_replace("class KegiatanController extends Controller {\n", "class KegiatanController extends Controller {\n    public function destroy(\$id) { Kegiatan::findOrFail(\$id)->delete(); return redirect()->back()->with('success', 'Kegiatan berhasil dihapus.'); }\n", $kegiatanCtrl);
file_put_contents($dirControllers . 'KegiatanController.php', $kegiatanCtrl);

// TaskController
$taskCtrl = file_get_contents($dirControllers . 'TaskController.php');
$taskCtrl = str_replace("class TaskController extends Controller {\n", "class TaskController extends Controller {\n    public function destroy(\$id) { \$t=Task::findOrFail(\$id); if(\$t->created_by !== \Auth::id() && \Auth::user()->role->nama_role !== 'Admin') abort(403); \$t->delete(); return redirect()->back()->with('success', 'Tugas dibatalkan/dihapus.'); }\n", $taskCtrl);
file_put_contents($dirControllers . 'TaskController.php', $taskCtrl);

// MasterDataController
$masterCtrl = <<<'EOD'
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

    public function storeUnit(Request $request) {
        $request->validate(['nama_unit' => 'required', 'kode_unit' => 'required']);
        UnitKerja::create($request->all());
        return redirect()->back()->with('success', 'Unit Kerja berhasil ditambahkan.');
    }
    public function destroyUnit($id) { UnitKerja::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Unit dihapus.'); }

    public function storeLokasi(Request $request) {
        $request->validate(['nama_lokasi' => 'required']);
        LokasiKegiatan::create($request->all());
        return redirect()->back()->with('success', 'Lokasi berhasil ditambahkan.');
    }
    public function destroyLokasi($id) { LokasiKegiatan::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Lokasi dihapus.'); }

    public function storeJenis(Request $request) {
        $request->validate(['nama_jenis' => 'required']);
        JenisKegiatan::create($request->all());
        return redirect()->back()->with('success', 'Jenis Kegiatan berhasil ditambahkan.');
    }
    public function destroyJenis($id) { JenisKegiatan::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Jenis Kegiatan dihapus.'); }
}
EOD;
file_put_contents($dirControllers . 'MasterDataController.php', $masterCtrl);

// 4. Update Views to include Delete Buttons & Form Errors
$adminView = file_get_contents(__DIR__ . '/resources/views/dashboard/admin.blade.php');
// Add delete button column
$adminView = str_replace('<th>Status</th>', "<th>Status</th>\n                    <th>Aksi</th>", $adminView);
$deleteForm = <<<'EOT'
<td>
                        <form action="{{ route('kegiatan.destroy', $keg->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
EOT;
$adminView = str_replace('</span>
                    </td>', "</span>\n                    </td>\n                    $deleteForm", $adminView);
// Add error validations on form (simple example for nama_kegiatan)
$adminView = str_replace('name="nama_kegiatan" required>', 'name="nama_kegiatan" class="{{ $errors->has(\'nama_kegiatan\') ? \'is-invalid\' : \'\' }}" required>
                @error(\'nama_kegiatan\') <small class="text-error">{{ $message }}</small> @enderror', $adminView);
file_put_contents(__DIR__ . '/resources/views/dashboard/admin.blade.php', $adminView);

$pimpinanView = file_get_contents(__DIR__ . '/resources/views/dashboard/pimpinan.blade.php');
$pimpinanView = str_replace('<th>Laporan</th>', "<th>Laporan</th>\n                    <th>Aksi</th>", $pimpinanView);
$deleteFormTask = <<<'EOT'
<td>
                        <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Tarik kembali/hapus tugas ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
EOT;
$pimpinanView = str_replace('@endif
                    </td>', "@endif\n                    </td>\n                    $deleteFormTask", $pimpinanView);
file_put_contents(__DIR__ . '/resources/views/dashboard/pimpinan.blade.php', $pimpinanView);

echo "Polish script completed.\n";
