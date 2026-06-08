<?php

$baseDir = __DIR__;

// 1. Update routes/web.php
$routesFile = $baseDir . '/routes/web.php';
$routes = file_get_contents($routesFile);
if (strpos($routes, "Route::put('/kegiatan/{id}'") === false) {
    $routes = str_replace(
        "Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');",
        "Route::put('/kegiatan/{id}', [KegiatanController::class, 'update'])->name('kegiatan.update');\n    Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');",
        $routes
    );
    file_put_contents($routesFile, $routes);
    echo "Routes updated.\n";
}

// 2. Update KegiatanController.php
$kegControllerPath = $baseDir . '/app/Http/Controllers/KegiatanController.php';
$kegController = file_get_contents($kegControllerPath);
if (strpos($kegController, "public function update") === false) {
    $newUpdateMethod = <<<'EOD'
    public function update(Request $request, $id) {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:200',
            'jenis_id' => 'required|exists:jenis_kegiatans,id',
            'unit_id' => 'required|exists:unit_kerjas,id',
            'lokasi_id' => 'required|exists:lokasi_kegiatans,id',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'status' => 'required|in:Belum,Berlangsung,Selesai',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->update($request->except('user_ids'));

        if ($request->has('user_ids')) {
            $kegiatan->peserta()->sync($request->user_ids);
        } else {
            $kegiatan->peserta()->detach();
        }

        return redirect()->back()->with('success', 'Jadwal kegiatan berhasil diperbarui!');
    }
}
EOD;
    $kegController = preg_replace('/}\s*$/s', "\n$newUpdateMethod", $kegController);
    file_put_contents($kegControllerPath, $kegController);
    echo "KegiatanController updated.\n";
}

// 3. Update admin.blade.php
$adminBladePath = $baseDir . '/resources/views/dashboard/admin.blade.php';
$adminBlade = file_get_contents($adminBladePath);

// Wrap main split container in AlpineJS x-data if not already wrapped
if (strpos($adminBlade, 'x-data="{') === false) {
    $adminBlade = str_replace(
        '<div class="split-container">',
        '<div class="split-container" x-data="{ 
            editModalOpen: false, 
            editId: \'\', 
            editData: { user_ids: [] },
            openEditModal(id, data, users) {
                this.editId = id;
                this.editData = data;
                this.editData.user_ids = users;
                this.editModalOpen = true;
            }
        }">',
        $adminBlade
    );
}

// Replace Action Button
$oldButton = <<<'EOD'
                    <td>
                        <form action="{{ route('kegiatan.destroy', $keg->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
EOD;

$newButton = <<<'EOD'
                    <td style="display:flex; gap:5px;">
                        <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $keg->id }}, { nama_kegiatan: '{{ addslashes($keg->nama_kegiatan) }}', jenis_id: '{{ $keg->jenis_id }}', unit_id: '{{ $keg->unit_id }}', lokasi_id: '{{ $keg->lokasi_id }}', waktu_mulai: '{{ $keg->waktu_mulai->format('Y-m-d\TH:i') }}', waktu_selesai: '{{ $keg->waktu_selesai->format('Y-m-d\TH:i') }}', status: '{{ $keg->status }}' }, {{ json_encode($keg->peserta->pluck('id')->toArray()) }})">✏️</button>
                        <form action="{{ route('kegiatan.destroy', $keg->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
EOD;
$adminBlade = str_replace($oldButton, $newButton, $adminBlade);

// Add Modal Overlay at the end before @endsection
$modalHtml = <<<'EOD'
    <!-- ============================
         EDIT MODAL (ALPINE JS)
    ============================ -->
    <div class="modal-overlay" :class="{ 'show': editModalOpen }" x-show="editModalOpen" style="display: none;" x-transition>
        <div class="modal-box" @click.away="editModalOpen = false">
            <div class="modal-header">
                <h3>✏️ Edit Jadwal Kegiatan</h3>
                <button type="button" class="modal-close" @click="editModalOpen = false">×</button>
            </div>
            <form :action="'{{ url('/kegiatan') }}/' + editId" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" x-model="editData.nama_kegiatan" required>
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                    <div class="form-group">
                        <label>Jenis Kegiatan</label>
                        <select name="jenis_id" x-model="editData.jenis_id" required>
                            @foreach(\App\Models\JenisKegiatan::all() as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Unit Pelaksana</label>
                        <select name="unit_id" x-model="editData.unit_id" required>
                            @foreach(\App\Models\UnitKerja::all() as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <select name="lokasi_id" x-model="editData.lokasi_id" required>
                        @foreach(\App\Models\LokasiKegiatan::all() as $lokasi)
                            <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Pegawai & Pimpinan Terlibat</label>
                    <div class="checkbox-list" style="max-height: 100px;">
                        @foreach(\App\Models\User::whereHas('role', function($q) { $q->whereIn('nama_role', ['Pegawai', 'Pimpinan']); })->get() as $u)
                            <label>
                                <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" :checked="editData.user_ids && editData.user_ids.includes({{ $u->id }})">
                                {{ $u->nama }} ({{ $u->role->nama_role }})
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label>Waktu Mulai & Selesai</label>
                    <div style="display:flex; gap:10px;">
                        <input type="datetime-local" name="waktu_mulai" x-model="editData.waktu_mulai" required>
                        <input type="datetime-local" name="waktu_selesai" x-model="editData.waktu_selesai" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" x-model="editData.status" required>
                        <option value="Belum">Belum Berlangsung</option>
                        <option value="Berlangsung">Sedang Berlangsung</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" @click="editModalOpen = false">Batal</button>
                    <button type="submit" class="btn">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
EOD;

if (strpos($adminBlade, "EDIT MODAL (ALPINE JS)") === false) {
    $adminBlade = str_replace("</div>\n@endsection", $modalHtml, $adminBlade);
    file_put_contents($adminBladePath, $adminBlade);
    echo "View admin.blade.php updated.\n";
}

echo "All complete.\n";
