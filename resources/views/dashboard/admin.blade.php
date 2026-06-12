@extends('layouts.app')

@section('page_title')
    <h2>Manajemen Penjadwalan Kegiatan</h2>
    <p>Buat dan kelola jadwal kegiatan organisasi secara terpusat</p>
@endsection

@push('styles')
<style>
    .admin-cards-mobile {
        display: none;
        flex-direction: column;
        gap: 12px;
    }
    .admin-cards-mobile .task-card {
        background: var(--bg-white);
        border: 1px solid var(--border-200);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: box-shadow var(--transition-base);
    }
    .admin-cards-mobile .task-card:hover {
        box-shadow: var(--shadow-md);
    }

    @media (max-width: 768px) {
        .admin-table-wrap {
            display: none !important;
        }
        .admin-cards-mobile {
            display: flex !important;
        }
        .desktop-pagination {
            display: none;
        }
        /* Form grid responsive */
        .form-grid-admin {
            grid-template-columns: 1fr !important;
        }
        /* Datetime inputs stack */
        .datetime-row {
            grid-template-columns: 1fr !important;
        }
        /* Checkbox list touch-friendly */
        .checkbox-list label {
            min-height: 44px;
            display: flex;
            align-items: center;
            padding: 8px 0;
        }
        /* Modal grid responsive */
        .modal-box .modal-grid-2col {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;" x-data="{ 
            formOpen: false,
            allUsers: {{ json_encode($allUsers) }},
            createUnitId: '',
            editModalOpen: false, 
            editId: '', 
            editData: { user_ids: [], unit_id: '' },
            openEditModal(id, data, users) {
                this.editId = id;
                this.editData = data;
                this.editData.user_ids = users;
                this.editModalOpen = true;
            },
            get filteredCreateUsers() {
                if (!this.createUnitId) return [];
                return this.allUsers.filter(u => u.unit_id == this.createUnitId);
            },
            get filteredEditUsers() {
                if (!this.editData.unit_id) return [];
                return this.allUsers.filter(u => u.unit_id == this.editData.unit_id);
            }
        }">
    <div class="section-box">
        <div style="display:flex; justify-content:space-between; align-items:center; cursor:pointer;" @click="formOpen = !formOpen">
            <h3 class="section-title" style="margin: 0;"><span class="title-icon"><i class="bi bi-plus-lg"></i></span> Buat Kegiatan Baru</h3>
            <button type="button" class="btn btn-sm btn-secondary" x-text="formOpen ? 'Tutup Form ▴' : 'Buat Kegiatan ▾'"></button>
        </div>
        <form action="{{ route('kegiatan.store') }}" method="POST" x-show="formOpen" x-transition style="margin-top: 20px; display: none;">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <div class="form-group">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="{{ $errors->has('nama_kegiatan') ? 'is-invalid' : '' }}" required>
                        @error('nama_kegiatan') <small class="text-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group">
                        <label>Jenis Kegiatan</label>
                        <select name="jenis_id" required>
                            @foreach($jenis_kegiatans as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Unit Pelaksana</label>
                        <select name="unit_id" required x-model="createUnitId">
                            <option value="" disabled selected>Pilih Unit Pelaksana</option>
                            @foreach($unit_kerjas as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lokasi</label>
                        <select name="lokasi_id" required>
                            @foreach($lokasi_kegiatans as $lokasi)
                                <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label>Waktu Mulai & Selesai</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <input type="datetime-local" name="waktu_mulai" required>
                            <input type="datetime-local" name="waktu_selesai" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Pegawai & Pimpinan Terlibat</label>
                        <div class="checkbox-list" style="height: 180px; max-height: 180px;">
                            <template x-for="u in filteredCreateUsers" :key="u.id">
                                <label>
                                    <input type="checkbox" name="user_ids[]" :value="u.id">
                                    <span x-text="u.nama + ' (' + u.role + ')'"></span>
                                </label>
                            </template>
                            <template x-if="filteredCreateUsers.length === 0">
                                <em style="color:var(--text-400); font-size:12px;">Pilih unit pelaksana terlebih dahulu</em>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn" style="width:100%; margin-top: 10px;"><i class="bi bi-calendar-event"></i> Publikasikan Jadwal</button>
        </form>
    </div>

    <div class="section-box">
        <h3 class="section-title"><span class="title-icon"><i class="bi bi-card-checklist"></i></span> Database Jadwal Kegiatan</h3>
        
        {{-- ======= DESKTOP TABLE VIEW ======= --}}
        <div class="admin-table-wrap" style="overflow-x: auto; width: 100%;">
            <table style="min-width: 800px;">
            <thead>
                <tr>
                    <th>Detail Kegiatan</th>
                    <th>Waktu Pelaksanaan</th>
                    <th>Peserta Terlibat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatans as $keg)
                <tr>
                    <td>
                        <div style="font-weight:700; color:var(--text-900); font-size:13.5px; margin-bottom:4px;">{{ $keg->nama_kegiatan }}</div>
                        <div style="font-size:11.5px; color:var(--primary-500); font-weight:600; margin-bottom:2px;"><i class="bi bi-building"></i> {{ $keg->unitKerja->nama_unit ?? '-' }}</div>
                        <div style="font-size:11.5px; color:var(--text-500);"><i class="bi bi-geo-alt-fill"></i> {{ $keg->lokasi->nama_lokasi ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-size:12.5px; font-weight:600; color:var(--text-700);">Mulai: {{ $keg->waktu_mulai->format('d M Y, H:i') }}</div>
                        <div style="font-size:12px; color:var(--text-500); margin-top:3px;">Selesai: {{ $keg->waktu_selesai->format('d M Y, H:i') }}</div>
                    </td>
                    <td>
                        <div style="max-width: 200px; white-space: normal; font-size: 11px;">
                            @if($keg->peserta->count() > 0)
                                {{ $keg->peserta->pluck('nama')->join(', ') }}
                            @else
                                <em style="color:var(--text-400);">Belum ada peserta</em>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $keg->status == 'Selesai' ? 'bg-selesai' : ($keg->status == 'Berlangsung' ? 'bg-proses' : 'bg-belum') }}">
                            {{ $keg->status }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:5px; align-items: center; white-space: nowrap;">
                            <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $keg->id }}, {{ \Illuminate\Support\Js::from(['nama_kegiatan' => $keg->nama_kegiatan, 'jenis_id' => $keg->jenis_id, 'unit_id' => $keg->unit_id, 'lokasi_id' => $keg->lokasi_id, 'waktu_mulai' => $keg->waktu_mulai->format('Y-m-d\TH:i'), 'waktu_selesai' => $keg->waktu_selesai->format('Y-m-d\TH:i')]) }}, {{ json_encode($keg->peserta->pluck('id')->toArray()) }})"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('kegiatan.destroy', $keg->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');" style="margin: 0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        {{-- ======= MOBILE CARD VIEW ======= --}}
        <div class="admin-cards-mobile">
            @forelse($kegiatans as $keg)
            <div class="task-card" style="margin-bottom: 12px;">
                <div class="task-card-header" style="padding: 16px 16px 12px; border-bottom: 1px solid var(--border-100);">
                    <div style="font-weight:700; color:var(--text-900); font-size:14px; margin-bottom:6px; line-height:1.4;">{{ $keg->nama_kegiatan }}</div>
                    <div style="font-size:12px; color:var(--primary-500); font-weight:600; margin-bottom:4px;"><i class="bi bi-building"></i> {{ $keg->unitKerja->nama_unit ?? '-' }}</div>
                    <div style="font-size:12px; color:var(--text-500); margin-bottom:8px;"><i class="bi bi-geo-alt-fill"></i> {{ $keg->lokasi->nama_lokasi ?? '-' }}</div>
                    <span class="badge {{ $keg->status == 'Selesai' ? 'bg-selesai' : ($keg->status == 'Berlangsung' ? 'bg-proses' : 'bg-belum') }}">{{ $keg->status }}</span>
                </div>
                <div style="padding: 12px 16px;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
                        <div>
                            <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-400); font-weight:700;">Mulai</div>
                            <div style="font-size:13px; font-weight:600; color:var(--text-700);">{{ $keg->waktu_mulai->format('d M Y') }}</div>
                            <div style="font-size:11px; color:var(--text-500);">{{ $keg->waktu_mulai->format('H:i') }} WIB</div>
                        </div>
                        <div>
                            <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-400); font-weight:700;">Selesai</div>
                            <div style="font-size:13px; font-weight:600; color:var(--text-700);">{{ $keg->waktu_selesai->format('d M Y') }}</div>
                            <div style="font-size:11px; color:var(--text-500);">{{ $keg->waktu_selesai->format('H:i') }} WIB</div>
                        </div>
                    </div>
                    @if($keg->peserta->count() > 0)
                        <div style="font-size:11px; color:var(--text-600); line-height:1.5;">
                            <strong style="font-size:10px; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-400); display:block; margin-bottom:4px;">Peserta</strong>
                            {{ $keg->peserta->pluck('nama')->join(', ') }}
                        </div>
                    @endif
                </div>
                <div style="display:flex; justify-content:flex-end; gap:8px; padding:10px 16px; border-top:1px solid var(--border-100); background:var(--bg-app);">
                    <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $keg->id }}, {{ \Illuminate\Support\Js::from(['nama_kegiatan' => $keg->nama_kegiatan, 'jenis_id' => $keg->jenis_id, 'unit_id' => $keg->unit_id, 'lokasi_id' => $keg->lokasi_id, 'waktu_mulai' => $keg->waktu_mulai->format('Y-m-d\TH:i'), 'waktu_selesai' => $keg->waktu_selesai->format('Y-m-d\TH:i')]) }}, {{ json_encode($keg->peserta->pluck('id')->toArray()) }})"><i class="bi bi-pencil"></i> Edit</button>
                    <form action="{{ route('kegiatan.destroy', $keg->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');" style="margin: 0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-calendar-event"></i></div>
                <p>Belum ada kegiatan yang dijadwalkan.</p>
            </div>
            @endforelse
        </div>

        {{-- Desktop Pagination --}}
        <div class="desktop-pagination">
            {{ $kegiatans->links() }}
        </div>
        
        {{-- Mobile Pagination --}}
        <div class="pagination-mobile">
            <span class="page-info">Halaman {{ $kegiatans->currentPage() }} dari {{ $kegiatans->lastPage() }}</span>
            <div class="page-nav-buttons">
                @if($kegiatans->onFirstPage())
                    <span class="disabled"><i class="bi bi-chevron-left"></i> Sebelumnya</span>
                @else
                    <a href="{{ $kegiatans->previousPageUrl() }}"><i class="bi bi-chevron-left"></i> Sebelumnya</a>
                @endif
                @if($kegiatans->hasMorePages())
                    <a href="{{ $kegiatans->nextPageUrl() }}">Selanjutnya <i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="disabled">Selanjutnya <i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </div>
    <!-- ============================
         EDIT MODAL (ALPINE JS)
    ============================ -->
    <div class="modal-overlay" :class="{ 'show': editModalOpen }" x-show="editModalOpen" style="display: none;" x-transition>
        <div class="modal-box" @click.away="editModalOpen = false">
            <div class="modal-header">
                <h3><i class="bi bi-pencil"></i> Edit Jadwal Kegiatan</h3>
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
                            @foreach($jenis_kegiatans as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Unit Pelaksana</label>
                        <select name="unit_id" x-model="editData.unit_id" required>
                            @foreach($unit_kerjas as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Lokasi</label>
                    <select name="lokasi_id" x-model="editData.lokasi_id" required>
                        @foreach($lokasi_kegiatans as $lokasi)
                            <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Pegawai & Pimpinan Terlibat</label>
                    <div class="checkbox-list" style="max-height: 100px;">
                        <template x-for="u in filteredEditUsers" :key="u.id">
                            <label>
                                <input type="checkbox" name="user_ids[]" :value="u.id" :checked="editData.user_ids && editData.user_ids.includes(u.id)">
                                <span x-text="u.nama + ' (' + u.role + ')'"></span>
                            </label>
                        </template>
                        <template x-if="filteredEditUsers.length === 0">
                            <em style="color:var(--text-400); font-size:12px;">Pilih unit pelaksana terlebih dahulu</em>
                        </template>
                    </div>
                </div>
                <div class="form-group">
                    <label>Waktu Mulai & Selesai</label>
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <input type="datetime-local" name="waktu_mulai" x-model="editData.waktu_mulai" required>
                        <input type="datetime-local" name="waktu_selesai" x-model="editData.waktu_selesai" required>
                    </div>
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