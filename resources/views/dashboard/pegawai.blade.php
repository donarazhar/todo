@extends('layouts.app')

@section('page_title')
    <h2>My To-Do List & Laporan</h2>
    <p>Daftar tugas dari pimpinan dan tugas mandiri Anda &mdash; {{ Auth::user()->nama }} ({{ Auth::user()->unitKerja->nama_unit ?? '' }})</p>
@endsection

@push('styles')
<style>
    /* ============================
       PEGAWAI PAGE – RESPONSIVE STYLES
    ============================ */

    /* --- Wizard Styles --- */
    .wizard-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 24px;
        position: relative;
    }
    .wizard-header::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 10%;
        right: 10%;
        height: 2px;
        background: var(--border-200);
        z-index: 0;
    }
    .wizard-step-indicator {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex: 1;
    }
    .wizard-step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--bg-200);
        color: var(--text-500);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        border: 2px solid var(--bg-200);
        transition: all 0.3s ease;
    }
    .wizard-step-indicator.active .wizard-step-circle {
        background: var(--primary-600);
        color: white;
        border-color: var(--primary-600);
    }
    .wizard-step-indicator.completed .wizard-step-circle {
        background: var(--primary-500);
        color: white;
        border-color: var(--primary-500);
    }
    .wizard-step-title {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--text-500);
        text-align: center;
    }
    .wizard-step-indicator.active .wizard-step-title {
        color: var(--primary-600);
    }
    .wizard-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid var(--border-100);
    }
    .wizard-body {
        min-height: 200px;
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

    /* --- Form Section --- */
    .pgw-form-toggle {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        gap: 12px;
    }
    .pgw-form-toggle-info h3 {
        margin-bottom: 6px;
    }
    .pgw-form-toggle-info p {
        font-size: 12px;
        color: var(--text-500);
        margin: 0;
        line-height: 1.5;
    }
    .pgw-form-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
    }
    .pgw-form-left {
        flex: 7;
        min-width: 250px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .pgw-form-right {
        flex: 3;
        min-width: 200px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .pgw-form-submit {
        width: 100%;
    }

    /* --- Desktop Table (hidden on mobile) --- */
    .pgw-table-wrap {
        overflow-x: auto;
        width: 100%;
    }
    .pgw-table-wrap table {
        min-width: 750px;
    }

    /* --- Mobile Card Layout (hidden on desktop) --- */
    .pgw-cards-mobile {
        display: none;
        flex-direction: column;
        gap: 16px;
    }

    /* Card structure */
    .pgw-card {
        background: var(--bg-white);
        border: 1px solid var(--border-200);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: box-shadow var(--transition-base);
    }
    .pgw-card:hover {
        box-shadow: var(--shadow-md);
    }
    .pgw-card-header {
        padding: 14px 16px 12px;
        border-bottom: 1px solid var(--border-100);
    }
    .pgw-card-title {
        font-weight: 700;
        color: var(--text-900);
        font-size: 14px;
        margin-bottom: 6px;
        line-height: 1.4;
    }
    .pgw-card-desc {
        font-size: 12px;
        color: var(--text-500);
        line-height: 1.5;
        margin-bottom: 8px;
    }
    .pgw-card-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }

    /* Card Body */
    .pgw-card-body {
        padding: 12px 16px;
    }
    .pgw-card-info-grid {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        overflow-x: auto;
        padding-bottom: 4px;
    }
    .pgw-card-info-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
        white-space: nowrap;
    }
    .pgw-card-info-label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-400);
        font-weight: 700;
    }
    .pgw-card-info-value {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--text-700);
    }
    .pgw-card-info-value.overdue {
        color: #E53E3E;
    }

    /* Report Section in Card */
    .pgw-card-report-done {
        background: var(--bg-app);
        border-radius: var(--radius-md);
        padding: 10px 12px;
        margin-bottom: 12px;
        border: 1px solid var(--border-100);
    }
    .pgw-card-report-text {
        font-weight: 600;
        font-size: 12px;
        line-height: 1.5;
    }
    .pgw-card-report-text.done {
        color: var(--text-500);
    }
    .pgw-card-report-text.waiting {
        color: #1E40AF;
    }
    .pgw-card-report-text.revision {
        color: #E53E3E;
    }
    .pgw-card-report-link {
        font-size: 11.5px;
        color: var(--primary-600);
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
    }
    .pgw-card-report-link:hover {
        text-decoration: underline;
    }
    .pgw-card-report-sub {
        font-size: 11.5px;
        color: var(--text-500);
        margin-top: 2px;
    }

    /* Report form in card */
    .pgw-card-report-form {
        background: var(--bg-app);
        border: 1px dashed var(--border-300);
        border-radius: var(--radius-md);
        padding: 12px;
        margin-bottom: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .pgw-card-report-form input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        font-size: 13px;
        border: 1px solid var(--border-200);
        border-radius: var(--radius-sm);
        outline: none;
        font-family: inherit;
        transition: border-color var(--transition-fast);
    }
    .pgw-card-report-form input[type="text"]:focus {
        border-color: var(--primary-400);
    }
    .pgw-card-report-form-row {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .pgw-card-report-form-row input[type="file"] {
        font-size: 11px;
        flex-shrink: 0;
        max-width: 160px;
    }
    .pgw-card-report-form-row .btn {
        flex-grow: 1;
    }

    /* Notes section in card */
    .pgw-card-notes-toggle {
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        color: var(--primary-600);
        padding: 6px 0;
        user-select: none;
    }
    .pgw-card-notes-content {
        background: var(--bg-white);
        padding: 10px;
        border-radius: var(--radius-md);
        margin-top: 6px;
        font-size: 12px;
        border: 1px solid var(--border-200);
    }
    .pgw-note-item {
        margin-bottom: 8px;
        border-bottom: 1px solid var(--border-100);
        padding-bottom: 6px;
    }
    .pgw-note-item:last-of-type {
        margin-bottom: 0;
        border-bottom: none;
        padding-bottom: 0;
    }
    .pgw-note-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2px;
        align-items: center;
    }
    .pgw-note-author {
        font-weight: 700;
        color: var(--text-900);
        font-size: 11.5px;
    }
    .pgw-note-time {
        font-size: 9.5px;
        color: var(--text-400);
    }
    .pgw-note-text {
        color: var(--text-700);
        font-size: 12px;
        line-height: 1.4;
    }
    .pgw-note-form {
        display: flex;
        gap: 6px;
        margin-top: 8px;
    }
    .pgw-note-form input {
        flex-grow: 1;
        padding: 8px 10px;
        font-size: 12px;
        border: 1px solid var(--border-300);
        border-radius: var(--radius-sm);
        font-family: inherit;
        outline: none;
    }

    /* Card Footer / Actions */
    .pgw-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-top: 1px solid var(--border-100);
        background: var(--bg-app);
    }
    .pgw-card-actions {
        display: flex;
        gap: 8px;
    }
    .pgw-card-actions .btn {
        padding: 8px 14px;
        font-size: 12px;
    }

    /* --- Modal Responsive --- */
    .pgw-modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    /* ============================
       TABLET BREAKPOINT (≤ 1024px)
    ============================ */
    @media (max-width: 1024px) {
        .pgw-form-grid {
            flex-direction: column;
        }
        .pgw-form-left,
        .pgw-form-right {
            flex: none;
            min-width: 0;
        }
    }

    /* ============================
       MOBILE BREAKPOINT (≤ 768px)
    ============================ */
    @media (max-width: 768px) {
        /* Hide table, show cards */
        .pgw-table-wrap {
            display: none !important;
        }
        .pgw-cards-mobile {
            display: flex !important;
        }
        
        .desktop-pagination {
            display: none;
        }

        /* Form toggle stacks */
        .pgw-form-toggle {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .pgw-form-toggle .btn {
            width: 100%;
            justify-content: center;
        }

        /* Modal fullscreen on mobile */
        .modal-box {
            max-width: 100% !important;
            margin: 12px 12px 80px 12px;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
            border-radius: var(--radius-lg) !important;
        }
        .modal-overlay {
            padding: 0;
            align-items: flex-end !important;
        }
        .pgw-modal-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ============================
       SMALL MOBILE (≤ 480px)
    ============================ */
    @media (max-width: 480px) {
        .wizard-step-title {
            display: none;
        }
        .pgw-card-info-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .pgw-card-report-form-row {
            flex-direction: column;
            align-items: stretch;
        }
        .pgw-card-report-form-row input[type="file"] {
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;" x-data="{ 
    formOpen: false,
    step: 1,
    editModalOpen: false, 
    editId: '', 
    editData: {},
    openEditModal(id, data) {
        this.editId = id;
        this.editData = data;
        this.editModalOpen = true;
    },
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

{{-- ================================
     SECTION: FORM TAMBAH TUGAS MANDIRI
================================= --}}
@if($tab === 'mandiri')
<div class="section-box">
    <div class="pgw-form-toggle" @click="formOpen = !formOpen; if(!formOpen) step = 1;">
        <div class="pgw-form-toggle-info">
            <h3 class="section-title"><span class="title-icon"><i class="bi bi-pencil"></i></span> Tambah To-Do Mandiri</h3>
            <p>Buat tugas mandiri untuk mencatat pekerjaan pribadi Anda.</p>
        </div>
        <button type="button" class="btn btn-sm btn-secondary" x-text="formOpen ? 'Tutup Form ▴' : 'Buat Tugas Mandiri ▾'"></button>
    </div>
    <div x-show="formOpen" x-transition style="display: none; margin-top:20px;">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <input type="hidden" name="assigned_to" value="{{ Auth::id() }}">
            
            <div class="wizard-header">
                <div class="wizard-step-indicator" :class="{'active': step === 1, 'completed': step > 1}">
                    <div class="wizard-step-circle">1</div>
                    <div class="wizard-step-title">Info Utama</div>
                </div>
                <div class="wizard-step-indicator" :class="{'active': step === 2, 'completed': step > 2}">
                    <div class="wizard-step-circle">2</div>
                    <div class="wizard-step-title">Prioritas & Bobot</div>
                </div>
                <div class="wizard-step-indicator" :class="{'active': step === 3}">
                    <div class="wizard-step-circle">3</div>
                    <div class="wizard-step-title">Waktu Pelaksanaan</div>
                </div>
            </div>

            <div class="wizard-body">
                <div class="step-1" x-show="step === 1" x-transition>
                    <div class="form-group">
                        <label>Judul Pekerjaan</label>
                        <input type="text" name="judul" required>
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" rows="3" required></textarea>
                    </div>
                </div>

                <div class="step-2" x-show="step === 2" x-transition style="display:none;">
                    <div class="form-group">
                        <label>Prioritas</label>
                        <select name="prioritas" required>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Rendah">Rendah</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        <label>Bobot (1–100)</label>
                        <input type="number" name="bobot" min="1" max="100" value="30" required>
                    </div>
                </div>

                <div class="step-3" x-show="step === 3" x-transition style="display:none;">
                    <div class="form-group">
                        <label>Mulai</label>
                        <input type="date" name="tgl_mulai" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        <label>Selesai</label>
                        <input type="date" name="tgl_selesai" required>
                    </div>
                </div>
            </div>

            <div class="wizard-actions">
                <div>
                    <button type="button" class="btn btn-secondary" x-show="step > 1" @click="step--"><i class="bi bi-arrow-left"></i> Kembali</button>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" x-show="step < 3" @click="
                        let currentStep = $el.closest('form').querySelector('.step-' + step);
                        let inputs = currentStep.querySelectorAll('input[required], select[required], textarea[required]');
                        let valid = true;
                        inputs.forEach(i => {
                            if(!i.checkValidity()) {
                                i.reportValidity();
                                valid = false;
                            }
                        });
                        if(valid) step++;
                    ">Lanjut <i class="bi bi-arrow-right"></i></button>
                    <button type="submit" class="btn btn-success" x-show="step === 3"><i class="bi bi-send"></i> Tambah To-Do Mandiri</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

{{-- ================================
     SECTION: DAFTAR TUGAS
================================= --}}
<div class="section-box">
    <h3 class="section-title" style="margin-bottom: 16px;">
        <span class="title-icon">{!! $tab === 'pimpinan' ? '<i class="bi bi-person-badge"></i>' : '<i class="bi bi-person"></i>' !!}</span> 
        Daftar {!! $tab === 'pimpinan' ? 'Delegasi Pimpinan' : 'To-Do Mandiri' !!}
    </h3>

    <form method="GET" action="{{ route('pegawai.tasks') }}" style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px;">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul/deskripsi..." style="flex-grow:1; padding:8px 12px; border:1px solid var(--border-300); border-radius:var(--radius-sm); outline:none;">
        <select name="status" style="padding:8px 12px; border:1px solid var(--border-300); border-radius:var(--radius-sm); outline:none;">
            <option value="">Semua Status</option>
            <option value="Berlangsung" {{ request('status') == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
            <option value="Menunggu Review" {{ request('status') == 'Menunggu Review' ? 'selected' : '' }}>Menunggu Review</option>
            <option value="Revisi" {{ request('status') == 'Revisi' ? 'selected' : '' }}>Revisi</option>
            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        <select name="prioritas" style="padding:8px 12px; border:1px solid var(--border-300); border-radius:var(--radius-sm); outline:none;">
            <option value="">Semua Prioritas</option>
            <option value="Tinggi" {{ request('prioritas') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
            <option value="Sedang" {{ request('prioritas') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
            <option value="Rendah" {{ request('prioritas') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
        </select>
        <button type="submit" class="btn btn-primary" style="padding:8px 16px;"><i class="bi bi-search"></i> Cari</button>
        @if(request('search') || request('status') || request('prioritas'))
            <a href="{{ route('pegawai.tasks', ['tab' => $tab]) }}" class="btn btn-secondary" style="padding:8px 16px; text-decoration:none;"><i class="bi bi-x-lg"></i> Reset</a>
        @endif
        <a href="{{ route('tasks.export', array_merge(request()->all(), ['tab' => $tab])) }}" target="_blank" class="btn btn-success" style="padding:8px 16px; text-decoration:none; margin-left:auto;"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
    </form>

    {{-- ======= DESKTOP TABLE VIEW ======= --}}
    <div class="pgw-table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Detail Tugas</th>
                    <th>Bobot</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Laporan / Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $t)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: var(--text-500);">{{ $tasks->firstItem() + $loop->index }}</td>
                    <td>
                        <strong>{{ $t->judul }}</strong><br>
                        @if($tab === 'pimpinan')
                            <div style="font-size:11px; color:var(--primary-500); font-weight:600; margin-top:2px; margin-bottom:4px;">
                                <span style="font-weight: 500; color: var(--text-500); font-size: 10.5px;">From:</span> <i class="bi bi-person" style="margin-left:2px;"></i> {{ $t->creator->nama ?? '-' }} 
                                <span style="font-size:10px; color:var(--text-400); font-weight:500;">({{ $t->creator->unitKerja->nama_unit ?? '-' }})</span>
                            </div>
                        @endif
                        <small style="color:var(--text-500);">{{ \Illuminate\Support\Str::limit($t->deskripsi, 60) }}</small>
                        <div style="margin-top: 4px;">
                            @php
                                $prioClass = $t->prioritas === 'Tinggi' ? 'prio-tinggi' : ($t->prioritas === 'Rendah' ? 'prio-rendah' : 'prio-sedang');
                            @endphp
                            <span class="prio-badge {{ $prioClass }}">Prio: {{ $t->prioritas }}</span>
                        </div>
                    </td>
                    <td><strong>{{ $t->bobot }}</strong></td>
                    <td>
                        {{ $t->tgl_selesai->format('d M Y') }}
                        @if($t->is_overdue)
                            <br><span style="color:#E53E3E; font-size:11px; font-weight:700;"><i class="bi bi-exclamation-triangle"></i> Terlambat</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusBg = 'bg-proses';
                            if($t->status === 'Selesai') $statusBg = 'bg-selesai';
                            elseif($t->status === 'Menunggu Review') $statusBg = 'bg-proses';
                            elseif($t->status === 'Revisi') $statusBg = 'bg-belum';
                        @endphp
                        <span class="badge {{ $statusBg }}" {!! $t->status === 'Revisi' ? 'style="background:#FEE2E2; color:#991B1B;"' : ($t->status === 'Menunggu Review' ? 'style="background:#DBEAFE; color:#1E40AF;"' : '') !!}>
                            {{ $t->status }}
                        </span>
                    </td>
                    <td>
                        @if($t->status === 'Selesai')
                            <div style="margin-bottom: 8px;">
                                <span style="color:var(--text-500); font-weight:normal; font-size:12px;"><i class="bi bi-check2"></i> Terkirim: {{ $t->laporan }}</span>
                                @if($t->file_laporan)
                                    @php
                                        $ext = strtolower(pathinfo($t->file_laporan, PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <div style="margin-top: 6px;">
                                        @if($isImage)
                                            <a href="#" @click.prevent="openImageModal('{{ asset('storage/' . $t->file_laporan) }}')" style="font-size:11px; color:var(--primary-600); text-decoration:none;"><i class="bi bi-image"></i> Lihat Gambar</a>
                                        @else
                                            <a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" style="font-size:11px; color:var(--primary-600); text-decoration:none;"><i class="bi bi-file-earmark-text"></i> Lihat Lampiran</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @elseif($t->status === 'Menunggu Review')
                            <div style="margin-bottom: 8px;">
                                <span style="color:#1E40AF; font-weight:600; font-size:12px;"><i class="bi bi-hourglass-split"></i> Menunggu Pimpinan</span><br>
                                <small style="color:var(--text-500);">Laporan: {{ $t->laporan }}</small>
                                @if($t->file_laporan)
                                    @php
                                        $ext2 = strtolower(pathinfo($t->file_laporan, PATHINFO_EXTENSION));
                                        $isImage2 = in_array($ext2, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    <div style="margin-top: 6px;">
                                        @if($isImage2)
                                            <a href="#" @click.prevent="openImageModal('{{ asset('storage/' . $t->file_laporan) }}')" style="font-size:11px; color:var(--primary-600); text-decoration:none;"><i class="bi bi-image"></i> Lihat Gambar</a>
                                        @else
                                            <a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" style="font-size:11px; color:var(--primary-600); text-decoration:none;"><i class="bi bi-file-earmark-text"></i> Lihat Lampiran</a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @else
                            @if($t->status === 'Revisi')
                                <div style="margin-bottom: 4px;">
                                    <span style="color:#E53E3E; font-weight:600; font-size:11px;"><i class="bi bi-exclamation-triangle"></i> Pimpinan meminta revisi laporan!</span>
                                </div>
                            @endif
                            <form action="{{ route('tasks.report', $t->id) }}" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:5px; margin-bottom: 8px; background:var(--bg-50); padding:8px; border-radius:6px; border:1px dashed var(--border-300);">
                                @csrf
                                <input type="text" name="laporan" placeholder="Tulis hasil singkat..." value="{!! $t->status === 'Revisi' ? $t->laporan : '' !!}" required style="padding: 6px; font-size: 12px; border:1px solid var(--border-200); border-radius:4px; outline:none;" onfocus="this.style.borderColor='var(--primary-400)'" onblur="this.style.borderColor='var(--border-200)'">
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <input type="file" name="file_laporan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="font-size:11px; width:140px;">
                                    <button type="submit" class="btn btn-sm" style="flex-grow:1;">{!! $t->status === 'Revisi' ? 'Kirim Revisi' : 'Kirim Laporan' !!}</button>
                                </div>
                            </form>
                        @endif

                        @if($t->comments->count() > 0 || $t->status === 'Revisi')
                        <details style="margin-bottom: 8px;">
                            <summary style="font-size: 11.5px; font-weight:600; cursor: pointer; color: var(--primary-600); padding:4px 0;"><i class="bi bi-chat-dots"></i> Riwayat Catatan ({{ $t->comments->count() }})</summary>
                            <div style="background: var(--bg-100); padding: 10px; border-radius: 6px; margin-top: 4px; font-size: 11.5px; border:1px solid var(--border-200);">
                                @foreach($t->comments as $c)
                                    <div style="margin-bottom: 8px; border-bottom:1px solid var(--border-200); padding-bottom:6px;">
                                        <div style="display:flex; justify-content:space-between; margin-bottom:2px;">
                                            <strong style="color:var(--text-900);">{{ $c->user->nama }}</strong>
                                            <span style="font-size: 9px; color: var(--text-400);">{{ $c->created_at->format('d M H:i') }}</span>
                                        </div>
                                        <div style="color:var(--text-700);">{{ $c->komentar }}</div>
                                    </div>
                                @endforeach
                                <form action="{{ route('tasks.comments.store', $t->id) }}" method="POST" style="display: flex; gap: 5px; margin-top: 8px;">
                                    @csrf
                                    <input type="text" name="komentar" placeholder="Tulis catatan..." required style="flex-grow: 1; padding: 6px; font-size: 11px; border:1px solid var(--border-300); border-radius:4px;">
                                    <button class="btn btn-sm" style="padding:4px 10px;">Balas</button>
                                </form>
                            </div>
                        </details>
                        @endif

                        <div style="display:flex; gap:5px; align-items: center; white-space: nowrap;">
                            @if($tab === 'pimpinan')
                                <button type="button" class="btn btn-sm btn-secondary" @click="openViewModal({{ \Illuminate\Support\Js::from(['judul' => $t->judul, 'deskripsi' => $t->deskripsi, 'prioritas' => $t->prioritas, 'bobot' => $t->bobot, 'tgl_mulai' => $t->tgl_mulai->format('Y-m-d'), 'tgl_selesai' => $t->tgl_selesai->format('Y-m-d'), 'assignee_nama' => $t->creator->nama ?? '-', 'assignee_unit' => $t->creator->unitKerja->nama_unit ?? '-', 'status' => $t->status, 'laporan' => $t->laporan]) }})"><i class="bi bi-eye"></i> View</button>
                            @else
                                <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $t->id }}, {{ \Illuminate\Support\Js::from(['judul' => $t->judul, 'deskripsi' => $t->deskripsi, 'prioritas' => $t->prioritas, 'bobot' => $t->bobot, 'tgl_mulai' => $t->tgl_mulai->format('Y-m-d'), 'tgl_selesai' => $t->tgl_selesai->format('Y-m-d'), 'assigned_to' => $t->assigned_to]) }})"><i class="bi bi-pencil"></i> Edit</button>
                            @endif
                            
                            @if($tab === 'mandiri')
                            <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tugas mandiri ini?');" style="margin: 0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-icon">{!! $tab === 'pimpinan' ? '<i class="bi bi-person-badge"></i>' : '<i class="bi bi-person"></i>' !!}</div>
                            <p>Belum ada tugas {!! $tab === 'pimpinan' ? 'dari pimpinan' : 'mandiri' !!}.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ======= MOBILE CARD VIEW ======= --}}
    <div class="pgw-cards-mobile">
        @forelse($tasks as $t)
        <div class="pgw-card" x-data="{ expanded: false }" style="border-bottom: 1px solid var(--border-200); padding: 16px 0; border-radius: 0; border-left: none; border-right: none; border-top: none; background: transparent; box-shadow: none;">
            {{-- Notification Header --}}
            <div class="notification-header" @click="expanded = !expanded" style="display: flex; gap: 12px; cursor: pointer;">
                @if($tab === 'pimpinan')
                    <div style="flex-shrink: 0;">
                        @if($t->creator && $t->creator->foto)
                            <img src="{{ \Illuminate\Support\Str::startsWith($t->creator->foto, ['http://', 'https://']) ? $t->creator->foto : Storage::url($t->creator->foto) }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary-800); color: white; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700;">
                                {{ substr($t->creator->nama ?? '?', 0, 1) }}
                            </div>
                        @endif
                    </div>
                @endif
                <div style="flex-grow: 1; min-width: 0;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2px;">
                        <strong style="color: var(--text-900); font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            @if($tab === 'pimpinan')
                                <span style="font-weight: 500; color: var(--text-500); font-size: 12px;">From: </span>{{ $t->creator->nama ?? '-' }}
                            @else
                                {{ $t->judul }}
                            @endif
                        </strong>
                        @php
                            $statusBg = 'bg-proses';
                            if($t->status === 'Selesai') $statusBg = 'bg-selesai';
                            elseif($t->status === 'Menunggu Review') $statusBg = 'bg-proses';
                            elseif($t->status === 'Revisi') $statusBg = 'bg-belum';
                        @endphp
                        <span class="badge {{ $statusBg }}" {!! $t->status === 'Revisi' ? 'style="background:#FEE2E2; color:#991B1B;"' : ($t->status === 'Menunggu Review' ? 'style="background:#DBEAFE; color:#1E40AF;"' : '') !!} style="font-size: 9px; padding: 2px 6px;">{{ $t->status }}</span>
                    </div>
                    <div style="color: var(--text-600); font-size: 13.5px; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">
                        @if($tab === 'pimpinan') <span style="font-weight: 600; color: var(--text-800);">{{ $t->judul }}</span> - @endif {{ $t->deskripsi }}
                    </div>
                    <div style="font-size: 11px; color: var(--text-400);">
                        {{ $t->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            {{-- Expanded Card Body --}}
            <div x-show="expanded" x-transition style="display: none; margin-top: 16px; padding-top: 16px; border-top: 1px dashed var(--border-200);">
                <div class="pgw-card-body" style="padding: 0;">
                {{-- Info Grid: Bobot, Deadline, Mulai --}}
                <div class="pgw-card-info-grid">
                    <div class="pgw-card-info-item">
                        <span class="pgw-card-info-label">Bobot</span>
                        <span class="pgw-card-info-value">{{ $t->bobot }}</span>
                    </div>
                    <div class="pgw-card-info-item">
                        <span class="pgw-card-info-label">Mulai</span>
                        <span class="pgw-card-info-value">{{ $t->tgl_mulai->format('d M Y') }}</span>
                    </div>
                    <div class="pgw-card-info-item">
                        <span class="pgw-card-info-label">Deadline</span>
                        <span class="pgw-card-info-value {{ $t->is_overdue ? 'overdue' : '' }}">
                            {{ $t->tgl_selesai->format('d M Y') }}
                            @if($t->is_overdue) <i class="bi bi-exclamation-triangle"></i> @endif
                        </span>
                    </div>
                </div>

                {{-- Laporan / Report Status --}}
                @if($t->status === 'Selesai')
                    <div class="pgw-card-report-done">
                        <div class="pgw-card-report-text done"><i class="bi bi-check2"></i> Terkirim: {{ $t->laporan }}</div>
                        @if($t->file_laporan)
                            @php
                                $ext3 = strtolower(pathinfo($t->file_laporan, PATHINFO_EXTENSION));
                                $isImage3 = in_array($ext3, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp
                            <div style="margin-top: 6px;">
                                @if($isImage3)
                                    <a href="#" @click.prevent="openImageModal('{{ asset('storage/' . $t->file_laporan) }}')" class="pgw-card-report-link"><i class="bi bi-image"></i> Lihat Gambar</a>
                                @else
                                    <a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" class="pgw-card-report-link"><i class="bi bi-file-earmark-text"></i> Lihat Lampiran</a>
                                @endif
                            </div>
                        @endif
                    </div>
                @elseif($t->status === 'Menunggu Review')
                    <div class="pgw-card-report-done">
                        <div class="pgw-card-report-text waiting"><i class="bi bi-hourglass-split"></i> Menunggu Review Pimpinan</div>
                        <div class="pgw-card-report-sub">Laporan: {{ $t->laporan }}</div>
                        @if($t->file_laporan)
                            @php
                                $ext4 = strtolower(pathinfo($t->file_laporan, PATHINFO_EXTENSION));
                                $isImage4 = in_array($ext4, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp
                            <div style="margin-top: 6px;">
                                @if($isImage4)
                                    <a href="#" @click.prevent="openImageModal('{{ asset('storage/' . $t->file_laporan) }}')" class="pgw-card-report-link"><i class="bi bi-image"></i> Lihat Gambar</a>
                                @else
                                    <a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" class="pgw-card-report-link"><i class="bi bi-file-earmark-text"></i> Lihat Lampiran</a>
                                @endif
                            </div>
                        @endif
                    </div>
                @else
                    @if($t->status === 'Revisi')
                        <div style="margin-bottom: 8px;">
                            <span style="color:#E53E3E; font-weight:700; font-size:12px;"><i class="bi bi-exclamation-triangle"></i> Pimpinan meminta revisi laporan!</span>
                        </div>
                    @endif
                    <form action="{{ route('tasks.report', $t->id) }}" method="POST" enctype="multipart/form-data" class="pgw-card-report-form">
                        @csrf
                        <input type="text" name="laporan" placeholder="Tulis hasil singkat..." value="{!! $t->status === 'Revisi' ? $t->laporan : '' !!}" required>
                        <div class="pgw-card-report-form-row">
                            <input type="file" name="file_laporan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <button type="submit" class="btn btn-sm">{!! $t->status === 'Revisi' ? '<i class="bi bi-arrow-repeat"></i> Kirim Revisi' : '<i class="bi bi-send"></i> Kirim Laporan' !!}</button>
                        </div>
                    </form>
                @endif

                {{-- Notes / Catatan --}}
                @if($t->comments->count() > 0 || $t->status === 'Revisi')
                <details>
                    <summary class="pgw-card-notes-toggle"><i class="bi bi-chat-dots"></i> Riwayat Catatan ({{ $t->comments->count() }})</summary>
                    <div class="pgw-card-notes-content">
                        @foreach($t->comments as $c)
                            <div class="pgw-note-item">
                                <div class="pgw-note-header">
                                    <span class="pgw-note-author">{{ $c->user->nama }}</span>
                                    <span class="pgw-note-time">{{ $c->created_at->format('d M H:i') }}</span>
                                </div>
                                <div class="pgw-note-text">{{ $c->komentar }}</div>
                            </div>
                        @endforeach
                        <form action="{{ route('tasks.comments.store', $t->id) }}" method="POST" class="pgw-note-form">
                            @csrf
                            <input type="text" name="komentar" placeholder="Tulis catatan..." required>
                            <button class="btn btn-sm" style="padding:8px 14px;">Balas</button>
                        </form>
                    </div>
                </details>
                @endif
            </div>

            {{-- Card Footer / Actions --}}
            <div class="pgw-card-footer">
                @if($t->is_overdue)
                    <span style="color:#E53E3E; font-size:11px; font-weight:700;"><i class="bi bi-exclamation-triangle"></i> Terlambat</span>
                @else
                    <span style="color:var(--text-400); font-size:11px;">{{ $t->tgl_mulai->format('d/m/Y') }} — {{ $t->tgl_selesai->format('d/m/Y') }}</span>
                @endif
                <div class="pgw-card-actions">
                    @if($tab === 'pimpinan')
                        <button type="button" class="btn btn-sm btn-secondary" @click="openViewModal({{ \Illuminate\Support\Js::from(['judul' => $t->judul, 'deskripsi' => $t->deskripsi, 'prioritas' => $t->prioritas, 'bobot' => $t->bobot, 'tgl_mulai' => $t->tgl_mulai->format('Y-m-d'), 'tgl_selesai' => $t->tgl_selesai->format('Y-m-d'), 'assignee_nama' => $t->creator->nama ?? '-', 'assignee_unit' => $t->creator->unitKerja->nama_unit ?? '-', 'status' => $t->status, 'laporan' => $t->laporan]) }})"><i class="bi bi-eye"></i> View</button>
                    @else
                        <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $t->id }}, {{ \Illuminate\Support\Js::from(['judul' => $t->judul, 'deskripsi' => $t->deskripsi, 'prioritas' => $t->prioritas, 'bobot' => $t->bobot, 'tgl_mulai' => $t->tgl_mulai->format('Y-m-d'), 'tgl_selesai' => $t->tgl_selesai->format('Y-m-d'), 'assigned_to' => $t->assigned_to]) }})"><i class="bi bi-pencil"></i> Edit</button>
                    @endif
                    @if($tab === 'mandiri')
                    <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tugas mandiri ini?');" style="margin: 0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    @endif
                </div>
            </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">{!! $tab === 'pimpinan' ? '<i class="bi bi-person-badge"></i>' : '<i class="bi bi-person"></i>' !!}</div>
            <p>Belum ada tugas {!! $tab === 'pimpinan' ? 'dari pimpinan' : 'mandiri' !!}. {{ $tab === 'mandiri' ? 'Tekan "Buat Tugas Mandiri" untuk memulai.' : '' }}</p>
        </div>
        @endforelse
    </div>

    {{-- Desktop Pagination --}}
    <div class="desktop-pagination" style="margin-top: 16px;">
        {{ $tasks->appends(['tab' => request('tab')])->links() }}
    </div>
    
    {{-- Mobile Pagination --}}
    <div class="pagination-mobile">
        <span class="page-info">Page {{ $tasks->currentPage() }} / {{ $tasks->lastPage() }}</span>
        <div class="page-nav-buttons">
            @if($tasks->onFirstPage())
                <span class="disabled"><i class="bi bi-chevron-left"></i> Prev</span>
            @else
                <a href="{{ $tasks->appends(['tab' => request('tab')])->previousPageUrl() }}"><i class="bi bi-chevron-left"></i> Prev</a>
            @endif
            @if($tasks->hasMorePages())
                <a href="{{ $tasks->appends(['tab' => request('tab')])->nextPageUrl() }}">Next <i class="bi bi-chevron-right"></i></a>
            @else
                <span class="disabled">Next <i class="bi bi-chevron-right"></i></span>
            @endif
        </div>
    </div>
</div>

{{-- ================================
     EDIT MODAL (ALPINE JS)
================================= --}}
<div class="modal-overlay" :class="{ 'show': editModalOpen }" x-show="editModalOpen" style="display: none;" x-transition>
    <div class="modal-box" @click.away="editModalOpen = false">
        <div class="modal-header">
            <h3>{!! $tab === 'pimpinan' ? '<i class="bi bi-eye"></i> View Tugas' : '<i class="bi bi-pencil"></i> Edit Tugas' !!}</h3>
            <button type="button" class="modal-close" @click="editModalOpen = false">×</button>
        </div>
        <form :action="'{{ url('/tasks') }}/' + editId" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Judul Pekerjaan / Tugas</label>
                <input type="text" name="judul" x-model="editData.judul" {!! $tab === 'pimpinan' ? 'readonly' : 'required' !!}>
            </div>
            <div class="form-group">
                <label>Deskripsi Detail Tugas</label>
                <textarea name="deskripsi" rows="3" x-model="editData.deskripsi" {!! $tab === 'pimpinan' ? 'readonly' : '' !!}></textarea>
            </div>
            <div class="form-group">
                <label>Prioritas</label>
                <select name="prioritas" x-model="editData.prioritas" {!! $tab === 'pimpinan' ? 'disabled' : 'required' !!}>
                    <option value="Sedang">Sedang</option>
                    <option value="Tinggi">Tinggi</option>
                    <option value="Rendah">Rendah</option>
                </select>
            </div>
            <div class="pgw-modal-grid">
                <div class="form-group">
                    <label>Bobot (1–100)</label>
                    <input type="number" name="bobot" min="1" max="100" x-model="editData.bobot" {!! $tab === 'pimpinan' ? 'readonly' : 'required' !!}>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" x-model="editData.tgl_mulai" {!! $tab === 'pimpinan' ? 'readonly' : 'required' !!}>
                </div>
            </div>
            <div class="form-group">
                <label>Deadline Penyelesaian</label>
                <input type="date" name="tgl_selesai" x-model="editData.tgl_selesai" {!! $tab === 'pimpinan' ? 'readonly' : 'required' !!}>
            </div>
            <input type="hidden" name="assigned_to" x-model="editData.assigned_to">
            
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" @click="editModalOpen = false">{!! $tab === 'pimpinan' ? 'Tutup' : 'Batal' !!}</button>
                @if($tab === 'mandiri')
                <button type="submit" class="btn"><i class="bi bi-floppy"></i> Simpan Perubahan</button>
                @endif
            </div>
        </form>
    </div>
</div>

    <!-- ============================
         VIEW MODAL (ALPINE JS)
    ============================ -->
    <div class="modal-overlay" :class="{ 'show': viewModalOpen }" x-show="viewModalOpen" style="display: none;" x-transition>
        <div class="modal-box" @click.away="viewModalOpen = false" style="padding: 0; overflow: hidden; max-width: 550px;">
            <div class="modal-header" style="padding: 20px 24px 16px; margin-bottom: 0; border-bottom: 1px solid var(--border-100); background: var(--bg-white);">
                <h3 style="margin: 0; font-size: 16px;"><i class="bi bi-file-text"></i> Laporan Tugas</h3>
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
                        Belum ada laporan pekerjaan.
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
