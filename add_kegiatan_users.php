<?php

// 1. Update KegiatanController.php
$kegControllerPath = __DIR__ . '/app/Http/Controllers/KegiatanController.php';
$kegController = file_get_contents($kegControllerPath);

$newStoreMethod = <<<'EOD'
    public function store(Request $request) {
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

        $data = $request->all();
        $data['created_by'] = Auth::id();
        $kegiatan = Kegiatan::create($data);

        if ($request->has('user_ids')) {
            $kegiatan->peserta()->sync($request->user_ids);
        }

        return redirect()->back()->with('success', 'Jadwal kegiatan berhasil ditambahkan!');
    }
EOD;

// Replace existing store method
$kegController = preg_replace('/public function store\(Request \$request\).*?Jadwal kegiatan berhasil ditambahkan!\'\);\s*}/s', $newStoreMethod, $kegController);
file_put_contents($kegControllerPath, $kegController);


// 2. Update admin.blade.php to add the Checkbox List
$adminBladePath = __DIR__ . '/resources/views/dashboard/admin.blade.php';
$adminBlade = file_get_contents($adminBladePath);

$checkboxHtml = <<<'EOD'
              <div class="form-group">
                  <label>Pegawai & Pimpinan Terlibat</label>
                  <div class="checkbox-list">
                      @foreach(\App\Models\User::whereHas('role', function($q) { $q->whereIn('nama_role', ['Pegawai', 'Pimpinan']); })->get() as $u)
                          <label>
                              <input type="checkbox" name="user_ids[]" value="{{ $u->id }}">
                              {{ $u->nama }} ({{ $u->role->nama_role }})
                          </label>
                      @endforeach
                  </div>
              </div>
              <div class="form-group">
                  <label>Waktu Mulai & Selesai</label>
EOD;

$adminBlade = str_replace('<div class="form-group">
                  <label>Waktu Mulai & Selesai</label>', $checkboxHtml, $adminBlade);
                  
file_put_contents($adminBladePath, $adminBlade);

echo "KegiatanController and admin.blade.php updated successfully.\n";
