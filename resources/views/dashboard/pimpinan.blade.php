@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Delegasi Tugas — To-Do Pimpinan</h2>
        <p>Berikan tugas khusus kepada pegawai dan monitor progres penyelesaiannya</p>
    </div>
</div>

<div class="split-container">
    <div class="section-box">
        <h3 class="section-title" style="margin-bottom: 6px;"><span class="title-icon">📝</span> Delegasikan Tugas Baru</h3>
            <p style="font-size:12px; color:var(--text-500); margin-bottom:16px;">Tugas yang Anda buat akan otomatis muncul di dashboard pegawai yang ditugaskan.</p>
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Judul Pekerjaan / Tugas</label>
                <input type="text" name="judul" required>
            </div>
            <div class="form-group">
                <label>Deskripsi Detail Tugas</label>
                <textarea name="deskripsi" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Pegawai yang Ditugaskan</label>
                <select name="assigned_to" required>
                    @foreach($pegawais as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Bobot Pekerjaan (1 – 100)</label>
                <input type="number" name="bobot" min="1" max="100" value="50" required>
            </div>
            <div class="form-group">
                <label>Tanggal Mulai & Deadline</label>
                <div style="display:flex; gap:10px;">
                    <input type="date" name="tgl_mulai" value="{{ date('Y-m-d') }}" required>
                    <input type="date" name="tgl_selesai" required>
                </div>
            </div>
            <button type="submit" class="btn" style="width:100%;">📤 Kirim Tugas Sekarang</button>
        </form>
    </div>

    <div class="section-box">
        <h3 class="section-title"><span class="title-icon">📊</span> Monitoring Kerja Pegawai</h3>
        <table>
            <thead>
                <tr>
                    <th>Tugas</th>
                    <th>Didelegasikan Ke</th>
                    <th>Bobot</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $t)
                <tr>
                    <td><strong>{{ $t->judul }}</strong><br><small style="color:var(--text-500);">{{ $t->deskripsi }}</small></td>
                    <td>👤 {{ $t->assignee->nama ?? '-' }}</td>
                    <td><strong>{{ $t->bobot }}</strong></td>
                    <td style="font-size:12px; color:var(--text-500);">{{ $t->tgl_selesai->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $t->status == 'Selesai' ? 'bg-selesai' : 'bg-proses' }}">
                            {{ $t->status }}
                        </span>
                    </td>
                    <td>
                        @if($t->laporan)
                            <span style="color:var(--teal-600); font-weight:600; font-size:12px;">✔ {{ $t->laporan }}</span>
                        @else
                            <span style="font-size:12px; color:var(--text-400); font-style:italic;">Belum ada laporan</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('tasks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Tarik kembali/hapus tugas ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection