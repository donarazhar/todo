@extends('layouts.app')

@section('page_title')
    <h2>My To-Do List & Laporan</h2>
    <p>Daftar tugas dari pimpinan dan tugas mandiri Anda &mdash; {{ Auth::user()->nama }} ({{ Auth::user()->unitKerja->nama_unit ?? '' }})</p>
@endsection

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

@if($tab === 'mandiri')
<div class="section-box">
    <div style="display:flex; justify-content:space-between; align-items:center; cursor:pointer;" @click="formOpen = !formOpen">
        <div>
            <h3 class="section-title" style="margin-bottom: 6px;"><span class="title-icon">✏️</span> Tambah To-Do Mandiri</h3>
            <p style="font-size:12px; color:var(--text-500); margin:0;">Buat tugas mandiri untuk mencatat pekerjaan pribadi Anda.</p>
        </div>
        <button type="button" class="btn btn-sm btn-secondary" x-text="formOpen ? 'Tutup Form ▴' : 'Buat Tugas Mandiri ▾'"></button>
    </div>
    <div x-show="formOpen" x-transition style="display: none; margin-top: 20px;">
        <form action="{{ route('tasks.store') }}" method="POST" style="display:flex; flex-wrap:wrap; gap: 20px;">
            @csrf
            <div style="flex: 7; min-width: 250px; display:flex; flex-direction:column; gap:15px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Judul Pekerjaan</label>
                    <input type="text" name="judul" required>
                </div>
                <div class="form-group" style="margin-bottom:0; display:flex; flex-direction:column; flex-grow:1;">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3" style="border:1px solid var(--border-200); border-radius:6px; padding:10px; font-family:inherit; font-size:13px; outline:none; resize:none; flex-grow:1;" onfocus="this.style.borderColor='var(--primary-400)'" onblur="this.style.borderColor='var(--border-200)'" required></textarea>
                </div>
            </div>
            <div style="flex: 3; min-width: 200px; display:flex; flex-direction:column; gap:15px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Prioritas</label>
                    <select name="prioritas" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border-200); border-radius: var(--radius-md); font-size: 13.5px; outline: none;">
                        <option value="Sedang">🟡 Sedang</option>
                        <option value="Tinggi">🔴 Tinggi</option>
                        <option value="Rendah">🟢 Rendah</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Bobot</label>
                    <input type="number" name="bobot" min="1" max="100" value="30" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Mulai</label>
                    <input type="date" name="tgl_mulai" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Selesai</label>
                    <input type="date" name="tgl_selesai" required>
                </div>
            </div>
            <button type="submit" class="btn" style="width:100%;">➕ Tambah To-Do Mandiri</button>
        </form>
    </div>
</div>
@endif

<div class="section-box">
    <h3 class="section-title" style="margin-bottom: 16px;">
        <span class="title-icon">{{ $tab === 'pimpinan' ? '👑' : '👤' }}</span> 
        Daftar {{ $tab === 'pimpinan' ? 'Delegasi Pimpinan' : 'To-Do Mandiri' }}
    </h3>

    <div style="overflow-x: auto; width: 100%;">
        <table style="min-width: 700px;">
            <thead>
                <tr>
                    <th>Detail Tugas</th>
                    <th>Bobot</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Laporan / Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $t)
                <tr>
                    <td>
                        <strong>{{ $t->judul }}</strong><br>
                        <small style="color:var(--text-500);">{{ $t->deskripsi }}</small>
                        <div style="margin-top: 4px;">
                            @php
                                $badgeColor = $t->prioritas === 'Tinggi' ? 'background: #FEE2E2; color: #991B1B;' : 
                                             ($t->prioritas === 'Rendah' ? 'background: #D1FAE5; color: #065F46;' : 'background: #FEF3C7; color: #92400E;');
                            @endphp
                            <span style="padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; {{ $badgeColor }}">Prio: {{ $t->prioritas }}</span>
                        </div>
                    </td>
                    <td><strong>{{ $t->bobot }}</strong></td>
                    <td>
                        {{ $t->tgl_selesai->format('d M Y') }}
                        @if($t->is_overdue)
                            <br><span style="color:#E53E3E; font-size:11px; font-weight:700;">⚠️ Terlambat</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusBg = 'bg-proses';
                            if($t->status === 'Selesai') $statusBg = 'bg-selesai';
                            elseif($t->status === 'Menunggu Review') $statusBg = 'bg-proses'; // or custom color
                            elseif($t->status === 'Revisi') $statusBg = 'bg-belum'; // actually red might be better, but bg-belum uses pending colors
                        @endphp
                        <span class="badge {{ $statusBg }}" {!! $t->status === 'Revisi' ? 'style="background:#FEE2E2; color:#991B1B;"' : ($t->status === 'Menunggu Review' ? 'style="background:#DBEAFE; color:#1E40AF;"' : '') !!}>
                            {{ $t->status }}
                        </span>
                    </td>
                    <td>
                        @if($t->status === 'Selesai')
                            <div style="margin-bottom: 8px;">
                                <span style="color:var(--teal-600); font-weight:600; font-size:12px;">✔ Terkirim: {{ $t->laporan }}</span>
                                @if($t->file_laporan)
                                    <br><a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" style="font-size:11px; color:var(--primary-600); text-decoration:none;">📄 Lihat Lampiran</a>
                                @endif
                            </div>
                        @elseif($t->status === 'Menunggu Review')
                            <div style="margin-bottom: 8px;">
                                <span style="color:#1E40AF; font-weight:600; font-size:12px;">⏳ Menunggu Pimpinan</span><br>
                                <small style="color:var(--text-500);">Laporan: {{ $t->laporan }}</small>
                                @if($t->file_laporan)
                                    <br><a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" style="font-size:11px; color:var(--primary-600); text-decoration:none;">📄 Lihat Lampiran</a>
                                @endif
                            </div>
                        @else
                            @if($t->status === 'Revisi')
                                <div style="margin-bottom: 4px;">
                                    <span style="color:#E53E3E; font-weight:600; font-size:11px;">⚠️ Pimpinan meminta revisi laporan!</span>
                                </div>
                            @endif
                            <form action="{{ route('tasks.report', $t->id) }}" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:5px; margin-bottom: 8px; background:var(--bg-50); padding:8px; border-radius:6px; border:1px dashed var(--border-300);">
                                @csrf
                                <input type="text" name="laporan" placeholder="Tulis hasil singkat..." value="{{ $t->status === 'Revisi' ? $t->laporan : '' }}" required style="padding: 6px; font-size: 12px; border:1px solid var(--border-200); border-radius:4px; outline:none;" onfocus="this.style.borderColor='var(--primary-400)'" onblur="this.style.borderColor='var(--border-200)'">
                                <div style="display:flex; gap:5px; align-items:center;">
                                    <input type="file" name="file_laporan" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="font-size:11px; width:140px;">
                                    <button type="submit" class="btn btn-sm" style="flex-grow:1;">{{ $t->status === 'Revisi' ? 'Kirim Revisi' : 'Kirim Laporan' }}</button>
                                </div>
                            </form>
                        @endif

                        @if($t->comments->count() > 0 || $t->status === 'Revisi')
                        <details style="margin-bottom: 8px;">
                            <summary style="font-size: 11.5px; font-weight:600; cursor: pointer; color: var(--primary-600); padding:4px 0;">💬 Riwayat Catatan ({{ $t->comments->count() }})</summary>
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
                            <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $t->id }}, { judul: '{{ addslashes($t->judul) }}', deskripsi: '{{ addslashes($t->deskripsi) }}', prioritas: '{{ $t->prioritas }}', bobot: '{{ $t->bobot }}', tgl_mulai: '{{ $t->tgl_mulai->format('Y-m-d') }}', tgl_selesai: '{{ $t->tgl_selesai->format('Y-m-d') }}', assigned_to: '{{ $t->assigned_to }}' })">✏️ Edit</button>
                            
                            @if($tab === 'mandiri')
                            <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tugas mandiri ini?');" style="margin: 0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tasks->appends(['tab' => request('tab')])->links() }}
    </div>
</div>

<!-- ============================
     EDIT MODAL (ALPINE JS)
============================ -->
<div class="modal-overlay" :class="{ 'show': editModalOpen }" x-show="editModalOpen" style="display: none;" x-transition>
    <div class="modal-box" @click.away="editModalOpen = false">
        <div class="modal-header">
            <h3>✏️ Edit Tugas</h3>
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
                <textarea name="deskripsi" rows="3" x-model="editData.deskripsi"></textarea>
            </div>
            <div class="form-group">
                <label>Prioritas</label>
                <select name="prioritas" x-model="editData.prioritas" required style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border-200); border-radius: var(--radius-md); font-size: 13.5px; outline: none;">
                    <option value="Sedang">🟡 Sedang</option>
                    <option value="Tinggi">🔴 Tinggi</option>
                    <option value="Rendah">🟢 Rendah</option>
                </select>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label>Bobot (1–100)</label>
                    <input type="number" name="bobot" min="1" max="100" x-model="editData.bobot" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" x-model="editData.tgl_mulai" required>
                </div>
            </div>
            <div class="form-group">
                <label>Deadline Penyelesaian</label>
                <input type="date" name="tgl_selesai" x-model="editData.tgl_selesai" required>
            </div>
            <input type="hidden" name="assigned_to" x-model="editData.assigned_to">
            
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" @click="editModalOpen = false">Batal</button>
                <button type="submit" class="btn">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection