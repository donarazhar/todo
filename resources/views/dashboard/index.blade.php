@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Dashboard Overview & Statistik</h2>
        @if(Auth::user()->role->nama_role === 'Admin')
            <p>Ringkasan performa dan jadwal kegiatan seluruh divisi</p>
        @elseif(Auth::user()->role->nama_role === 'Pimpinan')
            <p>Ringkasan performa unit kerja dan progres pegawai Anda</p>
        @else
            <p>Ringkasan progres kerja pribadi Anda</p>
        @endif
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card sc-blue">
        <span class="stat-icon">📅</span>
        <div class="value">{{ $totalKegiatan }}</div>
        <h3>Total Jadwal Kegiatan</h3>
    </div>
    <div class="stat-card sc-amber">
        <span class="stat-icon">⏳</span>
        <div class="value">{{ $tugasBerlangsung }}</div>
        <h3>Tugas Berlangsung</h3>
    </div>
    <div class="stat-card sc-teal">
        <span class="stat-icon">✅</span>
        <div class="value">{{ $tugasSelesai }}</div>
        <h3>Tugas Selesai</h3>
    </div>
    <div class="stat-card sc-purple">
        <span class="stat-icon">⚡</span>
        <div class="value">{{ $efisiensi }}%</div>
        <h3>Efisiensi Pengerjaan</h3>
        <div class="stat-sub">Persentase bobot tugas selesai</div>
    </div>
</div>

<div class="split-container">
    @if(Auth::user()->role->nama_role !== 'Pegawai')
    <div class="section-box">
        <h3 class="section-title"><span class="title-icon">📊</span> Progress Kerja Pegawai</h3>
        @if(count($pegawaiProgress) > 0)
            @foreach($pegawaiProgress as $prog)
            <div style="margin-bottom: 16px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                    <span style="font-size:13px; font-weight:600; color:var(--text-700);">👤 {{ $prog['nama'] }}</span>
                    <span style="font-size:12px; color:var(--text-500);">{{ $prog['bobotSelesai'] }}/{{ $prog['totalBobot'] }} bobot — <strong style="color:var(--text-900)">{{ $prog['persen'] }}%</strong></span>
                </div>
                <div class="progress-bar-wrap">
                    @php $fill = $prog['persen'] >= 80 ? 'fill-teal' : ($prog['persen'] >= 40 ? 'fill-blue' : 'fill-amber'); @endphp
                    <div class="progress-fill {{ $fill }}" style="width:{{ $prog['persen'] }}%"></div>
                </div>
            </div>
            @endforeach
        @else
            <p style="font-size:13px; color:var(--text-500); text-align:center;">Belum ada tugas terdaftar.</p>
        @endif
    </div>
    @endif

    <div class="section-box" style="{{ Auth::user()->role->nama_role === 'Pegawai' ? 'grid-column: span 2;' : '' }}">
        <h3 class="section-title"><span class="title-icon">📅</span> Jadwal Kegiatan Terdaftar</h3>
        <table>
            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Jadwal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatans as $keg)
                <tr>
                    <td><strong>{{ $keg->nama_kegiatan }}</strong><br><small>{{ $keg->lokasi->nama_lokasi ?? '-' }}</small></td>
                    <td style="font-size:12.5px; color:var(--text-500);">{{ $keg->waktu_mulai->format('d M, H:i') }}</td>
                    <td>
                        <span class="badge {{ $keg->status == 'Selesai' ? 'bg-selesai' : ($keg->status == 'Berlangsung' ? 'bg-proses' : 'bg-belum') }}">
                            {{ $keg->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection