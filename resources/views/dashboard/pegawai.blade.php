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
                    <textarea name="deskripsi" rows="5" style="border:1px solid var(--border-200); border-radius:6px; padding:10px; font-family:inherit; font-size:13px; outline:none; resize:none; flex-grow:1;" onfocus="this.style.borderColor='var(--primary-400)'" onblur="this.style.borderColor='var(--border-200)'" required></textarea>
                </div>
            </div>
            <div style="flex: 3; min-width: 200px; display:flex; flex-direction:column; gap:15px;">
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
                    </td>
                    <td><strong>{{ $t->bobot }}</strong></td>
                    <td>{{ $t->tgl_selesai->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $t->status == 'Selesai' ? 'bg-selesai' : 'bg-proses' }}">
                            {{ $t->status }}
                        </span>
                    </td>
                    <td>
                        @if($t->status == 'Selesai')
                            <div style="margin-bottom: 8px;">
                                <span style="color:var(--teal-600); font-weight:600; font-size:12px;">✔ Terkirim: {{ $t->laporan }}</span>
                            </div>
                        @else
                            <form action="{{ route('tasks.report', $t->id) }}" method="POST" style="display:flex; gap:5px; margin-bottom: 8px;">
                                @csrf
                                <input type="text" name="laporan" placeholder="Tulis hasil..." required style="padding: 6px; font-size: 12px; border:1px solid var(--border-200); border-radius:4px; width: 160px; outline:none;" onfocus="this.style.borderColor='var(--primary-400)'" onblur="this.style.borderColor='var(--border-200)'">
                                <button type="submit" class="btn btn-sm">Kirim</button>
                            </form>
                        @endif

                        <div style="display:flex; gap:5px; align-items: center; white-space: nowrap;">
                            <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $t->id }}, { judul: '{{ addslashes($t->judul) }}', deskripsi: '{{ addslashes($t->deskripsi) }}', bobot: '{{ $t->bobot }}', tgl_mulai: '{{ $t->tgl_mulai->format('Y-m-d') }}', tgl_selesai: '{{ $t->tgl_selesai->format('Y-m-d') }}', assigned_to: '{{ $t->assigned_to }}' })">✏️ Edit</button>
                            
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