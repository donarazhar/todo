@extends('layouts.app')

@section('page_title')
    <h2>Todo Mandiri Pegawai</h2>
    <p>Pantau daftar tugas mandiri yang dibuat dan dikerjakan sendiri oleh pegawai di unit Anda</p>
@endsection

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">
    <div class="section-box">
        <h3 class="section-title"><span class="title-icon">🎯</span> Tugas Mandiri Pegawai</h3>
        <p style="font-size:12px; color:var(--text-500); margin:0 0 16px 0;">Daftar tugas mandiri yang dibuat dan dikerjakan sendiri oleh pegawai di unit Anda.</p>
        <div style="overflow-x: auto; width: 100%;">
            <table style="min-width: 700px;">
                <thead>
                <tr>
                    <th>Detail Tugas</th>
                    <th>Waktu Pelaksanaan</th>
                    <th>Status & Laporan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mandiriTasks as $t)
                <tr>
                    <td>
                        <div style="font-weight:700; color:var(--text-900); font-size:13.5px; margin-bottom:4px;">{{ $t->judul }}</div>
                        <div style="font-size:11.5px; color:var(--primary-500); font-weight:600; margin-bottom:2px;">👤 {{ $t->assignee->nama ?? '-' }} &nbsp;&bull;&nbsp; Bobot: {{ $t->bobot }}</div>
                        <div style="font-size:11.5px; color:var(--text-500); margin-bottom: 6px;">{{ \Illuminate\Support\Str::limit($t->deskripsi, 60) }}</div>
                        <div>
                            @php
                                $badgeColor = $t->prioritas === 'Tinggi' ? 'background: #FEE2E2; color: #991B1B;' : 
                                             ($t->prioritas === 'Rendah' ? 'background: #D1FAE5; color: #065F46;' : 'background: #FEF3C7; color: #92400E;');
                            @endphp
                            <span style="padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; {{ $badgeColor }}">Prio: {{ $t->prioritas }}</span>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:12.5px; font-weight:600; color:var(--text-700);">Mulai: {{ $t->tgl_mulai->format('d M Y') }}</div>
                        <div style="font-size:12px; color:var(--text-500); margin-top:3px;">Deadline: {{ $t->tgl_selesai->format('d M Y') }}</div>
                        @if($t->is_overdue)
                            <div style="color:#E53E3E; font-size:11px; font-weight:700; margin-top:3px;">⚠️ Terlambat</div>
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
                                <div style="color:var(--teal-600); font-weight:600; font-size:11px; max-width:200px; white-space:normal;">
                                    ✔ {{ \Illuminate\Support\Str::limit($t->laporan, 50) }}
                                    @if($t->file_laporan)
                                        <br><a href="{{ asset('storage/' . $t->file_laporan) }}" target="_blank" style="font-size:11px; color:var(--primary-600); text-decoration:none;">📄 Lihat Lampiran</a>
                                    @endif
                                </div>
                            @else
                                <div style="font-size:11px; color:var(--text-400); font-style:italic;">Belum ada laporan</div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: var(--text-500); padding: 20px;">Belum ada tugas mandiri dari pegawai.</td>
                </tr>
                @endforelse
            </tbody>
            </table>
            {{ $mandiriTasks->links() }}
        </div>
    </div>
</div>
@endsection
