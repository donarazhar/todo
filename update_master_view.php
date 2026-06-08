<?php
$content = <<<'EOT'
@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Master Data Management</h2>
        <p>Pengelolaan data dasar untuk referensi tabel relasional sistem</p>
    </div>
</div>

<div class="section-box" x-data="{ tab: 'users' }">
    <div class="tab-nav">
        <button class="tab-btn" :class="{ 'active': tab === 'users' }" @click="tab = 'users'">Pengguna</button>
        <button class="tab-btn" :class="{ 'active': tab === 'units' }" @click="tab = 'units'">Unit Kerja</button>
        <button class="tab-btn" :class="{ 'active': tab === 'lokasi' }" @click="tab = 'lokasi'">Lokasi</button>
        <button class="tab-btn" :class="{ 'active': tab === 'jenis' }" @click="tab = 'jenis'">Jenis Kegiatan</button>
    </div>

    <!-- TAB USERS -->
    <div class="tab-content" :class="{ 'active': tab === 'users' }">
        <table>
            <thead><tr><th>Nama</th><th>Username</th><th>Role</th><th>Unit Kerja</th></tr></thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td><strong>{{ $user->nama }}</strong></td>
                    <td><code>{{ $user->username }}</code></td>
                    <td><span class="badge bg-belum">{{ $user->role->nama_role }}</span></td>
                    <td>{{ $user->unitKerja->nama_unit ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- TAB UNIT KERJA -->
    <div class="tab-content" :class="{ 'active': tab === 'units' }">
        <form action="{{ route('master.unit.store') }}" method="POST" style="display:flex; gap:10px; margin-bottom:15px;">
            @csrf
            <input type="text" name="kode_unit" placeholder="Kode Unit" required class="form-control" style="padding:8px; border:1px solid #ccc; border-radius:5px;">
            <input type="text" name="nama_unit" placeholder="Nama Unit" required class="form-control" style="padding:8px; border:1px solid #ccc; border-radius:5px; flex-grow:1;">
            <button type="submit" class="btn btn-sm">Tambah</button>
        </form>
        <table>
            <thead><tr><th>Kode</th><th>Nama Unit Kerja</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($units as $unit)
                <tr>
                    <td><strong>{{ $unit->kode_unit }}</strong></td>
                    <td>{{ $unit->nama_unit }}</td>
                    <td>
                        <form action="{{ route('master.unit.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                            @csrf @method('DELETE') <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- TAB LOKASI -->
    <div class="tab-content" :class="{ 'active': tab === 'lokasi' }">
        <form action="{{ route('master.lokasi.store') }}" method="POST" style="display:flex; gap:10px; margin-bottom:15px;">
            @csrf
            <input type="text" name="nama_lokasi" placeholder="Nama Lokasi" required class="form-control" style="padding:8px; border:1px solid #ccc; border-radius:5px; flex-grow:1;">
            <input type="text" name="alamat" placeholder="Keterangan" class="form-control" style="padding:8px; border:1px solid #ccc; border-radius:5px; flex-grow:2;">
            <button type="submit" class="btn btn-sm">Tambah</button>
        </form>
        <table>
            <thead><tr><th>Nama Lokasi</th><th>Keterangan</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($lokasi as $l)
                <tr>
                    <td><strong>{{ $l->nama_lokasi }}</strong></td>
                    <td>{{ $l->alamat }}</td>
                    <td>
                        <form action="{{ route('master.lokasi.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                            @csrf @method('DELETE') <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- TAB JENIS -->
    <div class="tab-content" :class="{ 'active': tab === 'jenis' }">
        <form action="{{ route('master.jenis.store') }}" method="POST" style="display:flex; gap:10px; margin-bottom:15px;">
            @csrf
            <input type="text" name="nama_jenis" placeholder="Jenis Kegiatan" required class="form-control" style="padding:8px; border:1px solid #ccc; border-radius:5px; flex-grow:1;">
            <input type="text" name="keterangan" placeholder="Keterangan" class="form-control" style="padding:8px; border:1px solid #ccc; border-radius:5px; flex-grow:2;">
            <button type="submit" class="btn btn-sm">Tambah</button>
        </form>
        <table>
            <thead><tr><th>Nama Jenis Kegiatan</th><th>Keterangan</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($jenis as $j)
                <tr>
                    <td><strong>{{ $j->nama_jenis }}</strong></td>
                    <td>{{ $j->keterangan }}</td>
                    <td>
                        <form action="{{ route('master.jenis.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                            @csrf @method('DELETE') <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
EOT;

file_put_contents(__DIR__ . '/resources/views/admin/master-data.blade.php', $content);
echo "master-data.blade.php updated.\n";
