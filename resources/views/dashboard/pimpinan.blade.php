@extends('layouts.app')

@section('page_title')
    <h2>Delegasi Tugas — To-Do Pimpinan</h2>
    <p>Berikan tugas khusus kepada pegawai dan monitor progres penyelesaiannya</p>
@endsection

@push('styles')
<style>
    /* ============================
       PIMPINAN PAGE – RESPONSIVE STYLES
    ============================ */

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
    }

    /* ============================
       SMALL MOBILE (≤ 480px)
    ============================ */
    @media (max-width: 480px) {
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
         SECTION: FORM DELEGASI TUGAS
    ================================= --}}
    <div class="section-box">
        <div class="task-form-toggle" @click="formOpen = !formOpen">
            <div class="task-form-toggle-info">
                <h3 class="section-title"><span class="title-icon"><i class="bi bi-pencil-square"></i></span> Delegasikan Tugas Baru</h3>
                <p>Tugas yang Anda buat akan otomatis muncul di dashboard pegawai yang ditugaskan.</p>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" x-text="formOpen ? 'Tutup Form ▴' : 'Buat Tugas Baru ▾'"></button>
        </div>
        <form action="{{ route('tasks.store') }}" method="POST" x-show="formOpen" x-transition style="display: none;">
            @csrf
            <div class="task-form-grid">
                <div>
                    <div class="form-group">
                        <label>Judul Pekerjaan / Tugas</label>
                        <input type="text" name="judul" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Detail Tugas</label>
                        <textarea name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Prioritas</label>
                        <select name="prioritas" required>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Rendah">Rendah</option>
                        </select>
                    </div>
                </div>
                <div>
                    <div class="task-form-row">
                        <div class="form-group">
                            <label>Pegawai yang Ditugaskan</label>
                            <select name="assigned_to" required>
                                @foreach($pegawais as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bobot (1–100)</label>
                            <input type="number" name="bobot" min="1" max="100" value="50" required>
                        </div>
                    </div>
                    <div class="task-form-row">
                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Deadline Penyelesaian</label>
                            <input type="date" name="tgl_selesai" required>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn task-form-submit"><i class="bi bi-send"></i> Kirim Tugas Sekarang</button>
        </form>
    </div>

    {{-- ================================
         SECTION: MONITORING KERJA
    ================================= --}}
    <div class="section-box">
        <h3 class="section-title" style="margin-bottom: 18px;"><span class="title-icon"><i class="bi bi-bar-chart-line"></i></span> Monitoring Kerja Pegawai</h3>

        {{-- ======= DESKTOP TABLE VIEW ======= --}}
        <div class="task-table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Detail Tugas</th>
                    <th>Waktu Pelaksanaan</th>
                    <th>Status & Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($delegasiTasks as $t)
                <tr>
                    <td>
                        <div style="font-weight:700; color:var(--text-900); font-size:13.5px; margin-bottom:4px;">{{ $t->judul }}</div>
                        <div style="font-size:11.5px; color:var(--primary-500); font-weight:600; margin-bottom:2px;"><i class="bi bi-person"></i> {{ $t->assignee->nama ?? '-' }} &nbsp;&bull;&nbsp; Bobot: {{ $t->bobot }}</div>
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
                            <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $t->id }}, { judul: '{{ addslashes($t->judul) }}', deskripsi: '{{ addslashes($t->deskripsi) }}', prioritas: '{{ $t->prioritas }}', assigned_to: '{{ $t->assigned_to }}', bobot: '{{ $t->bobot }}', tgl_mulai: '{{ $t->tgl_mulai->format('Y-m-d') }}', tgl_selesai: '{{ $t->tgl_selesai->format('Y-m-d') }}' })"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Tarik kembali/hapus tugas ini?');" style="margin: 0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
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
            @forelse($delegasiTasks as $t)
            <div class="task-card">
                {{-- Card Header --}}
                <div class="task-card-header">
                    <div class="task-card-title">{{ $t->judul }}</div>
                    <div class="task-card-meta">
                        <span><i class="bi bi-person"></i> {{ $t->assignee->nama ?? '-' }}</span>
                        <span>•</span>
                        <span>Bobot: {{ $t->bobot }}</span>
                    </div>
                    @if($t->deskripsi)
                        <div class="task-card-desc">{{ \Illuminate\Support\Str::limit($t->deskripsi, 100) }}</div>
                    @endif
                    <div class="task-card-badges">
                        @php
                            $prioClass = $t->prioritas === 'Tinggi' ? 'prio-tinggi' : ($t->prioritas === 'Rendah' ? 'prio-rendah' : 'prio-sedang');
                            $statusBg = 'bg-proses';
                            if($t->status === 'Selesai') $statusBg = 'bg-selesai';
                            elseif($t->status === 'Menunggu Review') $statusBg = 'bg-proses';
                            elseif($t->status === 'Revisi') $statusBg = 'bg-belum';
                        @endphp
                        <span class="prio-badge {{ $prioClass }}">{{ $t->prioritas }}</span>
                        <span class="badge {{ $statusBg }}" {!! $t->status === 'Revisi' ? 'style="background:#FEE2E2; color:#991B1B;"' : ($t->status === 'Menunggu Review' ? 'style="background:#DBEAFE; color:#1E40AF;"' : '') !!}>
                            {{ $t->status }}
                        </span>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="task-card-body">
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
                    </div>

                    {{-- Laporan --}}
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
                        <div class="task-card-no-report">Belum ada laporan dari pegawai</div>
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
                </div>

                {{-- Card Footer / Actions --}}
                <div class="task-card-footer">
                    @if($t->is_overdue)
                        <span style="color:#E53E3E; font-size:11px; font-weight:700;"><i class="bi bi-exclamation-triangle"></i> Terlambat</span>
                    @else
                        <span></span>
                    @endif
                    <div class="task-card-actions">
                        <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $t->id }}, { judul: '{{ addslashes($t->judul) }}', deskripsi: '{{ addslashes($t->deskripsi) }}', prioritas: '{{ $t->prioritas }}', assigned_to: '{{ $t->assigned_to }}', bobot: '{{ $t->bobot }}', tgl_mulai: '{{ $t->tgl_mulai->format('Y-m-d') }}', tgl_selesai: '{{ $t->tgl_selesai->format('Y-m-d') }}' })"><i class="bi bi-pencil"></i> Edit</button>
                        <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Tarik kembali/hapus tugas ini?');" style="margin: 0;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
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

        {{-- Pagination --}}
        <div style="margin-top: 16px;">
            {{ $delegasiTasks->links() }}
        </div>
    </div>

    <!-- ============================
         EDIT MODAL (ALPINE JS)
    ============================ -->
    <div class="modal-overlay" :class="{ 'show': editModalOpen }" x-show="editModalOpen" style="display: none;" x-transition>
        <div class="modal-box" @click.away="editModalOpen = false">
            <div class="modal-header">
                <h3><i class="bi bi-pencil"></i> Edit Tugas Pegawai</h3>
                <button type="button" class="modal-close" @click="editModalOpen = false">×</button>
            </div>
            <form :action="'{{ url('/tasks') }}/' + editId" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Judul Pekerjaan / Tugas</label>
                    <input type="text" name="judul" x-model="editData.judul" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi Detail Tugas</label>
                    <textarea name="deskripsi" rows="3" x-model="editData.deskripsi" required></textarea>
                </div>
                <div class="form-group">
                    <label>Prioritas</label>
                    <select name="prioritas" x-model="editData.prioritas" required>
                        <option value="Sedang">Sedang</option>
                        <option value="Tinggi">Tinggi</option>
                        <option value="Rendah">Rendah</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pegawai yang Ditugaskan</label>
                    <select name="assigned_to" x-model="editData.assigned_to" required>
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Bobot Pekerjaan (1 – 100)</label>
                    <input type="number" name="bobot" min="1" max="100" x-model="editData.bobot" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai & Deadline</label>
                    <div class="modal-form-dates">
                        <input type="date" name="tgl_mulai" x-model="editData.tgl_mulai" required>
                        <input type="date" name="tgl_selesai" x-model="editData.tgl_selesai" required>
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
