@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>My To-Do List & Laporan</h2>
        <p>Daftar tugas dari pimpinan dan tugas mandiri Anda &mdash; {{ Auth::user()->nama }} ({{ Auth::user()->unitKerja->nama_unit ?? '' }})</p>
    </div>
</div>

<div class="section-box" style="margin-bottom: 24px;">
    <h3 class="section-title" style="margin-bottom: 6px;"><span class="title-icon">✏️</span> Tambah To-Do Mandiri</h3>
    <p style="font-size:12px; color:var(--text-500); margin-bottom:16px;">Buat tugas mandiri untuk mencatat pekerjaan pribadi Anda.</p>
    <form action="{{ route('tasks.store') }}" method="POST" style="display:grid; grid-template-columns: 2fr 2fr 1fr 1fr 1fr auto; gap: 12px; align-items: end;">
        @csrf
        <div class="form-group" style="margin-bottom:0;">
            <label>Judul Pekerjaan</label>
            <input type="text" name="judul" required>
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label>Deskripsi</label>
            <input type="text" name="deskripsi">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label>Bobot</label>
            <input type="number" name="bobot" min="1" max="100" value="30">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label>Mulai</label>
            <input type="date" name="tgl_mulai" value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label>Selesai</label>
            <input type="date" name="tgl_selesai">
        </div>
        <button type="submit" class="btn" style="height:40px;">➕ Tambah</button>
    </form>
</div>

<div class="section-box">
    <h3 class="section-title"><span class="title-icon">📋</span> Daftar To-Do List Saya</h3>
    <table>
        <thead>
            <tr>
                <th>Sumber</th>
                <th>Judul Tugas</th>
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
                    @if($t->sumber == 'Pimpinan')
                        <span class="badge bg-proses" style="font-size:10px;">👑 Pimpinan</span>
                    @else
                        <span class="badge bg-selesai" style="font-size:10px;">👤 Mandiri</span>
                    @endif
                </td>
                <td><strong>{{ $t->judul }}</strong><br><small>{{ $t->deskripsi }}</small></td>
                <td><strong>{{ $t->bobot }}</strong></td>
                <td>{{ $t->tgl_selesai->format('d M Y') }}</td>
                <td>
                    <span class="badge {{ $t->status == 'Selesai' ? 'bg-selesai' : 'bg-proses' }}">
                        {{ $t->status }}
                    </span>
                </td>
                <td>
                    @if($t->status == 'Selesai')
                        <span style="color:var(--teal-600); font-weight:600; font-size:12px;">✔ Terkirim: {{ $t->laporan }}</span>
                    @else
                        <form action="{{ route('tasks.report', $t->id) }}" method="POST" style="display:flex; gap:5px;">
                            @csrf
                            <input type="text" name="laporan" placeholder="Tulis hasil..." required style="padding: 6px; font-size: 12px; border:1px solid #ccc; border-radius:4px;">
                            <button type="submit" class="btn btn-sm">Kirim Laporan</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection