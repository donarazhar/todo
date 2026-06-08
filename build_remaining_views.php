<?php
$dir = __DIR__ . '/resources/views/';
if(!is_dir($dir.'dashboard')) mkdir($dir.'dashboard', 0777, true);
if(!is_dir($dir.'admin')) mkdir($dir.'admin', 0777, true);
if(!is_dir($dir.'docs')) mkdir($dir.'docs', 0777, true);

// 1. Update Layouts sidebar
$appLayout = file_get_contents($dir . 'layouts/app.blade.php');

$sidebarMenu = <<<'EOT'
                <div class="sidebar-menu">
                    <div class="menu-section-title">Umum</div>
                    <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div class="menu-icon">📊</div> Dashboard Signage
                    </a>

                    @if(Auth::user()->role->nama_role === 'Admin')
                    <div class="menu-section-title">Fitur Admin</div>
                    <a href="{{ route('kegiatan.index') }}" class="menu-item {{ request()->routeIs('kegiatan.index') ? 'active' : '' }}">
                        <div class="menu-icon">📅</div> Kelola Kegiatan
                    </a>
                    <a href="{{ route('master.index') }}" class="menu-item {{ request()->routeIs('master.index') ? 'active' : '' }}">
                        <div class="menu-icon">🏢</div> Master Data
                    </a>
                    @endif

                    @if(Auth::user()->role->nama_role === 'Pimpinan')
                    <div class="menu-section-title">Fitur Pimpinan</div>
                    <a href="{{ route('pimpinan.tasks') }}" class="menu-item {{ request()->routeIs('pimpinan.tasks') ? 'active' : '' }}">
                        <div class="menu-icon">📋</div> Delegasi To-Do List
                    </a>
                    @endif

                    @if(Auth::user()->role->nama_role === 'Pegawai')
                    <div class="menu-section-title">Fitur Pegawai</div>
                    <a href="{{ route('pegawai.tasks') }}" class="menu-item {{ request()->routeIs('pegawai.tasks') ? 'active' : '' }}">
                        <div class="menu-icon">📝</div> My To-Do & Laporan
                    </a>
                    @endif

                    <div class="menu-section-title">Dokumentasi Teknis</div>
                    <a href="{{ route('docs.erd') }}" class="menu-item {{ request()->routeIs('docs.erd') ? 'active' : '' }}">
                        <div class="menu-icon">🗄️</div> Database & ERD
                    </a>
                    <a href="{{ route('docs.alur') }}" class="menu-item {{ request()->routeIs('docs.alur') ? 'active' : '' }}">
                        <div class="menu-icon">🔄</div> Alur Aplikasi
                    </a>
                </div>
EOT;

// Replace existing sidebar-menu block
$appLayout = preg_replace('/<div class="sidebar-menu">.*?<\/div>\s*<\/div>\s*<div class="sidebar-user">/s', $sidebarMenu . "\n            </div>\n            <div class=\"sidebar-user\">", $appLayout);

// Need to also add CSS for tabbed views and docs from the original HTML
$cssAdditions = <<<'EOCSS'
        .tab-nav { display: flex; gap: 10px; margin-bottom: 24px; border-bottom: 1px solid var(--border-200); padding-bottom: 10px; }
        .tab-btn { background: transparent; border: none; padding: 10px 18px; font-size: 13.5px; font-weight: 600; color: var(--text-500); cursor: pointer; border-radius: var(--radius-md); transition: all var(--transition-fast); }
        .tab-btn:hover { background: var(--primary-50); color: var(--primary-600); }
        .tab-btn.active { background: var(--primary-100); color: var(--primary-600); }
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeSlideIn 0.3s ease; }
        @keyframes fadeSlideIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        .progress-bar-wrap { width: 100%; background: var(--border-100); border-radius: var(--radius-full); height: 8px; overflow: hidden; position: relative; }
        .progress-fill { height: 100%; border-radius: var(--radius-full); transition: width 0.8s ease; }
        .fill-teal { background: var(--teal-500); }
        .fill-blue { background: var(--primary-500); }
        .fill-amber { background: #F59E0B; }

        .erd-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .erd-table-card { background: var(--bg-white); border: 1px solid var(--border-200); border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); }
        .erd-table-header { padding: 12px 16px; font-weight: 800; font-size: 14px; background: var(--border-100); display: flex; align-items: center; gap: 8px; }
        .erd-table-header.purple { background: #F3E8FF; color: #6B21A8; }
        .erd-table-header.amber { background: #FEF3C7; color: #B45309; }
        .erd-table-header.teal { background: #E6FFFA; color: #0D9488; }
        .erd-field { display: flex; justify-content: space-between; padding: 10px 16px; border-bottom: 1px solid var(--border-100); font-size: 12.5px; }
        .erd-field:last-child { border-bottom: none; }
        .field-name { font-weight: 600; color: var(--text-900); }
        .type { color: var(--text-500); font-family: monospace; font-size: 11.5px; }
        .key-badge { font-size: 9px; padding: 2px 6px; border-radius: 4px; font-weight: 800; margin-left: 6px; }
        .key-pk { background: #FEF08A; color: #854D0E; }
        .key-fk { background: #E2E8F0; color: #475569; }

        .flow-container { background: var(--bg-white); border-radius: var(--radius-lg); padding: 24px; box-shadow: var(--shadow-sm); margin-bottom: 24px; border: 1px solid var(--border-100); border-left: 4px solid var(--teal-500); }
        .flow-title { font-size: 15px; font-weight: 800; margin-bottom: 16px; color: var(--text-900); }
        .flow-steps { display: flex; align-items: center; gap: 16px; overflow-x: auto; padding-bottom: 10px; }
        .flow-step { flex-shrink: 0; width: 140px; background: var(--bg-app); padding: 16px; border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-200); position: relative; }
        .step-num { position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: var(--text-900); color: white; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 10px; }
        .step-icon { font-size: 24px; margin-bottom: 8px; }
        .step-label { font-size: 12.5px; font-weight: 700; color: var(--text-900); margin-bottom: 4px; }
        .step-desc { font-size: 11px; color: var(--text-500); line-height: 1.4; }
        .flow-arrow { font-size: 24px; color: var(--border-300); }
        .flow-note { margin-top: 16px; padding: 12px 16px; background: #F0F9FF; border-left: 3px solid #0EA5E9; font-size: 12.5px; color: #0369A1; border-radius: 0 var(--radius-md) var(--radius-md) 0; }
        .hl-green { background: rgba(32, 201, 151, 0.15); color: #0F766E; padding: 2px 6px; border-radius: 4px; font-family: monospace; font-size: 11.5px; }
</style>
EOCSS;
$appLayout = str_replace('</style>', $cssAdditions, $appLayout);

// Add Alpine.js for simple tab switching since we removed the custom JS
$alpine = '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>';
$appLayout = str_replace('</head>', "    $alpine\n</head>", $appLayout);

file_put_contents($dir . 'layouts/app.blade.php', $appLayout);

// 2. View: Dashboard Index
$dashboardIndex = <<<'EOT'
@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Dashboard Utama</h2>
        <p>Ringkasan statistik sistem dan monitor kegiatan (TV Signage View)</p>
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

    <div class="section-box">
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
EOT;
file_put_contents($dir . 'dashboard/index.blade.php', $dashboardIndex);

// 3. View: Master Data (Admin)
$masterData = <<<'EOT'
@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Master Data Management</h2>
        <p>Pengelolaan data dasar untuk referensi tabel relasional sistem</p>
    </div>
</div>

<div class="section-box" x-data="{ tab: 'users' }">
    <div class="tab-nav">
        <button class="tab-btn" :class="{ 'active': tab === 'users' }" @click="tab = 'users'">Manajemen Pengguna</button>
        <button class="tab-btn" :class="{ 'active': tab === 'units' }" @click="tab = 'units'">Unit Kerja</button>
        <button class="tab-btn" :class="{ 'active': tab === 'lokasi' }" @click="tab = 'lokasi'">Lokasi Kegiatan</button>
        <button class="tab-btn" :class="{ 'active': tab === 'jenis' }" @click="tab = 'jenis'">Jenis Kegiatan</button>
    </div>

    <!-- TAB USERS -->
    <div class="tab-content" :class="{ 'active': tab === 'users' }">
        <table>
            <thead><tr><th>Nama</th><th>Username</th><th>Role</th><th>Unit Kerja</th></tr></thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td><strong>{{ $user->nama }}</strong></td>
                    <td><code>{{ $user->username }}</code></td>
                    <td><span class="badge bg-belum">{{ $user->role->nama_role }}</span></td>
                    <td>{{ $user->unitKerja->nama_unit ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- TAB UNIT KERJA -->
    <div class="tab-content" :class="{ 'active': tab === 'units' }">
        <table>
            <thead><tr><th>Kode Unit</th><th>Nama Unit Kerja</th></tr></thead>
            <tbody>
                @foreach($units as $unit)
                <tr>
                    <td><strong>{{ $unit->kode_unit }}</strong></td>
                    <td>{{ $unit->nama_unit }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- TAB LOKASI -->
    <div class="tab-content" :class="{ 'active': tab === 'lokasi' }">
        <table>
            <thead><tr><th>Nama Lokasi</th><th>Alamat/Keterangan</th></tr></thead>
            <tbody>
                @foreach($lokasi as $l)
                <tr>
                    <td><strong>{{ $l->nama_lokasi }}</strong></td>
                    <td>{{ $l->alamat }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- TAB JENIS -->
    <div class="tab-content" :class="{ 'active': tab === 'jenis' }">
        <table>
            <thead><tr><th>Nama Jenis Kegiatan</th><th>Keterangan</th></tr></thead>
            <tbody>
                @foreach($jenis as $j)
                <tr>
                    <td><strong>{{ $j->nama_jenis }}</strong></td>
                    <td>{{ $j->keterangan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
EOT;
file_put_contents($dir . 'admin/master-data.blade.php', $masterData);

// 4. View: ERD Docs
$erdDoc = <<<'EOT'
@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Struktur Database & ERD</h2>
        <p>Blueprint arsitektur database relasional untuk aplikasi Task&Schedule</p>
    </div>
</div>

<div class="flow-container" style="margin-bottom: 24px;">
    <div class="flow-title">🔗 Relasi Logika Antar Tabel</div>
    <div class="flow-note" style="margin-top:0;">
        <strong style="color:#38BDF8;">Alur Delegasi Tugas:</strong><br>
        Pimpinan mengisi form To-Do → Sistem melakukan <code>INSERT INTO tasks</code> dengan field <code>assigned_to</code> = ID pegawai terpilih dan <code>created_by</code> = ID pimpinan login.
        <br><br>
        <strong style="color:#38BDF8;">Relasi Kegiatan – Peserta:</strong><br>
        Tabel <code>kegiatan</code> terhubung ke tabel <code>kegiatan_user</code> (pivot many-to-many) → terhubung ke tabel <code>users</code>.
    </div>
</div>

<div class="erd-grid">
    <!-- Tabel roles -->
    <div class="erd-table-card">
        <div class="erd-table-header purple">🏷️ roles</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama_role</span><span class="type">VARCHAR(50)</span></div>
        <div class="erd-field"><span class="field-name">created_at</span><span class="type">TIMESTAMP</span></div>
    </div>

    <!-- Tabel unit_kerjas -->
    <div class="erd-table-card">
        <div class="erd-table-header">🏢 unit_kerjas</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama_unit</span><span class="type">VARCHAR(100)</span></div>
        <div class="erd-field"><span class="field-name">kode_unit</span><span class="type">VARCHAR(20)</span></div>
    </div>

    <!-- Tabel users -->
    <div class="erd-table-card">
        <div class="erd-table-header">👤 users</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama</span><span class="type">VARCHAR(100)</span></div>
        <div class="erd-field"><span class="field-name">username</span><span class="type">VARCHAR(50) UNIQUE</span></div>
        <div class="erd-field"><span class="field-name">role_id <span class="key-badge key-fk">FK</span></span><span class="type">INT</span></div>
        <div class="erd-field"><span class="field-name">unit_id <span class="key-badge key-fk">FK</span></span><span class="type">INT</span></div>
    </div>

    <!-- Tabel kegiatan -->
    <div class="erd-table-card">
        <div class="erd-table-header teal">📅 kegiatans</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama_kegiatan</span><span class="type">VARCHAR(200)</span></div>
        <div class="erd-field"><span class="field-name">status</span><span class="type">ENUM</span></div>
        <div class="erd-field"><span class="field-name">waktu_mulai</span><span class="type">DATETIME</span></div>
    </div>

    <!-- Tabel tasks -->
    <div class="erd-table-card" style="border: 2px solid var(--teal-500);">
        <div class="erd-table-header teal">✅ tasks (To-Do List)</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">judul</span><span class="type">VARCHAR(200)</span></div>
        <div class="erd-field"><span class="field-name">bobot</span><span class="type">INT</span></div>
        <div class="erd-field"><span class="field-name">status</span><span class="type">ENUM</span></div>
        <div class="erd-field"><span class="field-name">laporan</span><span class="type">TEXT</span></div>
        <div class="erd-field"><span class="field-name">assigned_to <span class="key-badge key-fk">FK</span></span><span class="type">INT</span></div>
    </div>
</div>
@endsection
EOT;
file_put_contents($dir . 'docs/erd.blade.php', $erdDoc);

// 5. View: Alur Docs
$alurDoc = <<<'EOT'
@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Alur Aplikasi & Workflow</h2>
        <p>Diagram alur proses utama pada sistem Task&Schedule</p>
    </div>
</div>

<div class="flow-container" style="margin-bottom: 24px;">
    <div class="flow-title">🔐 Alur 1 — Login & Routing Berdasarkan Role</div>
    <div class="flow-steps">
        <div class="flow-step">
            <div class="step-num">Step 1</div>
            <div class="step-icon">👤</div>
            <div class="step-label">User Login</div>
            <div class="step-desc">Input username & password</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 2</div>
            <div class="step-icon">🔍</div>
            <div class="step-label">Verifikasi</div>
            <div class="step-desc">Cek tabel users</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 3</div>
            <div class="step-icon">🔀</div>
            <div class="step-label">Akses Dashboard</div>
            <div class="step-desc">Menu sesuai Hak Akses Laravel</div>
        </div>
    </div>
</div>

<div class="flow-container" style="margin-bottom: 24px;">
    <div class="flow-title">📋 Alur 3 — Pimpinan Mendelegasikan Tugas (Fitur Utama)</div>
    <div class="flow-steps">
        <div class="flow-step" style="border-color: rgba(251, 191, 36, 0.4);">
            <div class="step-num">Step 1</div>
            <div class="step-icon">👑</div>
            <div class="step-label">Pimpinan Input</div>
            <div class="step-desc">Judul, deskripsi, bobot</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step" style="border-color: rgba(251, 191, 36, 0.4);">
            <div class="step-num">Step 2</div>
            <div class="step-icon">🎯</div>
            <div class="step-label">Pilih Pegawai</div>
            <div class="step-desc">Siapa yang ditugaskan</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step" style="border-color: rgba(32, 201, 151, 0.4);">
            <div class="step-num">Step 3</div>
            <div class="step-icon">⚡</div>
            <div class="step-label">Auto Insert</div>
            <div class="step-desc">Masuk ke tabel tasks</div>
        </div>
    </div>
</div>
@endsection
EOT;
file_put_contents($dir . 'docs/alur.blade.php', $alurDoc);

echo "New Views and Layout updated successfully.\n";
