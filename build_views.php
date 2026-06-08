<?php

$css = <<<'EOCSS'
<style>
        :root {
            --primary-600: #0E4D8F;
            --primary-500: #1565C0;
            --primary-400: #1E88E5;
            --primary-300: #42A5F5;
            --primary-100: #BBDEFB;
            --primary-50: #E3F2FD;
            --teal-600: #00897B;
            --teal-500: #20C997;
            --teal-400: #26D9A2;
            --teal-300: #5EEDC0;
            --teal-100: #B2F5EA;
            --teal-50: #E6FFFA;
            --gradient-primary: linear-gradient(135deg, #0B2545 0%, #13547A 100%);
            --gradient-hero: linear-gradient(135deg, #0B2545 0%, #0E4D8F 50%, #137A7F 100%);
            --gradient-teal: linear-gradient(135deg, #20C997 0%, #0E9AA7 100%);
            --gradient-card-blue: linear-gradient(135deg, #1565C0 0%, #1E88E5 100%);
            --gradient-card-teal: linear-gradient(135deg, #00897B 0%, #20C997 100%);
            --gradient-card-amber: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
            --gradient-card-purple: linear-gradient(135deg, #7C3AED 0%, #A78BFA 100%);
            --bg-app: #F0F4F8;
            --bg-sidebar: #0B2545;
            --bg-sidebar-hover: rgba(255,255,255,0.08);
            --bg-sidebar-active: rgba(255,255,255,0.14);
            --bg-white: #FFFFFF;
            --bg-card: #FFFFFF;
            --text-900: #1A202C;
            --text-700: #2D3748;
            --text-500: #718096;
            --text-400: #A0AEC0;
            --border-100: #EDF2F7;
            --border-200: #E2E8F0;
            --status-pending-bg: #EDF2F7;
            --status-pending-text: #4A5568;
            --status-active-bg: #FEF3C7;
            --status-active-text: #92400E;
            --status-done-bg: #D1FAE5;
            --status-done-text: #065F46;
            --status-danger-bg: #FEE2E2;
            --status-danger-text: #991B1B;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -1px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04);
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --radius-full: 9999px;
            --transition-fast: 0.15s ease;
            --transition-base: 0.25s ease;
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-app);
            color: var(--text-700);
            overflow: hidden;
            height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .badge {
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 11.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }
        .badge::before {
            content: ''; width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0;
        }
        .bg-belum { background: var(--status-pending-bg); color: var(--status-pending-text); }
        .bg-belum::before { background: var(--status-pending-text); }
        .bg-proses { background: var(--status-active-bg); color: var(--status-active-text); }
        .bg-proses::before { background: var(--status-active-text); }
        .bg-selesai { background: var(--status-done-bg); color: var(--status-done-text); }
        .bg-selesai::before { background: var(--status-done-text); }

        .btn {
            background: var(--gradient-teal);
            color: var(--bg-white);
            border: none;
            padding: 10px 20px;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 600;
            font-size: 13.5px;
            font-family: inherit;
            transition: all var(--transition-base);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(32, 201, 151, 0.3);
        }
        .btn-secondary { background: var(--bg-white); border: 1px solid var(--border-200); color: var(--text-700); }
        .btn-danger { background: linear-gradient(135deg, #E53E3E 0%, #FC8181 100%); }
        .btn-sm { padding: 6px 14px; font-size: 12px; }

        #app-layout { display: flex; width: 100vw; height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: 270px; min-width: 270px; background: var(--bg-sidebar); color: var(--bg-white);
            display: flex; flex-direction: column; justify-content: space-between; padding: 0;
            position: relative; z-index: 100;
        }
        .sidebar::after {
            content: ''; position: absolute; top: 0; right: 0; width: 1px; height: 100%;
            background: linear-gradient(180deg, rgba(32, 201, 151, 0.3) 0%, transparent 30%, transparent 70%, rgba(32, 201, 151, 0.3) 100%);
        }
        .sidebar-brand {
            display: flex; align-items: center; gap: 12px; padding: 22px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-brand-icon {
            width: 38px; height: 38px; background: var(--gradient-teal); border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center; font-size: 18px;
            flex-shrink: 0; box-shadow: 0 4px 12px rgba(32, 201, 151, 0.25);
        }
        .sidebar-brand h3 { font-size: 17px; font-weight: 800; margin: 0;}
        .sidebar-brand span { font-size: 10.5px; color: var(--teal-500); font-weight: 600; }

        .sidebar-menu { flex-grow: 1; padding: 12px 12px; overflow-y: auto; }
        .menu-item {
            display: flex; align-items: center; gap: 11px; padding: 11px 14px; color: rgba(255,255,255,0.6);
            text-decoration: none; border-radius: var(--radius-md); font-size: 13.5px; font-weight: 500;
            margin-bottom: 2px; cursor: pointer; transition: all var(--transition-fast); position: relative;
        }
        .menu-item:hover, .menu-item.active { background: var(--bg-sidebar-hover); color: var(--bg-white); }
        .menu-item.active::before {
            content: ''; position: absolute; left: 0; top: 6px; bottom: 6px; width: 3px;
            border-radius: 0 3px 3px 0; background: var(--teal-500);
        }
        
        .sidebar-user {
            padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; gap: 12px;
        }
        .user-avatar {
            width: 38px; height: 38px; background: var(--gradient-card-blue); border: 2px solid var(--teal-500);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; flex-shrink: 0;
        }
        .btn-logout {
            margin-left: auto; width: 32px; height: 32px; border-radius: var(--radius-sm);
            border: 1px solid rgba(255,255,255,0.1); background: transparent; color: #FC8181;
            font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: all var(--transition-fast);
        }
        .btn-logout:hover { background: rgba(252,129,129,0.1); }

        .content-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }
        .top-navbar {
            height: 64px; background: var(--bg-white); border-bottom: 1px solid var(--border-200);
            display: flex; justify-content: space-between; align-items: center; padding: 0 28px; flex-shrink: 0;
        }
        .role-indicator {
            display: flex; align-items: center; gap: 10px; background: var(--primary-50);
            padding: 6px 16px; border-radius: var(--radius-full); font-size: 12.5px;
            font-weight: 600; color: var(--primary-600); border: 1px solid var(--primary-100);
        }
        .role-dot {
            width: 8px; height: 8px; border-radius: 50%; background: var(--teal-500);
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

        .content-body { padding: 28px; overflow-y: auto; flex-grow: 1; }
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
        .page-header h2 { font-size: 22px; font-weight: 800; color: var(--text-900); letter-spacing: -0.02em; margin-bottom: 4px; }
        .page-header p { font-size: 13.5px; color: var(--text-500); margin: 0;}

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
        .stat-card {
            border-radius: var(--radius-lg); padding: 22px 24px; color: white;
            position: relative; overflow: hidden; transition: transform var(--transition-base), box-shadow var(--transition-base);
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
        .sc-blue { background: var(--gradient-card-blue); }
        .sc-teal { background: var(--gradient-card-teal); }
        .sc-amber { background: var(--gradient-card-amber); }
        .sc-purple { background: var(--gradient-card-purple); }
        .stat-card .value { font-size: 32px; font-weight: 800; }
        .stat-card h3 { font-size: 11.5px; text-transform: uppercase; font-weight: 600; margin-top: 8px; margin-bottom: 0;}

        .section-box {
            background: var(--bg-card); border-radius: var(--radius-lg); padding: 24px;
            box-shadow: var(--shadow-sm); margin-bottom: 24px; border: 1px solid var(--border-100);
        }
        .section-title { font-size: 15px; font-weight: 700; color: var(--text-900); display: flex; align-items: center; gap: 8px; margin-bottom: 18px; }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: var(--bg-app); color: var(--text-500); font-size: 11px; text-transform: uppercase; padding: 12px 16px; border-bottom: 2px solid var(--border-200); }
        td { padding: 14px 16px; border-bottom: 1px solid var(--border-100); font-size: 13.5px; color: var(--text-700); }
        tr:hover td { background: rgba(237, 242, 247, 0.5); }

        .split-container { display: grid; grid-template-columns: 1fr 2fr; gap: 24px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 12.5px; font-weight: 600; margin-bottom: 6px; color: var(--text-700); }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px 14px; border: 1.5px solid var(--border-200); border-radius: var(--radius-md);
            font-size: 13.5px; font-family: inherit; outline: none; background: var(--bg-white);
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--primary-400); box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1); }
        
        .alert { padding: 12px; margin-bottom: 20px; border-radius: var(--radius-md); font-size: 13px; font-weight: 600; }
        .alert-success { background-color: var(--status-done-bg); color: var(--status-done-text); }
        .alert-error { background-color: var(--status-danger-bg); color: var(--status-danger-text); }
</style>
EOCSS;

$layoutContent = <<<EOT
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task&Schedule - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    $css
</head>
<body>
    <div id="app-layout">
        <!-- SIDEBAR NAVIGASI -->
        <div class="sidebar">
            <div>
                <div class="sidebar-brand">
                    <div class="sidebar-brand-icon">📅</div>
                    <div>
                        <h3>Task&Schedule</h3>
                        <span>MANAJEMEN ORGANISASI</span>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <a href="{{ route('dashboard') }}" class="menu-item active">
                        <div class="menu-icon">📊</div>
                        Dashboard
                    </a>
                </div>
            </div>
            
            <div class="sidebar-user">
                <div class="user-avatar" id="avatar-initial">{{ substr(Auth::user()->nama, 0, 1) }}</div>
                <div class="user-info">
                    <h4 id="user-display-name">{{ Auth::user()->nama }}</h4>
                    <span id="user-display-role">{{ Auth::user()->role->nama_role }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout" title="Logout">🚪</button>
                </form>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="content-area">
            <!-- TOP NAVBAR -->
            <div class="top-navbar">
                <div class="role-indicator">
                    <div class="role-dot"></div>
                    Akses: <span id="current-role-txt">{{ Auth::user()->role->nama_role }}</span>
                </div>
            </div>

            <!-- SCROLLABLE CONTENT BODY -->
            <div class="content-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(\$errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach (\$errors->all() as \$error)
                                <li>{{ \$error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
EOT;

$adminContent = <<<'EOT'
@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Dashboard Administrator</h2>
        <p>Ringkasan sistem dan penjadwalan kegiatan organisasi tingkat pusat</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card sc-blue">
        <span class="stat-icon">📅</span>
        <div class="value">{{ $kegiatans->count() }}</div>
        <h3>Total Kegiatan</h3>
    </div>
    <div class="stat-card sc-teal">
        <span class="stat-icon">👥</span>
        <div class="value">{{ \App\Models\User::count() }}</div>
        <h3>Total Pegawai</h3>
    </div>
</div>

<div class="split-container">
    <div class="section-box">
        <h3 class="section-title"><span class="title-icon">➕</span> Buat Jadwal Kegiatan</h3>
        <form action="{{ route('kegiatan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" required>
            </div>
            <div class="form-group">
                <label>Jenis Kegiatan</label>
                <select name="jenis_id" required>
                    @foreach(\App\Models\JenisKegiatan::all() as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->nama_jenis }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Unit Pelaksana</label>
                <select name="unit_id" required>
                    @foreach(\App\Models\UnitKerja::all() as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <select name="lokasi_id" required>
                    @foreach(\App\Models\LokasiKegiatan::all() as $lokasi)
                        <option value="{{ $lokasi->id }}">{{ $lokasi->nama_lokasi }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Waktu Mulai & Selesai</label>
                <div style="display:flex; gap:10px;">
                    <input type="datetime-local" name="waktu_mulai" required>
                    <input type="datetime-local" name="waktu_selesai" required>
                </div>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="Belum">Belum Berlangsung</option>
                    <option value="Berlangsung">Sedang Berlangsung</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn" style="width:100%;">Publikasikan Kegiatan</button>
        </form>
    </div>

    <div class="section-box">
        <h3 class="section-title"><span class="title-icon">📅</span> Jadwal Kegiatan Terdaftar</h3>
        <table>
            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Unit</th>
                    <th>Lokasi</th>
                    <th>Waktu Mulai</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatans as $keg)
                <tr>
                    <td><strong>{{ $keg->nama_kegiatan }}</strong></td>
                    <td>{{ $keg->unitKerja->nama_unit ?? '-' }}</td>
                    <td>{{ $keg->lokasi->nama_lokasi ?? '-' }}</td>
                    <td>{{ $keg->waktu_mulai->format('d M Y, H:i') }}</td>
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
EOT;

$pimpinanContent = <<<'EOT'
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
        <h3 class="section-title"><span class="title-icon">📝</span> Delegasikan Tugas Baru</h3>
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
            <button type="submit" class="btn" style="width:100%;">📤 Kirim Tugas</button>
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
                    <th>Status</th>
                    <th>Laporan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $t)
                <tr>
                    <td><strong>{{ $t->judul }}</strong><br><small style="color:var(--text-500);">{{ $t->deskripsi }}</small></td>
                    <td>👤 {{ $t->assignee->nama ?? '-' }}</td>
                    <td><strong>{{ $t->bobot }}</strong></td>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
EOT;

$pegawaiContent = <<<'EOT'
@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>My To-Do List & Laporan</h2>
        <p>Daftar tugas dari pimpinan dan tugas mandiri Anda</p>
    </div>
</div>

<div class="section-box" style="margin-bottom: 24px;">
    <h3 class="section-title"><span class="title-icon">✏️</span> Tambah To-Do Mandiri</h3>
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
EOT;

$dir = __DIR__ . '/resources/views/';
if(!is_dir($dir.'layouts')) mkdir($dir.'layouts', 0777, true);
if(!is_dir($dir.'dashboard')) mkdir($dir.'dashboard', 0777, true);

file_put_contents($dir . 'layouts/app.blade.php', $layoutContent);
file_put_contents($dir . 'dashboard/admin.blade.php', $adminContent);
file_put_contents($dir . 'dashboard/pimpinan.blade.php', $pimpinanContent);
file_put_contents($dir . 'dashboard/pegawai.blade.php', $pegawaiContent);

echo "Views generated successfully.\n";
