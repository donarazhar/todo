@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Pengelolaan Master Data</h2>
        <p>Kelola data unit kerja, pegawai, lokasi kegiatan, dan jenis kegiatan</p>
    </div>
</div>

<div x-data="{ 
    tab: 'unit',
    editModalOpen: false,
    editType: '',
    editId: '',
    editData: {},
    openEditModal(type, id, data) {
        this.editType = type;
        this.editId = id;
        this.editData = data;
        this.editModalOpen = true;
    }
}">
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
                    <div class="form-group">
                        <label>Kepala Unit</label>
                        <select name="kepala_unit_id" class="{{ $errors->has('kepala_unit_id') ? 'is-invalid' : '' }}">
                            <option value="">— Pilih Pimpinan (Opsional) —</option>
                            @foreach($users->where('role.nama_role', 'Pimpinan') as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                        @error('kepala_unit_id') <small class="text-error">{{ $message }}</small> @enderror
                    </div>
                    <button type="submit" class="btn" style="width:100%;">Simpan Unit Kerja</button>
                </form>
            </div>
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">📋</span> Daftar Unit Kerja</h3>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Nama Unit Kerja</th><th>Kode</th><th>Kepala Unit</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($units as $unit)
                        <tr>
                            <td>{{ $unit->id }}</td>
                            <td><strong>{{ $unit->nama_unit }}</strong></td>
                            <td><span class="badge bg-belum">{{ $unit->kode_unit }}</span></td>
                            <td>{{ $unit->kepalaUnit->nama ?? '—' }}</td>
                            <td style="display:flex; gap:5px;">
                                <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('unit', {{ $unit->id }}, { nama_unit: '{{ addslashes($unit->nama_unit) }}', kode_unit: '{{ addslashes($unit->kode_unit) }}', kepala_unit_id: '{{ $unit->kepala_unit_id }}' })">✏️</button>
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

    <!-- Tab: Pegawai & Pimpinan -->
    <div class="tab-content" :class="{ 'active': tab === 'pegawai' }" x-show="tab === 'pegawai'">
        <div class="split-container">
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">➕</span> Tambah Pegawai / Pimpinan</h3>
                <form action="{{ route('master.user.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="{{ $errors->has('nama') ? 'is-invalid' : '' }}" placeholder="Masukkan nama lengkap" required>
                        @error('nama') <small class="text-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="{{ $errors->has('username') ? 'is-invalid' : '' }}" placeholder="Masukkan username" required>
                        @error('username') <small class="text-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>Password Awal</label>
                        <input type="password" name="password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Masukkan password" required>
                        @error('password') <small class="text-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>Unit Kerja</label>
                        <select name="unit_id" required>
                            <option value="">— Pilih Unit Kerja —</option>
                            @foreach($units as $u)
                                <option value="{{ $u->id }}">{{ $u->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Role / Jabatan</label>
                        <select name="role_id" required>
                            <option value="">— Pilih Role —</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}">{{ $r->nama_role }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn" style="width:100%;">Simpan Pegawai</button>
                </form>
            </div>
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">👥</span> Daftar Pegawai & Pimpinan</h3>
                <table>
                    <thead>
                        <tr><th>Nama</th><th>Username</th><th>Unit Kerja</th><th>Jabatan</th><th>Aksi</th></tr>
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
                            <td style="display:flex; gap:5px;">
                                <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('user', {{ $user->id }}, { nama: '{{ addslashes($user->nama) }}', username: '{{ addslashes($user->username) }}', unit_id: '{{ $user->unit_id }}', role_id: '{{ $user->role_id }}' })">✏️</button>
                                <form action="{{ route('master.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
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
                    </div>
                    <div class="form-group">
                        <label>Alamat / Keterangan</label>
                        <textarea name="alamat" rows="2" placeholder="Gedung A, Lantai 3, Kapasitas 50 orang"></textarea>
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
                            <td style="display:flex; gap:5px;">
                                <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('lokasi', {{ $l->id }}, { nama_lokasi: '{{ addslashes($l->nama_lokasi) }}', alamat: '{{ addslashes($l->alamat) }}' })">✏️</button>
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
                        <input type="text" name="nama_jenis" required placeholder="Contoh: Rapat Koordinasi">
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" rows="2" placeholder="Deskripsi singkat jenis kegiatan..."></textarea>
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
                            <td style="display:flex; gap:5px;">
                                <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('jenis', {{ $j->id }}, { nama_jenis: '{{ addslashes($j->nama_jenis) }}', keterangan: '{{ addslashes($j->keterangan) }}' })">✏️</button>
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

    <!-- ============================
         EDIT MODAL (ALPINE JS)
    ============================ -->
    <div class="modal-overlay" :class="{ 'show': editModalOpen }" x-show="editModalOpen" style="display: none;" x-transition>
        <div class="modal-box" @click.away="editModalOpen = false">
            <div class="modal-header">
                <h3>✏️ Edit Data</h3>
                <button type="button" class="modal-close" @click="editModalOpen = false">×</button>
            </div>
            
            <!-- Form untuk Unit Kerja -->
            <form x-show="editType === 'unit'" :action="'{{ url('/master-data/unit') }}/' + editId" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Nama Unit Kerja</label>
                    <input type="text" name="nama_unit" x-model="editData.nama_unit" required>
                </div>
                <div class="form-group">
                    <label>Kode Unit</label>
                    <input type="text" name="kode_unit" x-model="editData.kode_unit" required>
                </div>
                <div class="form-group">
                    <label>Kepala Unit</label>
                    <select name="kepala_unit_id" x-model="editData.kepala_unit_id">
                        <option value="">— Pilih Pimpinan (Opsional) —</option>
                        @foreach($users->where('role.nama_role', 'Pimpinan') as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" @click="editModalOpen = false">Batal</button>
                    <button type="submit" class="btn">💾 Simpan Perubahan</button>
                </div>
            </form>

            <!-- Form untuk User -->
            <form x-show="editType === 'user'" :action="'{{ url('/master-data/user') }}/' + editId" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" x-model="editData.nama" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" x-model="editData.username" required>
                </div>
                <div class="form-group">
                    <label>Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="***">
                </div>
                <div class="form-group">
                    <label>Unit Kerja</label>
                    <select name="unit_id" x-model="editData.unit_id" required>
                        @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->nama_unit }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Role / Jabatan</label>
                    <select name="role_id" x-model="editData.role_id" required>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_role }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" @click="editModalOpen = false">Batal</button>
                    <button type="submit" class="btn">💾 Simpan Perubahan</button>
                </div>
            </form>

            <!-- Form untuk Lokasi -->
            <form x-show="editType === 'lokasi'" :action="'{{ url('/master-data/lokasi') }}/' + editId" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Nama Lokasi</label>
                    <input type="text" name="nama_lokasi" x-model="editData.nama_lokasi" required>
                </div>
                <div class="form-group">
                    <label>Alamat / Keterangan</label>
                    <textarea name="alamat" rows="2" x-model="editData.alamat"></textarea>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" @click="editModalOpen = false">Batal</button>
                    <button type="submit" class="btn">💾 Simpan Perubahan</button>
                </div>
            </form>

            <!-- Form untuk Jenis -->
            <form x-show="editType === 'jenis'" :action="'{{ url('/master-data/jenis') }}/' + editId" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Nama Jenis Kegiatan</label>
                    <input type="text" name="nama_jenis" x-model="editData.nama_jenis" required>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" rows="2" x-model="editData.keterangan"></textarea>
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