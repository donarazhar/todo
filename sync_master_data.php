<?php

$content = <<<'EOD'
@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Pengelolaan Master Data</h2>
        <p>Kelola data unit kerja, pegawai, lokasi kegiatan, dan jenis kegiatan</p>
    </div>
</div>

<div x-data="{ tab: 'unit' }">
    <!-- Tab Master Data -->
    <div class="tabs-bar">
        <button class="tab-btn" :class="{ 'active': tab === 'unit' }" @click="tab = 'unit'">Unit Kerja</button>
        <button class="tab-btn" :class="{ 'active': tab === 'pegawai' }" @click="tab = 'pegawai'">Pegawai & Pimpinan</button>
        <button class="tab-btn" :class="{ 'active': tab === 'lokasi' }" @click="tab = 'lokasi'">Lokasi Kegiatan</button>
        <button class="tab-btn" :class="{ 'active': tab === 'jenis' }" @click="tab = 'jenis'">Jenis Kegiatan</button>
    </div>

    <!-- Tab: Unit Kerja -->
    <div class="tab-content" :class="{ 'active': tab === 'unit' }" x-show="tab === 'unit'">
        <div class="split-container">
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">➕</span> Tambah Unit Kerja</h3>
                <form action="{{ route('master.unit.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Unit Kerja</label>
                        <input type="text" name="nama_unit" class="{{ $errors->has('nama_unit') ? 'is-invalid' : '' }}" placeholder="Contoh: Divisi Perencanaan" required>
                        @error('nama_unit') <small class="text-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>Kode Unit</label>
                        <input type="text" name="kode_unit" class="{{ $errors->has('kode_unit') ? 'is-invalid' : '' }}" placeholder="Contoh: UNIT-REN" required>
                        @error('kode_unit') <small class="text-error">{{ $message }}</small> @enderror
                    </div>
                    <button type="submit" class="btn" style="width:100%;">Simpan Unit Kerja</button>
                </form>
            </div>
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">📋</span> Daftar Unit Kerja</h3>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Nama Unit Kerja</th><th>Kode</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($units as $unit)
                        <tr>
                            <td>{{ $unit->id }}</td>
                            <td><strong>{{ $unit->nama_unit }}</strong></td>
                            <td><span class="badge bg-belum">{{ $unit->kode_unit }}</span></td>
                            <td>
                                <form action="{{ route('master.unit.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Hapus unit kerja ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab: Pegawai & Pimpinan (View Only for now, based on previous scope) -->
    <div class="tab-content" :class="{ 'active': tab === 'pegawai' }" x-show="tab === 'pegawai'">
        <div class="split-container">
            <div class="section-box" style="opacity: 0.7; pointer-events: none;">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">➕</span> Tambah Pegawai / Pimpinan</h3>
                <p style="font-size: 12px; margin-bottom:15px; color:var(--text-500);">Catatan: Penambahan user saat ini dilakukan melalui Seeder database.</p>
                <form>
                    <div class="form-group"><label>Nama Lengkap</label><input type="text" disabled></div>
                    <div class="form-group"><label>Username</label><input type="text" disabled></div>
                    <button type="button" class="btn" style="width:100%;" disabled>Simpan Pegawai</button>
                </form>
            </div>
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">👥</span> Daftar Pegawai & Pimpinan</h3>
                <table>
                    <thead>
                        <tr><th>Nama</th><th>Username</th><th>Unit Kerja</th><th>Jabatan</th></tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td><strong>{{ $user->nama }}</strong></td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->unitKerja->nama_unit ?? '-' }}</td>
                            <td>
                                @if($user->role->nama_role == 'Pimpinan')
                                    <span class="badge bg-proses">👑 Pimpinan</span>
                                @elseif($user->role->nama_role == 'Admin')
                                    <span class="badge bg-belum">⚙️ Admin</span>
                                @else
                                    <span class="badge bg-selesai">👤 Pegawai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab: Lokasi Kegiatan -->
    <div class="tab-content" :class="{ 'active': tab === 'lokasi' }" x-show="tab === 'lokasi'">
        <div class="split-container">
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">➕</span> Tambah Lokasi Kegiatan</h3>
                <form action="{{ route('master.lokasi.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Lokasi</label>
                        <input type="text" name="nama_lokasi" class="{{ $errors->has('nama_lokasi') ? 'is-invalid' : '' }}" placeholder="Contoh: Ruang Rapat Utama Lt. 3" required>
                        @error('nama_lokasi') <small class="text-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>Alamat / Keterangan</label>
                        <textarea name="alamat" rows="2" class="{{ $errors->has('alamat') ? 'is-invalid' : '' }}" placeholder="Gedung A, Lantai 3, Kapasitas 50 orang"></textarea>
                    </div>
                    <button type="submit" class="btn" style="width:100%;">Simpan Lokasi</button>
                </form>
            </div>
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">📍</span> Daftar Lokasi</h3>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Nama Lokasi</th><th>Alamat / Keterangan</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($lokasi as $l)
                        <tr>
                            <td>{{ $l->id }}</td>
                            <td><strong>{{ $l->nama_lokasi }}</strong></td>
                            <td style="font-size:12px; color:var(--text-500);">{{ $l->alamat }}</td>
                            <td>
                                <form action="{{ route('master.lokasi.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab: Jenis Kegiatan -->
    <div class="tab-content" :class="{ 'active': tab === 'jenis' }" x-show="tab === 'jenis'">
        <div class="split-container">
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">➕</span> Tambah Jenis Kegiatan</h3>
                <form action="{{ route('master.jenis.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Jenis Kegiatan</label>
                        <input type="text" name="nama_jenis" class="{{ $errors->has('nama_jenis') ? 'is-invalid' : '' }}" placeholder="Contoh: Rapat Koordinasi" required>
                        @error('nama_jenis') <small class="text-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" rows="2" class="{{ $errors->has('keterangan') ? 'is-invalid' : '' }}" placeholder="Deskripsi singkat jenis kegiatan..."></textarea>
                    </div>
                    <button type="submit" class="btn" style="width:100%;">Simpan Jenis Kegiatan</button>
                </form>
            </div>
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">🏷️</span> Daftar Jenis Kegiatan</h3>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Jenis Kegiatan</th><th>Keterangan</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($jenis as $j)
                        <tr>
                            <td>{{ $j->id }}</td>
                            <td><strong>{{ $j->nama_jenis }}</strong></td>
                            <td style="font-size:12px; color:var(--text-500);">{{ $j->keterangan }}</td>
                            <td>
                                <form action="{{ route('master.jenis.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jenis kegiatan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
EOD;

file_put_contents('d:/3-File App/todo/resources/views/admin/master-data.blade.php', $content);
echo "master-data.blade.php exactly synced.\n";
