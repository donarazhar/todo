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
    Route::put('/kegiatan/{id}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
    
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
    
    // Tasks
    Route::get('/pimpinan/tasks', [TaskController::class, 'pimpinanTasks'])->name('pimpinan.tasks');
    Route::get('/pegawai/tasks', [TaskController::class, 'pegawaiTasks'])->name('pegawai.tasks');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/tasks/{id}/report', [TaskController::class, 'submitReport'])->name('tasks.report');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    // Docs
    Route::view('/docs/erd', 'docs.erd')->name('docs.erd');
    Route::view('/docs/alur', 'docs.alur')->name('docs.alur');
});