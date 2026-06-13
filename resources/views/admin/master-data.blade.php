@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Pengelolaan Master Data</h2>
        <p>Kelola data unit kerja, pegawai, lokasi kegiatan, dan jenis kegiatan</p>
    </div>
</div>

<div x-data="{ 
    tab: '{{ $activeTab }}',
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
    @push('styles')
    <style>
        .master-cards-mobile {
            display: none;
            flex-direction: column;
            gap: 12px;
        }
        .master-cards-mobile .task-card {
            background: var(--bg-white);
            border: 1px solid var(--border-200);
            border-radius: var(--radius-lg);
            overflow: hidden;
            padding: 16px;
        }
        .master-cards-mobile .task-card-title {
            font-weight: 700;
            color: var(--text-900);
            font-size: 14px;
            margin-bottom: 4px;
        }
        .master-cards-mobile .task-card-subtitle {
            font-size: 12px;
            color: var(--text-500);
            margin-bottom: 12px;
        }
        .master-cards-mobile .task-card-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border-100);
        }

        @media (max-width: 1024px) {
            .split-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .master-table-wrap {
                display: none !important;
            }
            .master-cards-mobile {
                display: flex !important;
            }
        }
    </style>
    @endpush
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
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon"><i class="bi bi-plus-lg"></i></span> Tambah Unit Kerja</h3>
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
                        <label>Induk Unit Kerja (Opsional)</label>
                        <select name="parent_id" class="{{ $errors->has('parent_id') ? 'is-invalid' : '' }}">
                            <option value="">— Tidak Ada Induk (Level Tertinggi) —</option>
                            @foreach($allUnits as $u)
                                <option value="{{ $u->id }}">{{ $u->nama_unit }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <small class="text-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>Kepala Unit</label>
                        <select name="kepala_unit_id" class="{{ $errors->has('kepala_unit_id') ? 'is-invalid' : '' }}">
                            <option value="">— Pilih Pimpinan (Opsional) —</option>
                            @foreach($allUsers->where('role.nama_role', 'Pimpinan') as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                        @error('kepala_unit_id') <small class="text-error">{{ $message }}</small> @enderror
                    </div>
                    <button type="submit" class="btn" style="width:100%;">Simpan Unit Kerja</button>
                </form>
            </div>
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon"><i class="bi bi-card-checklist"></i></span> Daftar Unit Kerja</h3>
                <div class="master-table-wrap" style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr><th>ID</th><th>Informasi Unit</th><th>Hierarki & Pimpinan</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($units as $unit)
                            <tr>
                                <td>{{ $unit->id }}</td>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-900);">{{ $unit->nama_unit }}</div>
                                    <div style="font-size: 12px; margin-top: 4px;"><span class="badge bg-belum">{{ $unit->kode_unit }}</span></div>
                                </td>
                                <td>
                                    <div style="font-size: 12.5px; color: var(--text-700);"><i class="bi bi-diagram-3" style="color: var(--text-400);"></i> Induk: {{ $unit->parent->nama_unit ?? '—' }}</div>
                                    <div style="font-size: 12.5px; color: var(--text-700); margin-top: 2px;"><i class="bi bi-person" style="color: var(--text-400);"></i> Kepala: <strong>{{ $unit->kepalaUnit->nama ?? '—' }}</strong></div>
                                </td>
                                <td style="display:flex; gap:5px;">
                                    <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('unit', {{ $unit->id }}, { nama_unit: '{{ addslashes($unit->nama_unit) }}', kode_unit: '{{ addslashes($unit->kode_unit) }}', kepala_unit_id: '{{ $unit->kepala_unit_id }}', parent_id: '{{ $unit->parent_id }}' })"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('master.unit.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Hapus unit kerja ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View: Unit Kerja --}}
                <div class="master-cards-mobile">
                    @forelse($units as $unit)
                    <div class="task-card">
                        <div class="task-card-title">{{ $unit->nama_unit }}</div>
                        <div class="task-card-subtitle">Kode: <span class="badge bg-belum">{{ $unit->kode_unit }}</span> | ID: {{ $unit->id }}</div>
                        <div style="font-size: 12px; color: var(--text-600); margin-bottom: 4px;"><i class="bi bi-diagram-3"></i> Induk: {{ $unit->parent->nama_unit ?? '—' }}</div>
                        <div style="font-size: 12px; color: var(--text-600);"><i class="bi bi-person"></i> Kepala: {{ $unit->kepalaUnit->nama ?? '—' }}</div>
                        <div class="task-card-actions">
                            <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('unit', {{ $unit->id }}, { nama_unit: '{{ addslashes($unit->nama_unit) }}', kode_unit: '{{ addslashes($unit->kode_unit) }}', kepala_unit_id: '{{ $unit->kepala_unit_id }}', parent_id: '{{ $unit->parent_id }}' })"><i class="bi bi-pencil"></i> Edit</button>
                            <form action="{{ route('master.unit.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Hapus unit kerja ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state"><p>Belum ada data unit kerja.</p></div>
                    @endforelse
                </div>

                <div style="margin-top: 20px;">
                    {{ $units->appends(['tab' => 'unit', 'user_page' => request('user_page'), 'lokasi_page' => request('lokasi_page'), 'jenis_page' => request('jenis_page')])->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Pegawai & Pimpinan -->
    <div class="tab-content" :class="{ 'active': tab === 'pegawai' }" x-show="tab === 'pegawai'">
        <div class="split-container">
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon"><i class="bi bi-plus-lg"></i></span> Tambah Pegawai / Pimpinan</h3>
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
                        <label>Email (Untuk Google Login)</label>
                        <input type="email" name="email" class="{{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Contoh: pegawai@alazhar.org">
                        @error('email') <small class="text-error">{{ $message }}</small> @enderror
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
                            @foreach($allUnits as $u)
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
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon"><i class="bi bi-people"></i></span> Daftar Pegawai</h3>
                <div class="master-table-wrap" style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr><th>ID</th><th>Profil Pegawai</th><th>Unit & Jabatan</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-900);">{{ $user->nama }}</div>
                                    <div style="font-size: 12px; color: var(--text-500); margin-top: 2px;"><i class="bi bi-person-badge"></i> {{ $user->username }} &nbsp;&bull;&nbsp; <i class="bi bi-envelope"></i> {{ $user->email ?? '—' }}</div>
                                </td>
                                <td>
                                    <div style="font-size: 12.5px; color: var(--text-700); margin-bottom: 6px;"><i class="bi bi-building" style="color: var(--text-400);"></i> {{ $user->unitKerja->nama_unit ?? '—' }}</div>
                                    <div>
                                        @if($user->role->nama_role == 'Admin')
                                            <span class="badge bg-belum"><i class="bi bi-shield-lock"></i> Admin</span>
                                        @elseif($user->role->nama_role == 'Pimpinan')
                                            <span class="badge bg-proses"><i class="bi bi-person-badge"></i> Pimpinan</span>
                                        @else
                                            <span class="badge bg-selesai"><i class="bi bi-person"></i> Pegawai</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="display:flex; gap:5px;">
                                    <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('user', {{ $user->id }}, { nama: '{{ addslashes($user->nama) }}', username: '{{ addslashes($user->username) }}', email: '{{ addslashes($user->email ?? '') }}', unit_id: '{{ $user->unit_id }}', role_id: '{{ $user->role_id }}' })"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('master.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View: Pegawai --}}
                <div class="master-cards-mobile">
                    @forelse($users as $user)
                    <div class="task-card">
                        <div class="task-card-title">{{ $user->nama }}</div>
                        <div class="task-card-subtitle">{{ $user->username }} | Email: {{ $user->email ?? '—' }} | ID: {{ $user->id }}</div>
                        <div style="font-size: 12px; color: var(--text-600); margin-bottom: 8px;"><i class="bi bi-building"></i> {{ $user->unitKerja->nama_unit ?? '—' }}</div>
                        <div>
                            @if($user->role->nama_role == 'Admin')
                                <span class="badge bg-belum"><i class="bi bi-shield-lock"></i> Admin</span>
                            @elseif($user->role->nama_role == 'Pimpinan')
                                <span class="badge bg-proses"><i class="bi bi-person-badge"></i> Pimpinan</span>
                            @else
                                <span class="badge bg-selesai"><i class="bi bi-person"></i> Pegawai</span>
                            @endif
                        </div>
                        <div class="task-card-actions">
                            <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('user', {{ $user->id }}, { nama: '{{ addslashes($user->nama) }}', username: '{{ addslashes($user->username) }}', email: '{{ addslashes($user->email ?? '') }}', unit_id: '{{ $user->unit_id }}', role_id: '{{ $user->role_id }}' })"><i class="bi bi-pencil"></i> Edit</button>
                            <form action="{{ route('master.user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state"><p>Belum ada data pegawai.</p></div>
                    @endforelse
                </div>

                <div style="margin-top: 20px;">
                    {{ $users->appends(['tab' => 'pegawai', 'unit_page' => request('unit_page'), 'lokasi_page' => request('lokasi_page'), 'jenis_page' => request('jenis_page')])->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Lokasi Kegiatan -->
    <div class="tab-content" :class="{ 'active': tab === 'lokasi' }" x-show="tab === 'lokasi'">
        <div class="split-container">
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon"><i class="bi bi-plus-lg"></i></span> Tambah Lokasi Kegiatan</h3>
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
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon"><i class="bi bi-geo-alt-fill"></i></span> Daftar Lokasi</h3>
                <div class="master-table-wrap" style="overflow-x: auto;">
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
                                    <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('lokasi', {{ $l->id }}, { nama_lokasi: '{{ addslashes($l->nama_lokasi) }}', alamat: '{{ addslashes($l->alamat) }}' })"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('master.lokasi.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View: Lokasi --}}
                <div class="master-cards-mobile">
                    @forelse($lokasi as $l)
                    <div class="task-card">
                        <div class="task-card-title">{{ $l->nama_lokasi }}</div>
                        <div class="task-card-subtitle">ID: {{ $l->id }}</div>
                        <div style="font-size: 12px; color: var(--text-600);"><i class="bi bi-geo-alt"></i> {{ $l->alamat ?? 'Tidak ada alamat' }}</div>
                        <div class="task-card-actions">
                            <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('lokasi', {{ $l->id }}, { nama_lokasi: '{{ addslashes($l->nama_lokasi) }}', alamat: '{{ addslashes($l->alamat) }}' })"><i class="bi bi-pencil"></i> Edit</button>
                            <form action="{{ route('master.lokasi.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state"><p>Belum ada data lokasi.</p></div>
                    @endforelse
                </div>

                <div style="margin-top: 20px;">
                    {{ $lokasi->appends(['tab' => 'lokasi', 'user_page' => request('user_page'), 'unit_page' => request('unit_page'), 'jenis_page' => request('jenis_page')])->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Tab: Jenis Kegiatan -->
    <div class="tab-content" :class="{ 'active': tab === 'jenis' }" x-show="tab === 'jenis'">
        <div class="split-container">
            <div class="section-box">
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon"><i class="bi bi-plus-lg"></i></span> Tambah Jenis Kegiatan</h3>
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
                <h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon"><i class="bi bi-tag"></i></span> Daftar Jenis Kegiatan</h3>
                <div class="master-table-wrap" style="overflow-x: auto;">
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
                                    <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('jenis', {{ $j->id }}, { nama_jenis: '{{ addslashes($j->nama_jenis) }}', keterangan: '{{ addslashes($j->keterangan) }}' })"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('master.jenis.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jenis kegiatan ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View: Jenis Kegiatan --}}
                <div class="master-cards-mobile">
                    @forelse($jenis as $j)
                    <div class="task-card">
                        <div class="task-card-title">{{ $j->nama_jenis }}</div>
                        <div class="task-card-subtitle">ID: {{ $j->id }}</div>
                        <div style="font-size: 12px; color: var(--text-600);"><i class="bi bi-info-circle"></i> {{ $j->keterangan ?? 'Tidak ada keterangan' }}</div>
                        <div class="task-card-actions">
                            <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal('jenis', {{ $j->id }}, { nama_jenis: '{{ addslashes($j->nama_jenis) }}', keterangan: '{{ addslashes($j->keterangan) }}' })"><i class="bi bi-pencil"></i> Edit</button>
                            <form action="{{ route('master.jenis.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jenis kegiatan ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state"><p>Belum ada data jenis kegiatan.</p></div>
                    @endforelse
                </div>

                <div style="margin-top: 20px;">
                    {{ $jenis->appends(['tab' => 'jenis', 'user_page' => request('user_page'), 'unit_page' => request('unit_page'), 'lokasi_page' => request('lokasi_page')])->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- ============================
         EDIT MODAL (ALPINE JS)
    ============================ -->
    <div class="modal-overlay" :class="{ 'show': editModalOpen }" x-show="editModalOpen" style="display: none;" x-transition>
        <div class="modal-box" @click.away="editModalOpen = false">
            <div class="modal-header">
                <h3><i class="bi bi-pencil"></i> Edit Data</h3>
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
                    <label>Induk Unit Kerja (Opsional)</label>
                    <select name="parent_id" x-model="editData.parent_id">
                        <option value="">— Tidak Ada Induk (Level Tertinggi) —</option>
                        @foreach($allUnits as $u)
                            <option value="{{ $u->id }}">{{ $u->nama_unit }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kepala Unit</label>
                    <select name="kepala_unit_id" x-model="editData.kepala_unit_id">
                        <option value="">— Pilih Pimpinan (Opsional) —</option>
                        @foreach($allUsers->where('role.nama_role', 'Pimpinan') as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" @click="editModalOpen = false">Batal</button>
                    <button type="submit" class="btn"><i class="bi bi-floppy"></i> Simpan Perubahan</button>
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
                    <label>Email (Untuk Google Login)</label>
                    <input type="email" name="email" x-model="editData.email" placeholder="Contoh: pegawai@alazhar.org">
                </div>
                <div class="form-group">
                    <label>Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="***">
                </div>
                <div class="form-group">
                    <label>Unit Kerja</label>
                    <select name="unit_id" x-model="editData.unit_id" required>
                        @foreach($allUnits as $u)
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
                    <button type="submit" class="btn"><i class="bi bi-floppy"></i> Simpan Perubahan</button>
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
                    <button type="submit" class="btn"><i class="bi bi-floppy"></i> Simpan Perubahan</button>
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
                    <button type="submit" class="btn"><i class="bi bi-floppy"></i> Simpan Perubahan</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection