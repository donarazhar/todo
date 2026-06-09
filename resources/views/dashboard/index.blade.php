@extends('layouts.app')

@push('styles')
<style>
    /* ============================
       DASHBOARD ENHANCEMENTS
    ============================ */
    .dashboard-header {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 32px;
        animation: fadeInDown 0.6s ease-out forwards;
    }
    
    .dashboard-header h2 {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-900);
        letter-spacing: -0.03em;
        line-height: 1.2;
    }
    
    .dashboard-header p {
        font-size: 15px;
        color: var(--text-500);
        max-width: 600px;
        line-height: 1.6;
    }

    /* Enhanced Stat Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 32px;
    }
    
    .stat-card-premium {
        border-radius: var(--radius-xl);
        padding: 24px;
        color: white;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 160px;
        box-shadow: var(--shadow-md);
        animation: fadeInUp 0.6s ease-out both;
    }
    
    .stats-grid .stat-card-premium:nth-child(1) { animation-delay: 0.1s; }
    .stats-grid .stat-card-premium:nth-child(2) { animation-delay: 0.2s; }
    .stats-grid .stat-card-premium:nth-child(3) { animation-delay: 0.3s; }
    .stats-grid .stat-card-premium:nth-child(4) { animation-delay: 0.4s; }

    .stat-card-premium:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: var(--shadow-xl);
    }
    
    .stat-card-premium::before {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
        transition: transform 0.6s ease;
    }

    .stat-card-premium:hover::before {
        transform: scale(1.2);
    }

    .stat-card-premium .stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .stat-card-premium h3 {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        opacity: 0.9;
        margin-bottom: 4px;
        font-weight: 600;
    }

    .stat-card-premium .value {
        font-size: 36px;
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1;
        margin-bottom: 4px;
    }

    .sc-blue-premium { background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%); }
    .sc-amber-premium { background: linear-gradient(135deg, #B45309 0%, #F59E0B 100%); }
    .sc-teal-premium { background: linear-gradient(135deg, #0F766E 0%, #10B981 100%); }
    .sc-purple-premium { background: linear-gradient(135deg, #5B21B6 0%, #8B5CF6 100%); }

    /* Enhanced Section Boxes */
    .split-container-premium {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 32px;
        animation: fadeInUp 0.8s ease-out 0.5s both;
    }

    .section-box-premium {
        background: var(--bg-white);
        border-radius: var(--radius-xl);
        padding: 32px;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.02);
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        display: flex;
        flex-direction: column;
    }

    .section-box-premium:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08);
    }

    .section-title-premium {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-900);
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid var(--border-100);
    }

    .section-title-premium .icon-bg {
        width: 36px;
        height: 36px;
        background: var(--primary-50);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: var(--primary-600);
    }

    /* Enhanced Progress Bars */
    .progress-item {
        background: var(--bg-app);
        padding: 16px;
        border-radius: var(--radius-lg);
        margin-bottom: 16px;
        border: 1px solid var(--border-100);
        transition: all 0.3s ease;
    }

    .progress-item:hover {
        background: var(--bg-white);
        border-color: var(--primary-200);
        box-shadow: var(--shadow-sm);
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .progress-user {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .progress-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--gradient-primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
    }

    .progress-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-800);
    }

    .progress-stats {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-500);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .progress-badge {
        padding: 4px 8px;
        border-radius: var(--radius-md);
        font-size: 12px;
        font-weight: 700;
    }

    .pb-teal { background: var(--teal-50); color: var(--teal-700); }
    .pb-blue { background: var(--primary-50); color: var(--primary-700); }
    .pb-amber { background: #FEF3C7; color: #92400E; }

    .progress-bar-premium {
        height: 10px;
        background: var(--border-200);
        border-radius: var(--radius-full);
        overflow: hidden;
        position: relative;
    }

    .progress-fill-premium {
        height: 100%;
        border-radius: var(--radius-full);
        position: relative;
        width: 0; /* Will be set via inline style */
        transition: width 1.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    .progress-fill-premium::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
        animation: shimmer 2s infinite;
    }

    /* Enhanced Table */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin: 0 -32px;
        padding: 0 32px;
    }

    .table-premium {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
        margin-top: -8px;
    }

    .table-premium th {
        background: transparent;
        color: var(--text-500);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 16px;
        border: none;
        border-bottom: 2px solid var(--border-200);
    }

    .table-premium td {
        background: var(--bg-white);
        padding: 16px;
        font-size: 14px;
        color: var(--text-700);
        border-top: 1px solid var(--border-100);
        border-bottom: 1px solid var(--border-100);
        transition: background 0.3s ease;
    }

    .table-premium td:first-child {
        border-left: 1px solid var(--border-100);
        border-top-left-radius: var(--radius-lg);
        border-bottom-left-radius: var(--radius-lg);
    }

    .table-premium td:last-child {
        border-right: 1px solid var(--border-100);
        border-top-right-radius: var(--radius-lg);
        border-bottom-right-radius: var(--radius-lg);
    }

    .table-premium tr {
        box-shadow: 0 2px 4px rgba(0,0,0,0.01);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .table-premium tr:hover td {
        background: var(--primary-50);
        cursor: pointer;
    }
    
    .table-premium tr:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .keg-name {
        font-weight: 700;
        color: var(--text-900);
        margin-bottom: 4px;
        display: block;
    }

    .keg-loc {
        font-size: 12px;
        color: var(--text-500);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .badge-premium {
        padding: 6px 12px;
        border-radius: var(--radius-full);
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-premium::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
    }

    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* ============================
       RESPONSIVE BREAKPOINTS
    ============================ */
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .split-container-premium { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .dashboard-header h2 { font-size: 24px; }
        .dashboard-header p { font-size: 14px; }
        
        /* 2x2 Grid for Mobile (Clean & Professional) */
        .stats-grid { 
            grid-template-columns: repeat(2, 1fr); 
            gap: 12px;
        }
        
        .stat-card-premium {
            min-height: auto;
            padding: 16px;
            flex-direction: column;
            align-items: flex-start;
            border-radius: var(--radius-lg);
        }
        
        .stat-card-premium .stat-icon-wrap {
            width: 36px;
            height: 36px;
            font-size: 18px;
            margin-bottom: 12px;
            margin-right: 0;
            border-radius: var(--radius-sm);
        }

        .stat-card-premium .value {
            font-size: 24px;
            margin-bottom: 4px;
        }

        .stat-card-premium h3 {
            font-size: 10px;
            line-height: 1.3;
        }
        
        .stat-card-premium::before {
            top: -30px; right: -20px;
            width: 80px; height: 80px;
        }
        
        .split-container-premium {
            gap: 24px;
        }
        
        .section-box-premium {
            padding: 20px;
            border-radius: var(--radius-lg);
        }
        
        .table-responsive {
            margin: 0 -20px;
            padding: 0 20px;
        }
        
        .table-premium td {
            font-size: 13px;
            padding: 12px 10px;
        }
    }
    
    @media (max-width: 480px) {
        .progress-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        /* Mobile Card View for Tables */
        .table-premium thead {
            display: none;
        }
        .table-premium tbody tr {
            display: flex;
            flex-direction: column;
            margin-bottom: 12px;
            border: 1px solid var(--border-200);
            border-radius: var(--radius-lg);
            padding: 12px;
            background: var(--bg-white);
        }
        .table-premium tbody td {
            border: none !important;
            padding: 6px 0 !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-premium tbody td:first-child {
            flex-direction: column;
            align-items: flex-start;
            border-bottom: 1px solid var(--border-100) !important;
            padding-bottom: 10px !important;
            margin-bottom: 6px;
            border-radius: 0;
        }
        .table-premium tbody td::before {
            content: attr(data-label);
            font-weight: 700;
            font-size: 11px;
            color: var(--text-500);
            text-transform: uppercase;
        }
        .table-premium tbody td:first-child::before {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-header">
    <h2>Dashboard Overview</h2>
    @if(Auth::user()->role->nama_role === 'Admin')
        <p>Ringkasan performa dan jadwal kegiatan seluruh divisi secara real-time.</p>
    @elseif(Auth::user()->role->nama_role === 'Pimpinan')
        <p>Ringkasan performa unit kerja dan progres penyelesaian tugas pegawai Anda.</p>
    @else
        <p>Ringkasan progres kerja dan to-do list pribadi Anda.</p>
    @endif
</div>

<div class="stats-grid">
    <div class="stat-card-premium sc-blue-premium">
        <div class="stat-icon-wrap"><i class="bi bi-calendar-event"></i></div>
        <div>
            <div class="value">{{ $totalKegiatan }}</div>
            <h3>Total Kegiatan</h3>
        </div>
    </div>
    
    <div class="stat-card-premium sc-amber-premium">
        <div class="stat-icon-wrap"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="value">{{ $tugasBerlangsung }}</div>
            <h3>Tugas Berlangsung</h3>
        </div>
    </div>
    
    <div class="stat-card-premium sc-teal-premium">
        <div class="stat-icon-wrap"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="value">{{ $tugasSelesai }}</div>
            <h3>Tugas Selesai</h3>
        </div>
    </div>
    
    <div class="stat-card-premium sc-purple-premium">
        <div class="stat-icon-wrap"><i class="bi bi-lightning-charge"></i></div>
        <div>
            <div class="value">{{ $efisiensi }}%</div>
            <h3>Efisiensi Kerja</h3>
        </div>
    </div>
</div>

<div class="split-container-premium">
    @if(Auth::user()->role->nama_role !== 'Pegawai')
    <div class="section-box-premium">
        <h3 class="section-title-premium">
            <div class="icon-bg"><i class="bi bi-graph-up"></i></div>
            Progress Kerja Pegawai
        </h3>
        
        <div>
            @if(count($pegawaiProgress) > 0)
                @foreach($pegawaiProgress as $prog)
                <div class="progress-item">
                    <div class="progress-header">
                        <div class="progress-user">
                            <div class="progress-avatar">{{ substr($prog['nama'], 0, 1) }}</div>
                            <span class="progress-name">{{ $prog['nama'] }}</span>
                        </div>
                        <div class="progress-stats">
                            <span>{{ $prog['bobotSelesai'] }}/{{ $prog['totalBobot'] }}</span>
                            @php 
                                $badgeClass = $prog['persen'] >= 80 ? 'pb-teal' : ($prog['persen'] >= 40 ? 'pb-blue' : 'pb-amber'); 
                                $fillClass = $prog['persen'] >= 80 ? 'sc-teal-premium' : ($prog['persen'] >= 40 ? 'sc-blue-premium' : 'sc-amber-premium');
                            @endphp
                            <span class="progress-badge {{ $badgeClass }}">{{ $prog['persen'] }}%</span>
                        </div>
                    </div>
                    <div class="progress-bar-premium">
                        <!-- Added inline style for immediate rendering, but with transition -->
                        <div class="progress-fill-premium {{ $fillClass }}" style="width: {{ $prog['persen'] }}%"></div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-people"></i></div>
                    <p>Belum ada data progres kerja pegawai saat ini.</p>
                </div>
            @endif
        </div>
    </div>
    @endif

    <div class="section-box-premium" style="{{ Auth::user()->role->nama_role === 'Pegawai' ? 'grid-column: 1 / -1;' : '' }}">
        <h3 class="section-title-premium">
            <div class="icon-bg"><i class="bi bi-calendar-week"></i></div>
            Jadwal Kegiatan Terdekat
        </h3>
        
        <div class="table-responsive">
            @if(count($kegiatans) > 0)
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th>Informasi Kegiatan</th>
                            <th>Waktu Pelaksanaan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kegiatans as $keg)
                        <tr>
                            <td data-label="Kegiatan">
                                <span class="keg-name">{{ $keg->nama_kegiatan }}</span>
                                <span class="keg-loc"><i class="bi bi-geo-alt-fill"></i> {{ $keg->lokasi->nama_lokasi ?? 'Lokasi Belum Ditentukan' }}</span>
                            </td>
                            <td data-label="Waktu">
                                <span style="font-weight: 600; color: var(--text-800);">{{ $keg->waktu_mulai->format('d M Y') }}</span><br>
                                <span style="font-size: 13px; color: var(--text-500);">{{ $keg->waktu_mulai->format('H:i') }} WIB</span>
                            </td>
                            <td data-label="Status">
                                @php
                                    $statusClass = $keg->status == 'Selesai' ? 'bg-selesai' : ($keg->status == 'Berlangsung' ? 'bg-proses' : 'bg-belum');
                                @endphp
                                <span class="badge-premium {{ $statusClass }}">
                                    {{ $keg->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="bi bi-calendar-event"></i></div>
                    <p>Tidak ada jadwal kegiatan dalam waktu dekat.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- KALENDER INTEGRASI -->
<div class="section-box-premium" style="margin-top: 32px;">
    <div class="section-title-premium" style="margin-bottom: 16px;">
        <div class="icon-bg"><i class="bi bi-calendar-week"></i></div>
        Kalender {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
    </div>
    <div class="calendar-wrapper">
        <div class="calendar-grid" id="calendar-grid">
            <div class="calendar-day-head">Sen</div>
            <div class="calendar-day-head">Sel</div>
            <div class="calendar-day-head">Rab</div>
            <div class="calendar-day-head">Kam</div>
            <div class="calendar-day-head">Jum</div>
            <div class="calendar-day-head">Sab</div>
            <div class="calendar-day-head">Min</div>
            
            @php
                $now = \Carbon\Carbon::now();
                $startOfMonth = $now->copy()->startOfMonth();
                $daysInMonth = $now->daysInMonth;
                $startDayOfWeek = $startOfMonth->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
                
                $kegiatanMap = [];
                foreach($kegiatans as $keg) {
                    $date = $keg->waktu_mulai->format('Y-m-d');
                    if(!isset($kegiatanMap[$date])) $kegiatanMap[$date] = [];
                    $kegiatanMap[$date][] = $keg;
                }
            @endphp
            
            {{-- Empty cells before start of month --}}
            @for ($i = 1; $i < $startDayOfWeek; $i++)
                <div class="calendar-cell empty"></div>
            @endfor
            
            {{-- Days of the month --}}
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $currentDate = $now->copy()->day($day);
                    $dateStr = $currentDate->format('Y-m-d');
                    $isToday = $dateStr === \Carbon\Carbon::today()->format('Y-m-d');
                    $events = $kegiatanMap[$dateStr] ?? [];
                @endphp
                <div class="calendar-cell {{ $isToday ? 'today' : '' }}">
                    <div class="day-num">{{ $day }}</div>
                    @foreach($events as $event)
                        @php
                            $status = $event->status;
                            $eventClass = '';
                            if ($status == 'Selesai') $eventClass = 'teal';
                            elseif ($status == 'Berlangsung') $eventClass = 'amber';
                        @endphp
                        <div class="calendar-event {{ $eventClass }}" title="{{ $event->nama_kegiatan }}">
                            {{ $event->nama_kegiatan }}
                        </div>
                    @endforeach
                </div>
            @endfor
            
            {{-- Empty cells after end of month --}}
            @php
                $totalCells = ($startDayOfWeek - 1) + $daysInMonth;
                $remainingInRow = 7 - ($totalCells % 7);
                if ($remainingInRow == 7) $remainingInRow = 0;
            @endphp
            @for ($i = 0; $i < $remainingInRow; $i++)
                <div class="calendar-cell empty"></div>
            @endfor
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add subtle entrance animation for progress bars
        const fills = document.querySelectorAll('.progress-fill-premium');
        fills.forEach(fill => {
            const targetWidth = fill.style.width;
            fill.style.width = '0';
            setTimeout(() => {
                fill.style.width = targetWidth;
            }, 300);
        });
    });
</script>
@endpush