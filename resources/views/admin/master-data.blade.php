@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Pengelolaan Master Data</h2>
        <p>Kelola data lokasi kegiatan dan jenis kegiatan</p>
    </div>
</div>

<div class="alert alert-info" style="margin-bottom: 24px; padding: 16px; background-color: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 4px; display: flex; gap: 12px; align-items: flex-start;">
    <i class="bi bi-info-circle-fill" style="color: #3b82f6; font-size: 1.25rem;"></i>
    <div>
        <h4 style="margin: 0 0 4px 0; color: #1e3a8a; font-size: 14px; font-weight: 700;">Informasi Sistem</h4>
        <p style="margin: 0; color: #1e3a8a; font-size: 13px;">Data <strong>Pegawai</strong> dan <strong>Unit Kerja</strong> kini dikelola secara terpusat melalui SSO <strong>PresensiGPS</strong>. Anda tidak perlu lagi menambah atau mengubah data pegawai di sini.</p>
    </div>
</div>

<div x-data="{ 
    tab: '{{ $activeTab }}',
    mobileTabOpen: false,
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

        .desktop-tabs {
            display: flex;
        }
        .mobile-tabs-dropdown {
            display: none;
            position: relative;
            margin-bottom: 24px;
        }
        .mobile-tab-toggle {
            width: 100%;
            background: var(--bg-white);
            border: 1px solid var(--border-200);
            padding: 14px 16px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            color: var(--text-900);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
        }
        .mobile-tab-toggle i {
            transition: transform 0.3s;
        }
        .mobile-tab-toggle i.rotated {
            transform: rotate(180deg);
        }
        .mobile-tab-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 8px;
            background: var(--bg-white);
            border: 1px solid var(--border-200);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            z-index: 50;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .mobile-tab-btn {
            padding: 14px 16px;
            text-align: left;
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--border-100);
            font-size: 14px;
            font-weight: 500;
            color: var(--text-700);
            width: 100%;
        }
        .mobile-tab-btn:last-child {
            border-bottom: none;
        }
        .mobile-tab-btn.active {
            color: var(--primary-600);
            font-weight: 700;
            background: var(--primary-50);
        }

        @media (max-width: 768px) {
            .desktop-tabs {
                display: none !important;
            }
            .mobile-tabs-dropdown {
                display: block;
            }
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
    <div class="tabs-bar desktop-tabs">
        <button class="tab-btn" :class="{ 'active': tab === 'lokasi' }" @click="tab = 'lokasi'">Lokasi Kegiatan</button>
        <button class="tab-btn" :class="{ 'active': tab === 'jenis' }" @click="tab = 'jenis'">Jenis Kegiatan</button>
    </div>

    <!-- Mobile Tabs Dropdown -->
    <div class="mobile-tabs-dropdown" @click.away="mobileTabOpen = false">
        <button type="button" class="mobile-tab-toggle" @click="mobileTabOpen = !mobileTabOpen">
            <span x-text="
                tab === 'lokasi' ? 'Lokasi Kegiatan' : 'Jenis Kegiatan'
            "></span>
            <i class="bi bi-chevron-down" :class="{ 'rotated': mobileTabOpen }"></i>
        </button>
        <div class="mobile-tab-menu" x-show="mobileTabOpen" x-transition.opacity style="display: none;">
            <button class="mobile-tab-btn" :class="{ 'active': tab === 'lokasi' }" @click="tab = 'lokasi'; mobileTabOpen = false">Lokasi Kegiatan</button>
            <button class="mobile-tab-btn" :class="{ 'active': tab === 'jenis' }" @click="tab = 'jenis'; mobileTabOpen = false">Jenis Kegiatan</button>
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