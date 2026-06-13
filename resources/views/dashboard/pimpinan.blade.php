@extends('layouts.app')

@section('page_title')
    @if($tab === 'masuk')
        <h2>Tugas Masuk — Pimpinan</h2>
        <p>Daftar tugas yang didelegasikan kepada Anda dari atasan</p>
    @elseif($tab === 'mandiri')
        <h2>Tugas Mandiri — Pimpinan</h2>
        <p>Daftar tugas yang Anda inisiasi sendiri di luar penugasan</p>
    @else
        <h2>Delegasi Tugas — Pimpinan</h2>
        <p>Berikan tugas khusus kepada staf atau bawahan Anda dan monitor progresnya</p>
    @endif
@endsection

@push('styles')
<style>
    /* ============================
       PIMPINAN PAGE – RESPONSIVE STYLES
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

    /* --- Task Form Section --- */
    .task-form-toggle {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        gap: 12px;
    }
    .task-form-toggle-info h3 {
        margin-bottom: 6px;
    }
    .task-form-toggle-info p {
        font-size: 12px;
        color: var(--text-500);
        margin: 0;
        line-height: 1.5;
    }
    .task-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }
    .task-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    .task-form-submit {
        width: 100%;
        margin-top: 10px;
    }

    /* --- Desktop Table (hidden on mobile) --- */
    .task-table-wrap {
        overflow-x: auto;
        width: 100%;
    }
    .task-table-wrap table {
        min-width: 750px;
    }

    /* --- Mobile Card Layout (hidden on desktop) --- */
    .task-cards-mobile {
        display: none;
        flex-direction: column;
        gap: 16px;
    }
    .task-card {
        background: var(--bg-white);
        border: 1px solid var(--border-200);
        border-radius: var(--radius-lg);
        padding: 0;
        overflow: hidden;
        transition: box-shadow var(--transition-base);
    }
    .task-card:hover {
        box-shadow: var(--shadow-md);
    }
    .task-card-header {
        padding: 16px 16px 12px;
        border-bottom: 1px solid var(--border-100);
    }
    .task-card-title {
        font-weight: 700;
        color: var(--text-900);
        font-size: 14px;
        margin-bottom: 6px;
        line-height: 1.4;
    }
    .task-card-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--primary-500);
        font-weight: 600;
        margin-bottom: 6px;
    }
    .task-card-desc {
        font-size: 12px;
        color: var(--text-500);
        line-height: 1.5;
        margin-bottom: 8px;
    }
    .task-card-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
    }
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

    .task-card-body {
        padding: 12px 16px;
    }
    .task-card-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }
    .task-card-info-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .task-card-info-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-400);
        font-weight: 700;
    }
    .task-card-info-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-700);
    }
    .task-card-info-value.overdue {
        color: #E53E3E;
    }

    /* Laporan / Report section in card */
    .task-card-report {
        background: var(--bg-app);
        border-radius: var(--radius-md);
        padding: 10px 12px;
        margin-bottom: 12px;
        border: 1px solid var(--border-100);
    }
    .task-card-report-text {
        color: var(--teal-600);
        font-weight: 600;
        font-size: 12px;
        line-height: 1.5;
    }
    .task-card-report-link {
        font-size: 11.5px;
        color: var(--primary-600);
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
    }
    .task-card-report-link:hover {
        text-decoration: underline;
    }
    .task-card-no-report {
        font-size: 12px;
        color: var(--text-400);
        font-style: italic;
        margin-bottom: 12px;
    }

    /* Review buttons in card */
    .task-card-review-actions {
        display: flex;
        gap: 8px;
        margin-top: 8px;
    }
    .task-card-review-actions .btn {
        flex: 1;
        font-size: 12px;
        padding: 8px 12px;
    }

    /* Card footer / Actions */
    .task-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-top: 1px solid var(--border-100);
        background: var(--bg-app);
    }
    .task-card-actions {
        display: flex;
        gap: 8px;
    }
    .task-card-actions .btn {
        padding: 8px 14px;
        font-size: 12px;
    }

    /* Catatan / Notes detail in card */
    .task-card-notes-toggle {
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        color: var(--primary-600);
        padding: 6px 0;
        user-select: none;
    }
    .task-card-notes-content {
        background: var(--bg-white);
        padding: 10px;
        border-radius: var(--radius-md);
        margin-top: 6px;
        font-size: 12px;
        border: 1px solid var(--border-200);
    }
    .note-item {
        margin-bottom: 8px;
        border-bottom: 1px solid var(--border-100);
        padding-bottom: 6px;
    }
    .note-item:last-of-type {
        margin-bottom: 0;
        border-bottom: none;
        padding-bottom: 0;
    }
    .note-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2px;
        align-items: center;
    }
    .note-author {
        font-weight: 700;
        color: var(--text-900);
        font-size: 11.5px;
    }
    .note-time {
        font-size: 9.5px;
        color: var(--text-400);
    }
    .note-text {
        color: var(--text-700);
        font-size: 12px;
        line-height: 1.4;
    }
    .note-form {
        display: flex;
        gap: 6px;
        margin-top: 8px;
    }
    .note-form input {
        flex-grow: 1;
        padding: 8px 10px;
        font-size: 12px;
        border: 1px solid var(--border-300);
        border-radius: var(--radius-sm);
        font-family: inherit;
    }

    /* --- Modal Responsive --- */
    .modal-box .modal-form-dates {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* ============================
       TABLET BREAKPOINT (≤ 1024px)
    ============================ */
    @media (max-width: 1024px) {
        .task-form-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ============================
       MOBILE BREAKPOINT (≤ 768px)
    ============================ */
    @media (max-width: 768px) {
        /* Form goes single column */
        .task-form-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }
        .task-form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
        .task-form-toggle {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .task-form-toggle .btn {
            width: 100%;
            justify-content: center;
        }

        /* Hide table, show cards */
        .task-table-wrap {
            display: none !important;
        }
        .task-cards-mobile {
            display: flex !important;
        }

        /* Modal fullscreen on mobile */
        .modal-box {
            max-width: 100% !important;
            margin: 12px;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: var(--radius-lg) !important;
        }
        .modal-overlay {
            padding: 0;
            align-items: flex-end !important;
        }
        .modal-box .modal-form-dates {
            flex-direction: column;
        }
        /* Hide desktop pagination on mobile */
        .desktop-pagination {
            display: none;
        }
    }

    /* ============================
       SMALL MOBILE (≤ 480px)
    ============================ */
    @media (max-width: 480px) {
        .wizard-step-title {
            display: none;
        }
        .task-card-info-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .task-card-review-actions {
            flex-direction: column;
        }
        .task-card-review-actions .btn {
            width: 100%;
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
    }
}">

    {{-- ================================
         SECTION: TAB NAVIGATION
    ================================= --}}
    <div style="display:flex; gap:10px; margin-bottom: 20px; flex-wrap: nowrap; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; scrollbar-width: none;" class="tabs-scroll">
        <style>
            .tabs-scroll::-webkit-scrollbar { display: none; }
        </style>
        <a href="{{ route('pimpinan.tasks', ['tab' => 'delegasi']) }}" class="btn {{ $tab === 'delegasi' ? 'btn-primary' : 'btn-secondary' }}" style="padding:10px 20px; flex-shrink: 0;">
            <i class="bi bi-arrow-right-square"></i> Delegasi Keluar
        </a>
        @if(optional(Auth::user()->unitKerja)->parent_id !== null)
        <a href="{{ route('pimpinan.tasks', ['tab' => 'masuk']) }}" class="btn {{ $tab === 'masuk' ? 'btn-primary' : 'btn-secondary' }}" style="padding:10px 20px; flex-shrink: 0;">
            <i class="bi bi-inbox"></i> Tugas Masuk
        </a>
        @endif
        <a href="{{ route('pimpinan.tasks', ['tab' => 'mandiri']) }}" class="btn {{ $tab === 'mandiri' ? 'btn-primary' : 'btn-secondary' }}" style="padding:10px 20px; flex-shrink: 0;">
            <i class="bi bi-person-check"></i> Tugas Mandiri
        </a>
    </div>

    {{-- ================================
         SECTION: FORM TUGAS (Hanya tampil di tab Delegasi/Mandiri)
    ================================= --}}
    @if($tab !== 'masuk')
    <div class="section-box">
        <div class="task-form-toggle" @click="formOpen = !formOpen; if(!formOpen) step = 1;">
            <div class="task-form-toggle-info">
                <h3 class="section-title"><span class="title-icon"><i class="bi bi-pencil-square"></i></span> {{ $tab === 'delegasi' ? 'Delegasikan Tugas Baru' : 'Buat Tugas Mandiri' }}</h3>
                <p>{{ $tab === 'delegasi' ? 'Tugas yang Anda buat akan otomatis muncul di dashboard pegawai yang ditugaskan.' : 'Catat tugas yang Anda inisiasi sendiri di sini.' }}</p>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" x-text="formOpen ? 'Tutup Form ▴' : 'Buat Tugas Baru ▾'"></button>
        </div>
        <form action="{{ route('tasks.store') }}" method="POST" x-show="formOpen" x-transition style="display: none; margin-top:20px;">
            @csrf
            
            <div class="wizard-header">
                <div class="wizard-step-indicator" :class="{'active': step === 1, 'completed': step > 1}">
                    <div class="wizard-step-circle">1</div>
                    <div class="wizard-step-title">Info Utama</div>
                </div>
                <div class="wizard-step-indicator" :class="{'active': step === 2, 'completed': step > 2}">
                    <div class="wizard-step-circle">2</div>
                    <div class="wizard-step-title">Prioritas & Penugasan</div>
                </div>
                <div class="wizard-step-indicator" :class="{'active': step === 3}">
                    <div class="wizard-step-circle">3</div>
                    <div class="wizard-step-title">Waktu Pelaksanaan</div>
                </div>
            </div>

            <div class="wizard-body">
                <div class="step-1" x-show="step === 1" x-transition>
                    <div class="form-group">
                        <label>Judul Pekerjaan / Tugas</label>
                        <input type="text" name="judul" required>
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        <label>Deskripsi Detail Tugas</label>
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
                    @if($tab === 'delegasi')
                    <div class="form-group" style="margin-top:15px;">
                        <label>Pegawai yang Ditugaskan</label>
                        <select name="assigned_to" required>
                            <option value="" disabled selected>-- Pilih Pegawai --</option>
                            @foreach($pegawais as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->unitKerja->nama_unit ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <input type="hidden" name="assigned_to" value="{{ Auth::id() }}">
                    @endif
                    <div class="form-group" style="margin-top:15px;">
                        <label>Bobot (1–100)</label>
                        <input type="number" name="bobot" min="1" max="100" value="50" required>
                    </div>
                </div>

                <div class="step-3" x-show="step === 3" x-transition style="display:none;">
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        <label>Deadline Penyelesaian</label>
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
                    <button type="submit" class="btn btn-success" x-show="step === 3"><i class="bi bi-send"></i> Kirim Tugas</button>
                </div>
            </div>
        </form>
    </div>
    @endif

    {{-- ================================
         SECTION: MONITORING KERJA
    ================================= --}}
    <div class="section-box">
        <h3 class="section-title" style="margin-bottom: 18px;"><span class="title-icon"><i class="bi bi-bar-chart-line"></i></span> {{ $tab === 'delegasi' ? 'Monitoring Kerja Bawahan' : 'Daftar Tugas Anda' }}</h3>

        <form method="GET" action="{{ route('pimpinan.tasks') }}" style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px;">
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
                <a href="{{ route('pimpinan.tasks') }}" class="btn btn-secondary" style="padding:8px 16px; text-decoration:none;"><i class="bi bi-x-lg"></i> Reset</a>
            @endif
            <a href="{{ route('tasks.export', array_merge(request()->all(), ['tab' => 'pimpinan'])) }}" target="_blank" class="btn btn-success" style="padding:8px 16px; text-decoration:none; margin-left:auto;"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
        </form>

        {{-- ======= DESKTOP TABLE VIEW ======= --}}
        <div class="task-table-wrap">
            <table>
                <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Detail Tugas</th>
                    <th>Waktu Pelaksanaan</th>
                    <th>Status & Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $t)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: var(--text-500);">{{ $tasks->firstItem() + $loop->index }}</td>
                    <td>
                        <div style="font-weight:700; color:var(--text-900); font-size:13.5px; margin-bottom:4px;">{{ $t->judul }}</div>
                        <div style="font-size:11.5px; color:var(--primary-500); font-weight:600; margin-bottom:2px;">
                            @if($tab !== 'mandiri')
                                @php
                                    $person = $tab === 'masuk' ? $t->creator : $t->assignee;
                                    $prefix = $tab === 'masuk' ? 'From: ' : 'To: ';
                                @endphp
                                <i class="bi bi-person"></i> <span style="font-weight: 500; color: var(--text-500); font-size: 10px;">{{ $prefix }}</span>{{ $person->nama ?? '-' }} 
                                <span style="font-size:10px; color:var(--text-400); font-weight:500;">({{ $person->unitKerja->nama_unit ?? '-' }})</span>
                                &nbsp;&bull;&nbsp; 
                            @endif
                            Bobot: {{ $t->bobot }}
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
                            @php
                                $statusBg = 'bg-proses';
                                if($t->status === 'Selesai') $statusBg = 'bg-selesai';
                                elseif($t->status === 'Menunggu Review') $statusBg = 'bg-proses';
                                elseif($t->status === 'Revisi') $statusBg = 'bg-belum';
                            @endphp
                            <span class="badge {{ $statusBg }}" {!! $t->status === 'Revisi' ? 'style="background:#FEE2E2; color:#991B1B;"' : ($t->status === 'Menunggu Review' ? 'style="background:#DBEAFE; color:#1E40AF;"' : '') !!}>
                                {{ $t->status }}
                            </span>
                        </div>
                        <div>
                            @if($tab === 'delegasi')
                                {{-- VIEW AS CREATOR: Review laporan bawahan --}}
                                @if($t->laporan)
                                    <div style="color:var(--teal-600); font-weight:600; font-size:11px; max-width:200px; white-space:normal;">
                                        <i class="bi bi-check2"></i> {{ \Illuminate\Support\Str::limit($t->laporan, 50) }}
                                        @if($t->file_laporan)
                                            <br><a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" style="font-size:11px; color:var(--primary-600); text-decoration:none;"><i class="bi bi-file-earmark-text"></i> Lihat Lampiran</a>
                                        @endif
                                    </div>
                                    @if($t->status === 'Menunggu Review')
                                        <form action="{{ route('tasks.review', $t->id) }}" method="POST" style="margin-top: 8px; display: flex; gap: 5px;">
                                            @csrf
                                            <button type="submit" name="action" value="approve" class="btn btn-sm" style="padding: 4px 8px; font-size: 10px;"><i class="bi bi-check-circle"></i> Setujui</button>
                                            <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger" style="padding: 4px 8px; font-size: 10px;" onsubmit="return confirm('Minta pegawai merevisi laporan ini?');"><i class="bi bi-arrow-return-left"></i> Revisi</button>
                                        </form>
                                    @endif
                                @else
                                    <div style="font-size:11px; color:var(--text-400); font-style:italic;">Belum ada laporan</div>
                                @endif
                            @else
                                {{-- VIEW AS ASSIGNEE: Form submit laporan --}}
                                @if($t->status === 'Selesai')
                                    <div style="color:var(--teal-600); font-weight:600; font-size:11px; max-width:200px; white-space:normal;">
                                        <i class="bi bi-check2"></i> Terkirim: {{ \Illuminate\Support\Str::limit($t->laporan, 50) }}
                                        @if($t->file_laporan)
                                            <br><a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" style="font-size:11px; color:var(--primary-600); text-decoration:none;"><i class="bi bi-file-earmark-text"></i> Lihat Lampiran</a>
                                        @endif
                                    </div>
                                @elseif($t->status === 'Menunggu Review')
                                    <div style="color:#1E40AF; font-weight:600; font-size:11px;"><i class="bi bi-hourglass-split"></i> Menunggu Review Pimpinan</div>
                                    <small style="color:var(--text-500); font-size:10px;">Laporan: {{ $t->laporan }}</small>
                                @else
                                    <form action="{{ route('tasks.report', $t->id) }}" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:5px; margin-top: 8px; background:var(--bg-50); padding:8px; border-radius:6px; border:1px dashed var(--border-300);">
                                        @csrf
                                        <input type="text" name="laporan" placeholder="Tulis hasil singkat..." value="{!! $t->status === 'Revisi' ? $t->laporan : '' !!}" required style="padding: 6px; font-size: 11px; border:1px solid var(--border-200); border-radius:4px; outline:none;">
                                        <div style="display:flex; gap:5px; align-items:center;">
                                            <input type="file" name="file_laporan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="font-size:10px; width:120px;">
                                            <button type="submit" class="btn btn-sm" style="flex-grow:1; font-size:10px; padding:4px;">Kirim Laporan</button>
                                        </div>
                                    </form>
                                @endif
                            @endif
                        </div>

                        <details style="margin-top: 8px;">
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
                                    <input type="text" name="komentar" placeholder="Tulis catatan/revisi..." required style="flex-grow: 1; padding: 6px; font-size: 11px; border:1px solid var(--border-300); border-radius:4px;">
                                    <button class="btn btn-sm" style="padding:4px 10px;">Kirim</button>
                                </form>
                            </div>
                        </details>
                    </td>
                    <td>
                        <div style="display:flex; gap:5px; align-items: center; white-space: nowrap;">
                            @if($tab === 'delegasi')
                                <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $t->id }}, {{ \Illuminate\Support\Js::from(['judul' => $t->judul, 'deskripsi' => $t->deskripsi, 'prioritas' => $t->prioritas, 'assigned_to' => $t->assigned_to, 'bobot' => $t->bobot, 'tgl_mulai' => $t->tgl_mulai->format('Y-m-d'), 'tgl_selesai' => $t->tgl_selesai->format('Y-m-d')]) }})"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Tarik kembali/hapus tugas ini?');" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @else
                                <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $t->id }}, {{ \Illuminate\Support\Js::from(['judul' => $t->judul, 'deskripsi' => $t->deskripsi, 'prioritas' => $t->prioritas, 'bobot' => $t->bobot, 'tgl_mulai' => $t->tgl_mulai->format('Y-m-d'), 'tgl_selesai' => $t->tgl_selesai->format('Y-m-d'), 'assigned_to' => $t->assigned_to]) }})">👁️ Detail</button>
                                
                                @if($tab === 'mandiri')
                                <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tugas mandiri ini?');" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-card-checklist"></i></div>
                            <p>Belum ada tugas yang didelegasikan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            </table>
        </div>

        {{-- ======= MOBILE CARD VIEW ======= --}}
        <div class="task-cards-mobile">
            @forelse($tasks as $t)
            <div class="task-card" x-data="{ expanded: false }" style="border-bottom: 1px solid var(--border-200); padding: 16px 0; border-radius: 0; border-left: none; border-right: none; border-top: none; background: transparent;">
                {{-- Notification Header (Clickable) --}}
                <div class="notification-header" @click="expanded = !expanded" style="display: flex; gap: 12px; cursor: pointer;">
                    @php
                        $person = $tab === 'masuk' ? $t->creator : $t->assignee;
                    @endphp
                    @if($tab !== 'mandiri')
                    <div style="flex-shrink: 0;">
                        @if($person && $person->foto)
                            <img src="{{ Storage::url($person->foto) }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary-800); color: white; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700;">
                                {{ substr($person->nama ?? '?', 0, 1) }}
                            </div>
                        @endif
                    </div>
                    @endif
                    <div style="flex-grow: 1; min-width: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2px;">
                            <strong style="color: var(--text-900); font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                @if($tab !== 'mandiri')
                                    <span style="font-weight: 500; color: var(--text-500); font-size: 12px;">{{ $tab === 'masuk' ? 'From: ' : 'To: ' }}</span>{{ $person->nama ?? '-' }}
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
                            @if($tab !== 'mandiri') <span style="font-weight: 600; color: var(--text-800);">{{ $t->judul }}</span> - @endif {{ $t->deskripsi }}
                        </div>
                        <div style="font-size: 11px; color: var(--text-400);">
                            {{ $t->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                {{-- Expanded Card Body --}}
                <div x-show="expanded" x-transition style="display: none; margin-top: 16px; padding-top: 16px; border-top: 1px dashed var(--border-200);">
                    <div class="task-card-info-grid">
                        <div class="task-card-info-item">
                            <span class="task-card-info-label">Mulai</span>
                            <span class="task-card-info-value">{{ $t->tgl_mulai->format('d M Y') }}</span>
                        </div>
                        <div class="task-card-info-item">
                            <span class="task-card-info-label">Deadline</span>
                            <span class="task-card-info-value {{ $t->is_overdue ? 'overdue' : '' }}">
                                {{ $t->tgl_selesai->format('d M Y') }}
                                @if($t->is_overdue) <i class="bi bi-exclamation-triangle"></i> @endif
                            </span>
                        </div>
                        <div class="task-card-info-item">
                            <span class="task-card-info-label">Bobot</span>
                            <span class="task-card-info-value">{{ $t->bobot }}</span>
                        </div>
                        <div class="task-card-info-item">
                            <span class="task-card-info-label">Prioritas</span>
                            <span class="task-card-info-value">
                                @php $prioClass = $t->prioritas === 'Tinggi' ? 'prio-tinggi' : ($t->prioritas === 'Rendah' ? 'prio-rendah' : 'prio-sedang'); @endphp
                                <span class="prio-badge {{ $prioClass }}" style="font-size:10px;">{{ $t->prioritas }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- Laporan --}}
                    @if($tab === 'delegasi')
                        @if($t->laporan)
                            <div class="task-card-report">
                                <div class="task-card-report-text">
                                    <i class="bi bi-check2"></i> {{ \Illuminate\Support\Str::limit($t->laporan, 80) }}
                                </div>
                                @if($t->file_laporan)
                                    <a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" class="task-card-report-link">
                                        <i class="bi bi-file-earmark-text"></i> Lihat Lampiran
                                    </a>
                                @endif
                            </div>
                            @if($t->status === 'Menunggu Review')
                                <form action="{{ route('tasks.review', $t->id) }}" method="POST">
                                    @csrf
                                    <div class="task-card-review-actions">
                                        <button type="submit" name="action" value="approve" class="btn btn-sm"><i class="bi bi-check-circle"></i> Setujui</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger" onclick="return confirm('Minta pegawai merevisi laporan ini?');"><i class="bi bi-arrow-return-left"></i> Revisi</button>
                                    </div>
                                </form>
                            @endif
                        @else
                            <div class="task-card-no-report">Belum ada laporan dari bawahan</div>
                        @endif
                    @else
                        {{-- Mobile VIEW AS ASSIGNEE --}}
                        @if($t->status === 'Selesai')
                            <div class="task-card-report">
                                <div class="task-card-report-text"><i class="bi bi-check2"></i> Terkirim: {{ \Illuminate\Support\Str::limit($t->laporan, 80) }}</div>
                                @if($t->file_laporan)
                                    <a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" class="task-card-report-link"><i class="bi bi-file-earmark-text"></i> Lihat Lampiran</a>
                                @endif
                            </div>
                        @elseif($t->status === 'Menunggu Review')
                            <div class="task-card-report">
                                <div class="task-card-report-text" style="color:#1E40AF;"><i class="bi bi-hourglass-split"></i> Menunggu Review Pimpinan</div>
                                @if($t->file_laporan)
                                    <a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" class="task-card-report-link"><i class="bi bi-file-earmark-text"></i> Lihat Lampiran</a>
                                @endif
                            </div>
                        @else
                            <form action="{{ route('tasks.report', $t->id) }}" method="POST" enctype="multipart/form-data" style="background:var(--bg-app); border:1px dashed var(--border-300); border-radius:var(--radius-md); padding:12px; margin-bottom:12px; display:flex; flex-direction:column; gap:8px;">
                                @csrf
                                <input type="text" name="laporan" placeholder="Tulis hasil singkat..." value="{!! $t->status === 'Revisi' ? $t->laporan : '' !!}" required style="width:100%; padding:10px 12px; font-size:13px; border:1px solid var(--border-200); border-radius:var(--radius-sm); outline:none;">
                                <div style="display:flex; gap:8px; align-items:center;">
                                    <input type="file" name="file_laporan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="font-size:11px; flex-shrink:0; max-width:160px;">
                                    <button type="submit" class="btn btn-sm" style="flex-grow:1;">Kirim Laporan</button>
                                </div>
                            </form>
                        @endif
                    @endif

                    {{-- Catatan / Notes --}}
                    <details>
                        <summary class="task-card-notes-toggle"><i class="bi bi-chat-dots"></i> Riwayat Catatan ({{ $t->comments->count() }})</summary>
                        <div class="task-card-notes-content">
                            @foreach($t->comments as $c)
                                <div class="note-item">
                                    <div class="note-header">
                                        <span class="note-author">{{ $c->user->nama }}</span>
                                        <span class="note-time">{{ $c->created_at->format('d M H:i') }}</span>
                                    </div>
                                    <div class="note-text">{{ $c->komentar }}</div>
                                </div>
                            @endforeach
                            <form action="{{ route('tasks.comments.store', $t->id) }}" method="POST" class="note-form">
                                @csrf
                                <input type="text" name="komentar" placeholder="Tulis catatan..." required>
                                <button class="btn btn-sm" style="padding:8px 14px;">Kirim</button>
                            </form>
                        </div>
                    </details>

                    {{-- Card Footer / Actions --}}
                    <div class="task-card-footer" style="margin-top: 16px;">
                        @if($t->is_overdue)
                            <span style="color:#E53E3E; font-size:11px; font-weight:700;"><i class="bi bi-exclamation-triangle"></i> Terlambat</span>
                        @else
                            <span></span>
                        @endif
                        <div class="task-card-actions">
                            @if($tab === 'delegasi')
                                <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $t->id }}, {{ \Illuminate\Support\Js::from(['judul' => $t->judul, 'deskripsi' => $t->deskripsi, 'prioritas' => $t->prioritas, 'assigned_to' => $t->assigned_to, 'bobot' => $t->bobot, 'tgl_mulai' => $t->tgl_mulai->format('Y-m-d'), 'tgl_selesai' => $t->tgl_selesai->format('Y-m-d')]) }})"><i class="bi bi-pencil"></i> Edit</button>
                                <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Tarik kembali/hapus tugas ini?');" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @else
                                <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $t->id }}, {{ \Illuminate\Support\Js::from(['judul' => $t->judul, 'deskripsi' => $t->deskripsi, 'prioritas' => $t->prioritas, 'bobot' => $t->bobot, 'tgl_mulai' => $t->tgl_mulai->format('Y-m-d'), 'tgl_selesai' => $t->tgl_selesai->format('Y-m-d'), 'assigned_to' => $t->assigned_to]) }})">👁️ Detail</button>
                                @if($tab === 'mandiri')
                                    <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tugas mandiri ini?');" style="margin: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon"><i class="bi bi-card-checklist"></i></div>
                <p>Belum ada tugas yang didelegasikan. Tekan "Buat Tugas Baru" untuk memulai.</p>
            </div>
            @endforelse
        </div>

        {{-- Desktop Pagination --}}
        <div class="desktop-pagination" style="margin-top: 16px;">
            {{ $tasks->links() }}
        </div>
        
        {{-- Mobile Pagination --}}
        <div class="pagination-mobile">
            <span class="page-info">Page {{ $tasks->currentPage() }} / {{ $tasks->lastPage() }}</span>
            <div class="page-nav-buttons">
                @if($tasks->onFirstPage())
                    <span class="disabled"><i class="bi bi-chevron-left"></i> Prev</span>
                @else
                    <a href="{{ $tasks->appends(['tab' => request('tab', 'delegasi')])->previousPageUrl() }}"><i class="bi bi-chevron-left"></i> Prev</a>
                @endif
                @if($tasks->hasMorePages())
                    <a href="{{ $tasks->appends(['tab' => request('tab', 'delegasi')])->nextPageUrl() }}">Next <i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="disabled">Next <i class="bi bi-chevron-right"></i></span>
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
                <h3>{!! $tab === 'delegasi' ? '<i class="bi bi-pencil"></i> Edit Tugas Pegawai' : '👁️ Detail Tugas' !!}</h3>
                <button type="button" class="modal-close" @click="editModalOpen = false">×</button>
            </div>
            <form :action="'{{ url('/tasks') }}/' + editId" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Judul Pekerjaan / Tugas</label>
                    <input type="text" name="judul" x-model="editData.judul" {!! $tab === 'delegasi' ? 'required' : 'readonly' !!}>
                </div>
                <div class="form-group">
                    <label>Deskripsi Detail Tugas</label>
                    <textarea name="deskripsi" rows="3" x-model="editData.deskripsi" {!! $tab === 'delegasi' ? 'required' : 'readonly' !!}></textarea>
                </div>
                <div class="form-group">
                    <label>Prioritas</label>
                    <select name="prioritas" x-model="editData.prioritas" {!! $tab === 'delegasi' ? 'required' : 'disabled' !!}>
                        <option value="Sedang">Sedang</option>
                        <option value="Tinggi">Tinggi</option>
                        <option value="Rendah">Rendah</option>
                    </select>
                </div>
                @if($tab === 'delegasi')
                <div class="form-group">
                    <label>Pegawai yang Ditugaskan</label>
                    <select name="assigned_to" x-model="editData.assigned_to" required>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->unitKerja->nama_unit ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="form-group">
                    <label>Bobot Pekerjaan (1 – 100)</label>
                    <input type="number" name="bobot" min="1" max="100" x-model="editData.bobot" {!! $tab === 'delegasi' ? 'required' : 'readonly' !!}>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai & Deadline</label>
                    <div class="modal-form-dates">
                        <input type="date" name="tgl_mulai" x-model="editData.tgl_mulai" {!! $tab === 'delegasi' ? 'required' : 'readonly' !!}>
                        <input type="date" name="tgl_selesai" x-model="editData.tgl_selesai" {!! $tab === 'delegasi' ? 'required' : 'readonly' !!}>
                    </div>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" @click="editModalOpen = false">Batal</button>
                    @if($tab === 'delegasi')
                    <button type="submit" class="btn"><i class="bi bi-floppy"></i> Simpan Perubahan</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
