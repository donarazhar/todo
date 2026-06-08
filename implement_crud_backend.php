<?php

$baseDir = __DIR__;

// 1. Update UnitKerja.php Model
$unitModelFile = $baseDir . '/app/Models/UnitKerja.php';
$unitModel = <<<'EOD'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model {
    use HasFactory;
    protected $fillable = ['nama_unit', 'kode_unit', 'kepala_unit_id'];

    public function users() {
        return $this->hasMany(User::class, 'unit_id');
    }
    
    public function kepalaUnit() {
        return $this->belongsTo(User::class, 'kepala_unit_id');
    }
}
EOD;
file_put_contents($unitModelFile, $unitModel);

// 2. Update routes/web.php
$routesFile = $baseDir . '/routes/web.php';
$routes = file_get_contents($routesFile);
$newRoutes = <<<'EOD'
    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master.index');
    
    // Unit Kerja
    Route::post('/master-data/unit', [MasterDataController::class, 'storeUnit'])->name('master.unit.store');
    Route::put('/master-data/unit/{id}', [MasterDataController::class, 'updateUnit'])->name('master.unit.update');
    Route::delete('/master-data/unit/{id}', [MasterDataController::class, 'destroyUnit'])->name('master.unit.destroy');
    
    // Lokasi
    Route::post('/master-data/lokasi', [MasterDataController::class, 'storeLokasi'])->name('master.lokasi.store');
    Route::put('/master-data/lokasi/{id}', [MasterDataController::class, 'updateLokasi'])->name('master.lokasi.update');
    Route::delete('/master-data/lokasi/{id}', [MasterDataController::class, 'destroyLokasi'])->name('master.lokasi.destroy');
    
    // Jenis
    Route::post('/master-data/jenis', [MasterDataController::class, 'storeJenis'])->name('master.jenis.store');
    Route::put('/master-data/jenis/{id}', [MasterDataController::class, 'updateJenis'])->name('master.jenis.update');
    Route::delete('/master-data/jenis/{id}', [MasterDataController::class, 'destroyJenis'])->name('master.jenis.destroy');
    
    // Users
    Route::post('/master-data/user', [MasterDataController::class, 'storeUser'])->name('master.user.store');
    Route::put('/master-data/user/{id}', [MasterDataController::class, 'updateUser'])->name('master.user.update');
    Route::delete('/master-data/user/{id}', [MasterDataController::class, 'destroyUser'])->name('master.user.destroy');
EOD;
$routes = preg_replace('/Route::get\(\'\/master-data\'.*?master\.jenis\.destroy\'\);/s', $newRoutes, $routes);
file_put_contents($routesFile, $routes);

// 3. Update MasterDataController.php
$controllerFile = $baseDir . '/app/Http/Controllers/MasterDataController.php';
$controllerContent = <<<'EOD'
<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\UnitKerja;
use App\Models\LokasiKegiatan;
use App\Models\JenisKegiatan;
use Illuminate\Support\Facades\Hash;

class MasterDataController extends Controller {
    public function index() {
        $users = User::with(['role', 'unitKerja'])->get();
        $roles = Role::all();
        $units = UnitKerja::with('kepalaUnit')->get();
        $lokasi = LokasiKegiatan::all();
        $jenis = JenisKegiatan::all();
        return view('admin.master-data', compact('users', 'roles', 'units', 'lokasi', 'jenis'));
    }

    // -- UNIT KERJA --
    public function storeUnit(Request $request) {
        $request->validate(['nama_unit' => 'required', 'kode_unit' => 'required']);
        UnitKerja::create($request->all());
        return redirect()->back()->with('success', 'Unit Kerja berhasil ditambahkan.');
    }
    public function updateUnit(Request $request, $id) {
        $request->validate(['nama_unit' => 'required', 'kode_unit' => 'required']);
        UnitKerja::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success', 'Unit Kerja berhasil diubah.');
    }
    public function destroyUnit($id) { UnitKerja::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Unit dihapus.'); }

    // -- LOKASI --
    public function storeLokasi(Request $request) {
        $request->validate(['nama_lokasi' => 'required']);
        LokasiKegiatan::create($request->all());
        return redirect()->back()->with('success', 'Lokasi berhasil ditambahkan.');
    }
    public function updateLokasi(Request $request, $id) {
        $request->validate(['nama_lokasi' => 'required']);
        LokasiKegiatan::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success', 'Lokasi berhasil diubah.');
    }
    public function destroyLokasi($id) { LokasiKegiatan::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Lokasi dihapus.'); }

    // -- JENIS --
    public function storeJenis(Request $request) {
        $request->validate(['nama_jenis' => 'required']);
        JenisKegiatan::create($request->all());
        return redirect()->back()->with('success', 'Jenis Kegiatan berhasil ditambahkan.');
    }
    public function updateJenis(Request $request, $id) {
        $request->validate(['nama_jenis' => 'required']);
        JenisKegiatan::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success', 'Jenis Kegiatan berhasil diubah.');
    }
    public function destroyJenis($id) { JenisKegiatan::findOrFail($id)->delete(); return redirect()->back()->with('success', 'Jenis Kegiatan dihapus.'); }

    // -- USERS --
    public function storeUser(Request $request) {
        $request->validate([
            'nama' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'unit_id' => 'required|exists:unit_kerjas,id'
        ]);
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->back()->with('success', 'Pegawai / Pimpinan berhasil ditambahkan.');
    }
    public function updateUser(Request $request, $id) {
        $request->validate([
            'nama' => 'required|string',
            'username' => 'required|string|unique:users,username,'.$id,
            'role_id' => 'required|exists:roles,id',
            'unit_id' => 'required|exists:unit_kerjas,id'
        ]);
        $user = User::findOrFail($id);
        $data = $request->except('password');
        if($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->back()->with('success', 'Data user berhasil diubah.');
    }
    public function destroyUser($id) { User::findOrFail($id)->delete(); return redirect()->back()->with('success', 'User dihapus.'); }
}
EOD;
file_put_contents($controllerFile, $controllerContent);

echo "Backend models, routes, and controllers successfully updated for full CRUD.\n";
