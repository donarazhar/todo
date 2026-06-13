@extends('layouts.app')

@section('page_title')
    <h2>Todo Mandiri Pegawai</h2>
    <p>Pantau daftar tugas mandiri yang dibuat dan dikerjakan sendiri oleh pegawai di unit Anda</p>
@endsection

@push('styles')
<style>
    /* ============================
       MANDIRI PAGE – RESPONSIVE STYLES
    ============================ */

    .mandiri-subtitle {
        font-size: 12px;
        color: var(--text-500);
        margin: 0 0 16px 0;
        line-height: 1.5;
    }

    /* --- Priority Badges --- */
    .prio-badge {
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10.5px;
        font-weight: 700;
        display: inline-block;
    }
    .prio-tinggi { background: #FEE2E2; color: #991B1B; }
    .prio-sedang { background: #FEF3C7; color: #92400E; }
    .prio-rendah { background: #D1FAE5; color: #065F46; }

    /* --- Desktop Table (hidden on mobile) --- */
    .mandiri-table-wrap {
        overflow-x: auto;
        width: 100%;
    }
    .mandiri-table-wrap table {
        min-width: 700px;
    }

    /* --- Mobile Card Layout (hidden on desktop) --- */
    .mandiri-cards-mobile {
        display: none;
        flex-direction: column;
        gap: 14px;
    }
    .mandiri-card {
        background: var(--bg-white);
        border: 1px solid var(--border-200);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: box-shadow var(--transition-base);
    }
    .mandiri-card:hover {
        box-shadow: var(--shadow-md);
    }

    /* Card Header */
    .mandiri-card-header {
        padding: 14px 16px 12px;
        border-bottom: 1px solid var(--border-100);
    }
    .mandiri-card-title {
        font-weight: 700;
        color: var(--text-900);
        font-size: 14px;
        margin-bottom: 6px;
        line-height: 1.4;
    }
    .mandiri-card-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--primary-500);
        font-weight: 600;
        margin-bottom: 6px;
    }
    .mandiri-card-desc {
        font-size: 12px;
        color: var(--text-500);
        line-height: 1.5;
        margin-bottom: 8px;
    }
    .mandiri-card-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }

    /* Card Body */
    .mandiri-card-body {
        padding: 12px 16px;
    }
    .mandiri-card-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }
    .mandiri-card-info-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .mandiri-card-info-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-400);
        font-weight: 700;
    }
    .mandiri-card-info-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-700);
    }
    .mandiri-card-info-value.overdue {
        color: #E53E3E;
    }

    /* Laporan section */
    .mandiri-card-report {
        background: var(--bg-app);
        border-radius: var(--radius-md);
        padding: 10px 12px;
        border: 1px solid var(--border-100);
    }
    .mandiri-card-report-text {
        color: var(--text-500);
        font-weight: normal;
        font-size: 12px;
        line-height: 1.5;
    }
    .mandiri-card-report-link {
        font-size: 11.5px;
        color: var(--primary-600);
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
    }
    .mandiri-card-report-link:hover {
        text-decoration: underline;
    }
    .mandiri-card-no-report {
        font-size: 12px;
        color: var(--text-400);
        font-style: italic;
    }

    /* Card Footer */
    .mandiri-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 16px;
        border-top: 1px solid var(--border-100);
        background: var(--bg-app);
        font-size: 11px;
    }

    /* ============================
       MOBILE BREAKPOINT (≤ 768px)
    ============================ */
    @media (max-width: 768px) {
        .mandiri-table-wrap {
            display: none !important;
        }
        .mandiri-cards-mobile {
            display: flex !important;
        }
        .desktop-pagination {
            display: none;
        }
    }

    /* ============================
       SMALL MOBILE (≤ 480px)
    ============================ */
    @media (max-width: 480px) {
        .mandiri-card-info-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }
    }
</style>
@endpush

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;" x-data="{
    viewModalOpen: false, 
    viewData: {},
    openViewModal(data) {
        this.viewData = data;
        this.viewModalOpen = true;
    },
    imageModalOpen: false,
    imageModalSrc: '',
    openImageModal(src) {
        this.imageModalSrc = src;
        this.imageModalOpen = true;
    }
}">
    <div class="section-box">
        <h3 class="section-title" style="margin-bottom: 6px;"><span class="title-icon"><i class="bi bi-bullseye"></i></span> Tugas Mandiri Pegawai</h3>
        <p class="mandiri-subtitle">Daftar tugas mandiri yang dibuat dan dikerjakan sendiri oleh pegawai di unit Anda.</p>

        <form method="GET" action="{{ route('pimpinan.mandiri') }}" style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul/deskripsi..." style="flex-grow:1; padding:8px 12px; border:1px solid var(--border-300); border-radius:var(--radius-sm); outline:none;">
            <select name="filter_unit" style="padding:8px 12px; border:1px solid var(--border-300); border-radius:var(--radius-sm); outline:none;">
                <option value="">Semua Unit Kerja</option>
                @foreach($subUnits as $unit)
                    <option value="{{ $unit->id }}" {{ request('filter_unit') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
                @endforeach
            </select>
            <select name="status" style="padding:8px 12px; border:1px solid var(--border-300); border-radius:var(--radius-sm); outline:none;">
                <option value="">Semua Status</option>
                <option value="Berlangsung" {{ request('status') == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <select name="prioritas" style="padding:8px 12px; border:1px solid var(--border-300); border-radius:var(--radius-sm); outline:none;">
                <option value="">Semua Prioritas</option>
                <option value="Tinggi" {{ request('prioritas') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                <option value="Sedang" {{ request('prioritas') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                <option value="Rendah" {{ request('prioritas') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
            </select>
            <button type="submit" class="btn btn-primary" style="padding:8px 16px;"><i class="bi bi-search"></i> Cari</button>
            @if(request('search') || request('status') || request('prioritas') || request('filter_unit'))
                <a href="{{ route('pimpinan.mandiri') }}" class="btn btn-secondary" style="padding:8px 16px; text-decoration:none;"><i class="bi bi-x-lg"></i> Reset</a>
            @endif
            <a href="{{ route('tasks.export', array_merge(request()->all(), ['tab' => 'bawahan_mandiri'])) }}" target="_blank" class="btn btn-success" style="padding:8px 16px; text-decoration:none; margin-left:auto;"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
        </form>

        {{-- ======= DESKTOP TABLE VIEW ======= --}}
        <div class="mandiri-table-wrap">
            <table>
                <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Detail Tugas</th>
                    <th>Waktu Pelaksanaan</th>
                    <th>Status & Laporan</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mandiriTasks as $t)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: var(--text-500);">{{ $mandiriTasks->firstItem() + $loop->index }}</td>
                    <td>
                        <div style="font-weight:700; color:var(--text-900); font-size:13.5px; margin-bottom:4px;">{{ $t->judul }}</div>
                        <div style="font-size:11.5px; color:var(--primary-500); font-weight:600; margin-bottom:2px;">
                            <i class="bi bi-person"></i> {{ $t->assignee->nama ?? '-' }} 
                            <span style="font-size:10px; color:var(--text-400); font-weight:500;">({{ $t->assignee->unitKerja->nama_unit ?? '-' }})</span>
                            &nbsp;&bull;&nbsp; Bobot: {{ $t->bobot }}
                        </div>
                        <div style="font-size:11.5px; color:var(--text-500); margin-bottom: 6px;">{{ \Illuminate\Support\Str::limit($t->deskripsi, 60) }}</div>
                        <div>
                            @php
                                $prioClass = $t->prioritas === 'Tinggi' ? 'prio-tinggi' : ($t->prioritas === 'Rendah' ? 'prio-rendah' : 'prio-sedang');
                            @endphp
                            <span class="prio-badge {{ $prioClass }}">Prio: {{ $t->prioritas }}</span>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:12.5px; font-weight:600; color:var(--text-700);">Mulai: {{ $t->tgl_mulai->format('d M Y') }}</div>
                        <div style="font-size:12px; color:var(--text-500); margin-top:3px;">Deadline: {{ $t->tgl_selesai->format('d M Y') }}</div>
                        @if($t->is_overdue)
                            <div style="color:#E53E3E; font-size:11px; font-weight:700; margin-top:3px;"><i class="bi bi-exclamation-triangle"></i> Terlambat</div>
                        @endif
                    </td>
                    <td>
                        <div style="margin-bottom:6px;">
                            <span class="badge {{ $t->status === 'Selesai' ? 'bg-selesai' : 'bg-proses' }}">
                                {{ $t->status }}
                            </span>
                        </div>
                        <div>
                            @if($t->laporan)
                                <div style="color:var(--text-500); font-weight:normal; font-size:11px; max-width:200px; white-space:normal;">
                                    <i class="bi bi-check2"></i> {{ $t->laporan }}
                                    @if($t->file_laporan)
                                        @php
                                            $ext = strtolower(pathinfo($t->file_laporan, PATHINFO_EXTENSION));
                                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        @endphp
                                        <br>
                                        @if($isImage)
                                            <a href="#" @click.prevent="openImageModal('{{ asset('storage/' . $t->file_laporan) }}')" style="font-size:11px; color:var(--primary-600); text-decoration:none;"><i class="bi bi-image"></i> Lihat Gambar</a>
                                        @else
                                            <a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" style="font-size:11px; color:var(--primary-600); text-decoration:none;"><i class="bi bi-file-earmark-text"></i> Lihat Lampiran</a>
                                        @endif
                                    @endif
                                </div>
                            @else
                                <div style="font-size:11px; color:var(--text-400); font-style:italic;">Belum ada laporan</div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-secondary" style="width: 100%; justify-content: center;" @click="openViewModal({{ \Illuminate\Support\Js::from(['judul' => $t->judul, 'deskripsi' => $t->deskripsi, 'prioritas' => $t->prioritas, 'bobot' => $t->bobot, 'tgl_mulai' => $t->tgl_mulai->format('Y-m-d'), 'tgl_selesai' => $t->tgl_selesai->format('Y-m-d'), 'assignee_nama' => $t->assignee->nama ?? '-', 'assignee_unit' => $t->assignee->unitKerja->nama_unit ?? '-', 'status' => $t->status, 'laporan' => $t->laporan]) }})">
                            <i class="bi bi-eye"></i> View
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-bullseye"></i></div>
                            <p>Belum ada tugas mandiri dari pegawai.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            </table>
        </div>

        {{-- ======= MOBILE CARD VIEW ======= --}}
        <div class="mandiri-cards-mobile">
            @forelse($mandiriTasks as $t)
            <div class="mandiri-card">
                {{-- Card Header --}}
                <div class="mandiri-card-header">
                    <div class="mandiri-card-title">{{ $t->judul }}</div>
                    <div class="mandiri-card-meta">
                        <span><i class="bi bi-person"></i> {{ $t->assignee->nama ?? '-' }}</span>
                        <span>•</span>
                        <span>Bobot: {{ $t->bobot }}</span>
                    </div>
                    @if($t->deskripsi)
                        <div class="mandiri-card-desc">{{ \Illuminate\Support\Str::limit($t->deskripsi, 100) }}</div>
                    @endif
                    <div class="mandiri-card-badges">
                        @php
                            $prioClass = $t->prioritas === 'Tinggi' ? 'prio-tinggi' : ($t->prioritas === 'Rendah' ? 'prio-rendah' : 'prio-sedang');
                        @endphp
                        <span class="prio-badge {{ $prioClass }}">{{ $t->prioritas }}</span>
                        <span class="badge {{ $t->status === 'Selesai' ? 'bg-selesai' : 'bg-proses' }}">
                            {{ $t->status }}
                        </span>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="mandiri-card-body">
                    <div class="mandiri-card-info-grid">
                        <div class="mandiri-card-info-item">
                            <span class="mandiri-card-info-label">Mulai</span>
                            <span class="mandiri-card-info-value">{{ $t->tgl_mulai->format('d M Y') }}</span>
                        </div>
                        <div class="mandiri-card-info-item">
                            <span class="mandiri-card-info-label">Deadline</span>
                            <span class="mandiri-card-info-value {{ $t->is_overdue ? 'overdue' : '' }}">
                                {{ $t->tgl_selesai->format('d M Y') }}
                                @if($t->is_overdue) <i class="bi bi-exclamation-triangle"></i> @endif
                            </span>
                        </div>
                    </div>

                    {{-- Laporan --}}
                    @if($t->laporan)
                        <div class="mandiri-card-report">
                            <div class="mandiri-card-report-text">
                                <i class="bi bi-check2"></i> {{ $t->laporan }}
                            </div>
                            @if($t->file_laporan)
                                @php
                                    $ext2 = strtolower(pathinfo($t->file_laporan, PATHINFO_EXTENSION));
                                    $isImage2 = in_array($ext2, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                @if($isImage2)
                                    <a href="#" @click.prevent="openImageModal('{{ asset('storage/' . $t->file_laporan) }}')" class="mandiri-card-report-link">
                                        <i class="bi bi-image"></i> Lihat Gambar
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" class="mandiri-card-report-link">
                                        <i class="bi bi-file-earmark-text"></i> Lihat Lampiran
                                    </a>
                                @endif
                            @endif
                        </div>
                    @else
                        <div class="mandiri-card-no-report">Belum ada laporan dari pegawai</div>
                    @endif
                </div>

                {{-- Card Footer --}}
                <div class="mandiri-card-footer">
                    <div>
                        @if($t->is_overdue)
                            <span style="color:#E53E3E; font-weight:700;"><i class="bi bi-exclamation-triangle"></i> Terlambat</span>
                        @else
                            <span style="color:var(--text-400);">Tugas Mandiri</span>
                        @endif
                        <span style="color:var(--text-400); margin-left: 8px;">{{ $t->tgl_mulai->format('d/m/Y') }}</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" style="padding: 4px 10px; font-size: 11px;" @click="openViewModal({{ \Illuminate\Support\Js::from(['judul' => $t->judul, 'deskripsi' => $t->deskripsi, 'prioritas' => $t->prioritas, 'bobot' => $t->bobot, 'tgl_mulai' => $t->tgl_mulai->format('Y-m-d'), 'tgl_selesai' => $t->tgl_selesai->format('Y-m-d'), 'assignee_nama' => $t->assignee->nama ?? '-', 'assignee_unit' => $t->assignee->unitKerja->nama_unit ?? '-', 'status' => $t->status, 'laporan' => $t->laporan]) }})">
                        <i class="bi bi-eye"></i> View
                    </button>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-bullseye"></i></div>
                <p>Belum ada tugas mandiri dari pegawai di unit Anda.</p>
            </div>
            @endforelse
        </div>

        {{-- Desktop Pagination --}}
        <div class="desktop-pagination" style="margin-top: 16px;">
            {{ $mandiriTasks->links() }}
        </div>
        
        {{-- Mobile Pagination --}}
        <div class="pagination-mobile">
            <span class="page-info">Page {{ $mandiriTasks->currentPage() }} / {{ $mandiriTasks->lastPage() }}</span>
            <div class="page-nav-buttons">
                @if($mandiriTasks->onFirstPage())
                    <span class="disabled"><i class="bi bi-chevron-left"></i> Prev</span>
                @else
                    <a href="{{ $mandiriTasks->previousPageUrl() }}"><i class="bi bi-chevron-left"></i> Prev</a>
                @endif
                @if($mandiriTasks->hasMorePages())
                    <a href="{{ $mandiriTasks->nextPageUrl() }}">Next <i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="disabled">Next <i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
        </div>
    </div>
    
    {{-- ================================
         VIEW MODAL (ALPINE JS)
    ================================= --}}
    <div class="modal-overlay" :class="{ 'show': viewModalOpen }" x-show="viewModalOpen" style="display: none;" x-transition>
        <div class="modal-box" @click.away="viewModalOpen = false" style="padding: 0; overflow: hidden; max-width: 550px;">
            <div class="modal-header" style="padding: 20px 24px 16px; margin-bottom: 0; border-bottom: 1px solid var(--border-100); background: var(--bg-white);">
                <h3 style="margin: 0; font-size: 16px;"><i class="bi bi-file-text"></i> Laporan Tugas Mandiri</h3>
                <button type="button" class="modal-close" @click="viewModalOpen = false">×</button>
            </div>
            <div style="padding: 24px; background: #fafafa; overflow-y: auto; max-height: calc(90vh - 70px);">
                
                <div style="background: var(--bg-white); border: 1px solid var(--border-200); border-radius: var(--radius-md); padding: 20px; margin-bottom: 20px;">
                    <h2 style="font-size: 18px; font-weight: 700; color: var(--text-900); margin-top: 0; margin-bottom: 8px; line-height: 1.4;" x-text="viewData.judul"></h2>
                    
                    <div style="display: flex; gap: 12px; font-size: 12px; color: var(--text-500); margin-bottom: 16px; align-items: center; flex-wrap: wrap;">
                        <span><i class="bi bi-person-badge"></i> <span x-text="viewData.assignee_nama + ' (' + viewData.assignee_unit + ')'"></span></span>
                        <span>&bull;</span>
                        <span :class="{'prio-badge prio-tinggi': viewData.prioritas === 'Tinggi', 'prio-badge prio-sedang': viewData.prioritas === 'Sedang', 'prio-badge prio-rendah': viewData.prioritas === 'Rendah'}" x-text="viewData.prioritas" style="padding: 2px 6px; font-size: 10px; border-radius: 4px;"></span>
                        <span>&bull;</span>
                        <span style="font-weight: 600; color: var(--text-700);">Bobot: <span x-text="viewData.bobot"></span></span>
                    </div>

                    <div style="font-size: 13.5px; color: var(--text-700); line-height: 1.6; white-space: pre-wrap; margin-bottom: 24px;" x-text="viewData.deskripsi || 'Tidak ada deskripsi tugas.'"></div>

                    <div style="background: var(--bg-app); border: 1px dashed var(--border-300); border-radius: var(--radius-sm); padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                        <div>
                            <span style="color: var(--text-500); display: block; margin-bottom: 2px;">Mulai</span>
                            <strong style="color: var(--text-800);" x-text="viewData.tgl_mulai"></strong>
                        </div>
                        <div style="text-align: right;">
                            <span style="color: var(--text-500); display: block; margin-bottom: 2px;">Deadline</span>
                            <strong style="color: var(--text-800);" x-text="viewData.tgl_selesai"></strong>
                        </div>
                    </div>
                </div>

                <div style="background: var(--bg-white); border: 1px solid var(--border-200); border-radius: var(--radius-md); padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <h4 style="font-size: 14px; font-weight: 700; color: var(--text-900); margin: 0;">Status & Laporan</h4>
                        <span :class="{'badge bg-selesai': viewData.status === 'Selesai', 'badge bg-proses': viewData.status !== 'Selesai'}" x-text="viewData.status"></span>
                    </div>
                    
                    <div style="font-size: 13px; color: var(--text-700); line-height: 1.5; white-space: pre-wrap; background: #f0fdf4; padding: 12px; border-radius: var(--radius-sm); border-left: 3px solid var(--teal-500);" x-show="viewData.laporan">
                        <span x-text="viewData.laporan"></span>
                    </div>
                    <div style="font-size: 13px; color: var(--text-400); font-style: italic;" x-show="!viewData.laporan">
                        Pegawai belum melampirkan laporan pekerjaan.
                    </div>
                </div>
                
                <div style="display:flex; justify-content:flex-end; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" @click="viewModalOpen = false">Tutup Laporan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================
         IMAGE MODAL (ALPINE JS)
    ============================ -->
    <div class="modal-overlay" :class="{ 'show': imageModalOpen }" x-show="imageModalOpen" style="display: none; background: rgba(0,0,0,0.8); z-index: 9999; padding: 20px; align-items: center !important;" x-transition>
        <div @click.away="imageModalOpen = false" style="position: relative; max-width: 100%; max-height: 100%; display: flex; justify-content: center; align-items: center;">
            <button type="button" @click="imageModalOpen = false" style="position: absolute; top: -40px; right: 0; background: none; border: none; color: white; font-size: 32px; cursor: pointer; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">&times;</button>
            <img :src="imageModalSrc" style="max-width: 100%; max-height: 85vh; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.5); object-fit: contain;">
        </div>
    </div>
</div>
@endsection
