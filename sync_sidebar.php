<?php
$appFile = __DIR__ . '/resources/views/layouts/app.blade.php';
$content = file_get_contents($appFile);

// Remove the <div class="menu-section-title">Umum</div> I added earlier because original doesn't have it
$content = str_replace('<div class="menu-section-title">Umum</div>', '', $content);

$content = str_replace('Dashboard', 'Dashboard Overview', $content);
// Wait, replacing 'Dashboard' might replace too much (like Dashboard Overview Overview). I'll use exact replacement.

// Let's rewrite the sidebar menu portion entirely to be completely safe and exact.
$startStr = '<div class="sidebar-menu">';
$endStr = '</div>
            <div class="sidebar-user">';
$startPos = strpos($content, $startStr);
$endPos = strpos($content, $endStr);

if ($startPos !== false && $endPos !== false) {
    $exactSidebar = <<<'EOT'
<div class="sidebar-menu">
                    <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="menu-icon">🏠</span>
                        <span>Dashboard Overview</span>
                    </a>

                    @if(Auth::user()->role->nama_role === 'Admin')
                    <div class="admin-feature">
                        <div class="menu-section-title">Fitur Administrator</div>
                        <a href="{{ route('master.index') }}" class="menu-item {{ request()->routeIs('master.index') ? 'active' : '' }}">
                            <span class="menu-icon">📂</span>
                            <span>Pengelolaan Master Data</span>
                        </a>
                        <a href="{{ route('kegiatan.index') }}" class="menu-item {{ request()->routeIs('kegiatan.index') ? 'active' : '' }}">
                            <span class="menu-icon">📅</span>
                            <span>Manajemen Penjadwalan</span>
                        </a>
                    </div>
                    @endif

                    @if(Auth::user()->role->nama_role === 'Pimpinan')
                    <div class="pimpinan-feature">
                        <div class="menu-section-title">Fitur Pimpinan</div>
                        <a href="{{ route('pimpinan.tasks') }}" class="menu-item {{ request()->routeIs('pimpinan.tasks') ? 'active' : '' }}">
                            <span class="menu-icon">📋</span>
                            <span>Delegasi To-Do List</span>
                        </a>
                    </div>
                    @endif

                    @if(Auth::user()->role->nama_role === 'Pegawai')
                    <div class="pegawai-feature">
                        <div class="menu-section-title">Fitur Pegawai</div>
                        <a href="{{ route('pegawai.tasks') }}" class="menu-item {{ request()->routeIs('pegawai.tasks') ? 'active' : '' }}">
                            <span class="menu-icon">✅</span>
                            <span>My To-Do & Laporan</span>
                        </a>
                    </div>
                    @endif

                    <!-- Dokumentasi -->
                    <div class="menu-section-title">Dokumentasi Teknis</div>
                    <a href="{{ route('docs.erd') }}" class="menu-item {{ request()->routeIs('docs.erd') ? 'active' : '' }}">
                        <span class="menu-icon">🧬</span>
                        <span>Database & ERD</span>
                    </a>
                    <a href="{{ route('docs.alur') }}" class="menu-item {{ request()->routeIs('docs.alur') ? 'active' : '' }}">
                        <span class="menu-icon">🔄</span>
                        <span>Alur Aplikasi</span>
                    </a>
                </div>
EOT;

    $content = substr_replace($content, $exactSidebar, $startPos, $endPos - $startPos);
}

// Brand fix
$content = str_replace('<div class="sidebar-brand-icon">📅</div>', '<div class="sidebar-brand-icon">📋</div>', $content);
$content = str_replace('<span>MANAJEMEN ORGANISASI</span>', '<span>v1.0 Mockup</span>', $content);

file_put_contents($appFile, $content);
echo "Sidebar synced exactly with dashboard.html.\n";
