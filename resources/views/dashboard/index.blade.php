@extends('layouts.app')

@section('page_title')
    <!-- No page title needed here since we have a hero banner -->
@endsection

@push('styles')
<style>
    /* Reset & Base Dashboard Layout */
    .content-body {
        padding: 0 !important; /* Remove padding from content-body for full width banner */
        background: var(--bg-app);
    }
    
    .dashboard-container {
        padding-bottom: 80px;
    }

    /* ============================
       HERO BANNER
    ============================ */
    .hero-banner {
        background: linear-gradient(135deg, #0B2545 0%, #133E7C 100%);
        padding: 40px 24px 70px 24px;
        position: relative;
        overflow: hidden;
        border-bottom-left-radius: 32px;
        border-bottom-right-radius: 32px;
    }

    /* Abstract Pattern Background */
    .hero-banner::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 250px; height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        border-radius: 50%;
    }
    .hero-banner::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -20px;
        width: 200px; height: 200px;
        border: 2px solid rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .hero-abstract-shape {
        position: absolute;
        top: 20px;
        right: 40px;
        width: 150px;
        height: 150px;
        background: url('data:image/svg+xml;utf8,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="10" width="80" height="80" rx="15" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="4" transform="rotate(45 50 50)"/><circle cx="50" cy="50" r="20" fill="rgba(255,255,255,0.05)"/></svg>') no-repeat center;
        background-size: contain;
        opacity: 0.8;
        pointer-events: none;
    }

    .hero-text {
        position: relative;
        z-index: 2;
    }
    .hero-text h1 {
        font-size: 26px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 4px;
        letter-spacing: -0.02em;
    }
    .hero-text p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.8);
    }

    /* Search Bar Overlapping */
    .search-wrapper {
        margin: -25px 24px 24px 24px;
        position: relative;
        z-index: 10;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        border-radius: var(--radius-full);
    }
    .search-input {
        width: 100%;
        padding: 16px 20px 16px 48px;
        border: none;
        border-radius: var(--radius-full);
        font-size: 14px;
        color: var(--text-800);
        background: var(--bg-white);
        outline: none;
    }
    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-400);
        font-size: 18px;
    }

    /* ============================
       ICON GRID
    ============================ */
    .icon-grid-container {
        padding: 0 16px;
        margin-bottom: 24px;
    }
    .icon-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px 8px;
    }
    .icon-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        gap: 8px;
        transition: transform 0.2s ease;
    }
    .icon-item:hover {
        transform: translateY(-2px);
    }
    .icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }
    .icon-item span {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-700);
        text-align: center;
        line-height: 1.2;
    }

    /* Icon colors */
    .bg-blue-soft { background: #E0E7FF; color: #4338CA; }
    .bg-green-soft { background: #DCFCE7; color: #15803D; }
    .bg-amber-soft { background: #FEF3C7; color: #B45309; }
    .bg-purple-soft { background: #F3E8FF; color: #7E22CE; }
    .bg-rose-soft { background: #FFE4E6; color: #BE123C; }
    .bg-teal-soft { background: #CCFBF1; color: #0F766E; }

    /* ============================
       STATUS CARD (JakOne style)
    ============================ */
    .status-card-wrapper {
        padding: 0 20px;
        margin-bottom: 32px;
    }
    .status-card {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid var(--border-100);
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .status-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .status-title {
        font-size: 12px;
        color: var(--text-500);
        font-weight: 600;
    }
    .status-value {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-900);
    }
    .status-btn {
        background: #F3F4F6;
        color: #111827;
        border: none;
        padding: 8px 16px;
        border-radius: var(--radius-full);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s;
    }
    .status-btn:hover {
        background: #E5E7EB;
    }

    /* ============================
       EXPLORE BY CATEGORY (2x2 Grid)
    ============================ */
    .section-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-900);
        margin: 0 20px 16px 20px;
        letter-spacing: -0.01em;
    }
    .category-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        padding: 0 20px;
        margin-bottom: 32px;
    }
    .cat-card {
        background: var(--bg-white);
        border: 1px solid var(--border-100);
        border-radius: var(--radius-md);
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .cat-icon {
        font-size: 20px;
    }
    .cat-text {
        display: flex;
        flex-direction: column;
    }
    .cat-title {
        font-size: 12px;
        color: var(--text-500);
        font-weight: 500;
    }
    .cat-val {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-800);
    }

    /* ============================
       RECOMMENDED FOR YOU (Horizontal)
    ============================ */
    .section-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0 20px 12px 20px;
    }
    .section-header-flex h3 {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-900);
        margin: 0;
    }
    .see-all {
        font-size: 13px;
        color: var(--primary-600);
        font-weight: 600;
        text-decoration: none;
    }
    
    .vertical-list-kegiatan {
        display: flex;
        flex-direction: column;
        gap: 16px;
        padding: 0 20px 24px 20px;
    }
    
    .h-card {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-100);
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        transition: transform 0.2s;
    }
    .h-card:hover {
        transform: translateY(-2px);
    }
    .h-card-img {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, #10B981 0%, #047857 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .h-card-img i {
        font-size: 28px;
        color: rgba(255,255,255,0.8);
    }
    .h-card-img.alt-1 { background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%); }
    .h-card-img.alt-2 { background: linear-gradient(135deg, #F59E0B 0%, #B45309 100%); }
    
    .h-badge {
        background: var(--primary-50);
        color: var(--primary-700);
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 4px;
    }
    .h-card-content {
        padding: 0 16px;
        flex: 1;
    }
    .h-subtitle {
        font-size: 11px;
        color: var(--text-400);
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .h-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text-900);
        margin-bottom: 8px;
    }
    .h-desc {
        font-size: 12px;
        color: var(--text-500);
        line-height: 1.4;
    }

    /* ============================
       VERTICAL STACK (Leaderboard/Tasks)
    ============================ */
    .vertical-stack {
        padding: 0 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .v-card {
        display: flex;
        background: var(--bg-white);
        border: 1px solid var(--border-100);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .v-img {
        width: 100px;
        background: var(--primary-100);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: var(--primary-500);
    }
    .v-img.teal { background: #CCFBF1; color: #0D9488; }
    .v-img.amber { background: #FEF3C7; color: #D97706; }
    
    .v-content {
        padding: 16px;
        flex: 1;
    }
    .v-subtitle {
        font-size: 11px;
        color: var(--text-500);
        font-weight: 600;
        margin-bottom: 2px;
    }
    .v-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--text-900);
        margin-bottom: 6px;
    }
    .v-desc {
        font-size: 12px;
        color: var(--text-600);
        line-height: 1.4;
    }

    /* Desktop Adjustments */
    @media(min-width: 768px) {
        .dashboard-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .hero-banner {
            border-radius: var(--radius-xl);
            margin: 20px;
        }
        .icon-grid {
            grid-template-columns: repeat(8, 1fr);
        }
        .category-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    
    <!-- HERO BANNER -->
    <div class="hero-banner">
        <div class="hero-abstract-shape"></div>
        <div class="hero-text">
            <h1>Hello, {{ Auth::user()->nama }}!</h1>
            <p>Welcome to your {{ Auth::user()->role->nama_role }} Dashboard</p>
        </div>
    </div>

    <!-- SEARCH BAR -->
    <div class="search-wrapper">
        <i class="bi bi-search search-icon"></i>
        <input type="text" class="search-input" placeholder="Looking for features or tasks?">
    </div>

    <!-- ICON GRID -->
    <div class="icon-grid-container">
        <div class="icon-grid">
            <!-- Global Home -->
            <a href="{{ route('dashboard') }}" class="icon-item">
                <div class="icon-circle bg-blue-soft"><i class="bi bi-house-door"></i></div>
                <span>Home</span>
            </a>

            @if(Auth::user()->role->nama_role === 'Admin')
                <a href="{{ route('master.index') }}" class="icon-item">
                    <div class="icon-circle bg-amber-soft"><i class="bi bi-database"></i></div>
                    <span>Master</span>
                </a>
                <a href="{{ route('kegiatan.index') }}" class="icon-item">
                    <div class="icon-circle bg-teal-soft"><i class="bi bi-calendar-check"></i></div>
                    <span>Jadwal</span>
                </a>
                <a href="{{ route('monitoring.index') }}" class="icon-item">
                    <div class="icon-circle bg-purple-soft"><i class="bi bi-display"></i></div>
                    <span>Monitoring</span>
                </a>
                <a href="{{ route('docs.erd') }}" class="icon-item">
                    <div class="icon-circle bg-rose-soft"><i class="bi bi-diagram-3"></i></div>
                    <span>ERD Docs</span>
                </a>
                <a href="{{ route('docs.alur') }}" class="icon-item">
                    <div class="icon-circle bg-green-soft"><i class="bi bi-bezier2"></i></div>
                    <span>App Flow</span>
                </a>
            @elseif(Auth::user()->role->nama_role === 'Pimpinan')
                <a href="{{ route('pimpinan.tasks') }}" class="icon-item">
                    <div class="icon-circle bg-amber-soft"><i class="bi bi-send"></i></div>
                    <span>Delegasi</span>
                </a>
                <a href="{{ route('pimpinan.mandiri') }}" class="icon-item">
                    <div class="icon-circle bg-green-soft"><i class="bi bi-check-circle"></i></div>
                    <span>Mandiri</span>
                </a>
                <a href="{{ route('monitoring.index') }}" class="icon-item">
                    <div class="icon-circle bg-purple-soft"><i class="bi bi-display"></i></div>
                    <span>Monitoring</span>
                </a>
            @elseif(Auth::user()->role->nama_role === 'Pegawai')
                <a href="{{ route('pegawai.tasks', ['tab' => 'pimpinan']) }}" class="icon-item">
                    <div class="icon-circle bg-amber-soft"><i class="bi bi-inbox"></i></div>
                    <span>Delegasi</span>
                </a>
                <a href="{{ route('pegawai.tasks', ['tab' => 'mandiri']) }}" class="icon-item">
                    <div class="icon-circle bg-green-soft"><i class="bi bi-check-circle"></i></div>
                    <span>Mandiri</span>
                </a>
                <a href="{{ route('monitoring.index') }}" class="icon-item">
                    <div class="icon-circle bg-purple-soft"><i class="bi bi-display"></i></div>
                    <span>Monitoring</span>
                </a>
            @endif
        </div>
    </div>

    <!-- STATUS CARD -->
    <div class="status-card-wrapper">
        <div class="status-card">
            <div class="status-info">
                <span class="status-title">Work Efficiency</span>
                <span class="status-value">{{ $efisiensi }}% Completed</span>
            </div>
            <a href="#" class="status-btn">View Stats</a>
        </div>
    </div>

    <!-- EXPLORE BY CATEGORY (STATS) -->
    <h3 class="section-title">Explore Analytics</h3>
    <div class="category-grid">
        <div class="cat-card">
            <div class="cat-icon" style="color: #3B82F6;"><i class="bi bi-calendar2-event"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ $totalKegiatan }}</span>
                <span class="cat-title">Kegiatan</span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-icon" style="color: #F59E0B;"><i class="bi bi-hourglass-split"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ $tugasBerlangsung }}</span>
                <span class="cat-title">Berlangsung</span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-icon" style="color: #10B981;"><i class="bi bi-check-circle"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ $tugasSelesai }}</span>
                <span class="cat-title">Selesai</span>
            </div>
        </div>
        <div class="cat-card">
            <div class="cat-icon" style="color: #8B5CF6;"><i class="bi bi-lightning-charge"></i></div>
            <div class="cat-text">
                <span class="cat-val">{{ count($pegawaiProgress ?? []) }}</span>
                <span class="cat-title">Pegawai Aktif</span>
            </div>
        </div>
    </div>

    <!-- RECOMMENDED FOR YOU (UPCOMING EVENTS) -->
    <div class="section-header-flex">
        <h3>Recommended for You</h3>
        <a href="{{ route('monitoring.index') }}" class="see-all">See All Agenda</a>
    </div>
    <div class="vertical-list-kegiatan">
        @if(count($kegiatans) > 0)
            @foreach($kegiatans as $index => $keg)
                @php
                    $colors = ['alt-1', 'alt-2', '', 'alt-1'];
                    $colorClass = $colors[$index % 4];
                @endphp
                <div class="h-card">
                    <div class="h-card-img {{ $colorClass }}">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="h-card-content">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div class="h-subtitle">{{ $keg->waktu_mulai->format('d M Y') }}</div>
                            <div class="h-badge">{{ $keg->status }}</div>
                        </div>
                        <div class="h-title">{{ $keg->nama_kegiatan }}</div>
                        <div class="h-desc"><i class="bi bi-geo-alt"></i> {{ $keg->lokasi->nama_lokasi ?? 'Lokasi belum diatur' }}</div>
                    </div>
                </div>
            @endforeach
            
            <div style="margin-top: 16px;">
                {{ $kegiatans->links() }}
            </div>
        @else
            <div class="h-card">
                <div class="h-card-img">
                    <i class="bi bi-info-circle"></i>
                </div>
                <div class="h-card-content">
                    <div class="h-subtitle">Info</div>
                    <div class="h-title">Belum ada kegiatan</div>
                    <div class="h-desc">Saat ini tidak ada kegiatan terjadwal di sistem.</div>
                </div>
            </div>
        @endif
    </div>

    <!-- VERTICAL STACK (LEADERBOARD) -->
    @if(Auth::user()->role->nama_role !== 'Pegawai' && count($pegawaiProgress) > 0)
    <div class="section-header-flex" style="margin-top: 16px;">
        <h3>Leaderboard Pegawai</h3>
    </div>
    <div class="vertical-stack">
        @foreach(collect($pegawaiProgress)->sortByDesc('persen')->take(3) as $index => $prog)
            @php
                $colors = ['teal', 'amber', ''];
                $colorClass = $colors[$index % 3];
            @endphp
            <div class="v-card">
                <div class="v-img {{ $colorClass }}">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="v-content">
                    <div class="v-subtitle">Peringkat {{ $index + 1 }}</div>
                    <div class="v-title">{{ $prog['nama'] }}</div>
                    <div class="v-desc">Penyelesaian: {{ $prog['bobotSelesai'] }}/{{ $prog['totalBobot'] }} ({{ $prog['persen'] }}%)</div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
