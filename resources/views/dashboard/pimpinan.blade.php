@extends('layouts.app')

@section('page_title')
    <h2>Delegasi Tugas — To-Do Pimpinan</h2>
    <p>Berikan tugas khusus kepada pegawai dan monitor progres penyelesaiannya</p>
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
    <div class="section-box">
        <div style="display:flex; justify-content:space-between; align-items:center; cursor:pointer;" @click="formOpen = !formOpen">
            <div>
                <h3 class="section-title" style="margin-bottom: 6px;"><span class="title-icon">📝</span> Delegasikan Tugas Baru</h3>
                <p style="font-size:12px; color:var(--text-500); margin:0;">Tugas yang Anda buat akan otomatis muncul di dashboard pegawai yang ditugaskan.</p>
            </div>
            <button type="button" class="btn btn-sm btn-secondary" x-text="formOpen ? 'Tutup Form ▴' : 'Buat Tugas Baru ▾'"></button>
        </div>
        <form action="{{ route('tasks.store') }}" method="POST" x-show="formOpen" x-transition style="margin-top: 20px; display: none;">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <div class="form-group">
                        <label>Judul Pekerjaan / Tugas</label>
                        <input type="text" name="judul" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Detail Tugas</label>
                        <textarea name="deskripsi" rows="4" required></textarea>
                    </div>
                </div>
                <div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
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
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
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
            <button type="submit" class="btn" style="width:100%; margin-top: 10px;">📤 Kirim Tugas Sekarang</button>
        </form>
    </div>

    <div class="section-box">
        <h3 class="section-title"><span class="title-icon">📊</span> Monitoring Kerja Pegawai</h3>
        <div style="overflow-x: auto; width: 100%;">
            <table style="min-width: 700px;">
                <thead>
                <tr>
                    <th>Detail Tugas</th>
                    <th>Waktu Pelaksanaan</th>
                    <th>Status & Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $t)
                <tr>
                    <td>
                        <div style="font-weight:700; color:var(--text-900); font-size:13.5px; margin-bottom:4px;">{{ $t->judul }}</div>
                        <div style="font-size:11.5px; color:var(--primary-500); font-weight:600; margin-bottom:2px;">👤 {{ $t->assignee->nama ?? '-' }} &nbsp;&bull;&nbsp; Bobot: {{ $t->bobot }}</div>
                        <div style="font-size:11.5px; color:var(--text-500);">{{ \Illuminate\Support\Str::limit($t->deskripsi, 60) }}</div>
                    </td>
                    <td>
                        <div style="font-size:12.5px; font-weight:600; color:var(--text-700);">Mulai: {{ $t->tgl_mulai->format('d M Y') }}</div>
                        <div style="font-size:12px; color:var(--text-500); margin-top:3px;">Deadline: {{ $t->tgl_selesai->format('d M Y') }}</div>
                    </td>
                    <td>
                        <div style="margin-bottom:6px;">
                            <span class="badge {{ $t->status == 'Selesai' ? 'bg-selesai' : 'bg-proses' }}">
                                {{ $t->status }}
                            </span>
                        </div>
                        <div>
                            @if($t->laporan)
                                <div style="color:var(--teal-600); font-weight:600; font-size:11px; max-width:200px; white-space:normal;">✔ {{ \Illuminate\Support\Str::limit($t->laporan, 50) }}</div>
                            @else
                                <div style="font-size:11px; color:var(--text-400); font-style:italic;">Belum ada laporan</div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="display:flex; gap:5px; align-items: center; white-space: nowrap;">
                            <button type="button" class="btn btn-sm btn-secondary" @click="openEditModal({{ $t->id }}, { judul: '{{ addslashes($t->judul) }}', deskripsi: '{{ addslashes($t->deskripsi) }}', assigned_to: '{{ $t->assigned_to }}', bobot: '{{ $t->bobot }}', tgl_mulai: '{{ $t->tgl_mulai->format('Y-m-d') }}', tgl_selesai: '{{ $t->tgl_selesai->format('Y-m-d') }}' })">✏️</button>
                            <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Tarik kembali/hapus tugas ini?');" style="margin: 0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                            </form>
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
                <h3>✏️ Edit Tugas Pegawai</h3>
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
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <input type="date" name="tgl_mulai" x-model="editData.tgl_mulai" required>
                        <input type="date" name="tgl_selesai" x-model="editData.tgl_selesai" required>
                    </div>
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