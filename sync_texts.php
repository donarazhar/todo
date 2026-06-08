<?php
// 1. Admin
$adminView = file_get_contents(__DIR__ . '/resources/views/dashboard/admin.blade.php');
$adminView = str_replace('<h2>Dashboard Administrator</h2>', '<h2>Manajemen Penjadwalan Kegiatan</h2>', $adminView);
$adminView = str_replace('<p>Ringkasan sistem dan penjadwalan kegiatan organisasi tingkat pusat</p>', '<p>Buat dan kelola jadwal kegiatan organisasi secara terpusat</p>', $adminView);
$adminView = str_replace('<h3 class="section-title"><span class="title-icon">➕</span> Buat Jadwal Kegiatan</h3>', '<h3 class="section-title" style="margin-bottom: 16px;"><span class="title-icon">➕</span> Buat Kegiatan Baru</h3>', $adminView);
$adminView = str_replace('<button type="submit" class="btn" style="width:100%;">Publikasikan Kegiatan</button>', '<button type="submit" class="btn" style="width:100%;">📅 Publikasikan Jadwal</button>', $adminView);
$adminView = str_replace('<h3 class="section-title"><span class="title-icon">📅</span> Jadwal Kegiatan Terdaftar</h3>', '<h3 class="section-title"><span class="title-icon">📋</span> Database Jadwal Kegiatan</h3>', $adminView);
$adminView = str_replace('<th>Unit</th>', '<th>Unit Penyelenggara</th>', $adminView);
// Remove the stat cards from Admin view, because in the original dashboard.html, the Admin Jadwal panel does not have stat cards! The stat cards are only in panel-dashboard!
$adminView = preg_replace('/<div class="stats-grid">.*?<\/div>\s*<div class="split-container">/s', '<div class="split-container">', $adminView);
file_put_contents(__DIR__ . '/resources/views/dashboard/admin.blade.php', $adminView);


// 2. Pimpinan
$pimpinanView = file_get_contents(__DIR__ . '/resources/views/dashboard/pimpinan.blade.php');
// Add the small text under Delegasi Tugas Baru
$pimpinanView = str_replace('<h3 class="section-title"><span class="title-icon">📝</span> Delegasikan Tugas Baru</h3>', '<h3 class="section-title" style="margin-bottom: 6px;"><span class="title-icon">📝</span> Delegasikan Tugas Baru</h3>
            <p style="font-size:12px; color:var(--text-500); margin-bottom:16px;">Tugas yang Anda buat akan otomatis muncul di dashboard pegawai yang ditugaskan.</p>', $pimpinanView);
$pimpinanView = str_replace('<button type="submit" class="btn" style="width:100%;">📤 Kirim Tugas</button>', '<button type="submit" class="btn" style="width:100%;">📤 Kirim Tugas Sekarang</button>', $pimpinanView);
// Update Table headers to match: Tugas, Didelegasikan Ke, Bobot, Deadline, Status, Laporan, Aksi
$pimpinanView = str_replace('<th>Bobot</th>
                    <th>Status</th>', '<th>Bobot</th>
                    <th>Deadline</th>
                    <th>Status</th>', $pimpinanView);
$pimpinanView = str_replace('<td><strong>{{ $t->bobot }}</strong></td>
                    <td>
                        <span class="badge', '<td><strong>{{ $t->bobot }}</strong></td>
                    <td style="font-size:12px; color:var(--text-500);">{{ $t->tgl_selesai->format(\'d M Y\') }}</td>
                    <td>
                        <span class="badge', $pimpinanView);
file_put_contents(__DIR__ . '/resources/views/dashboard/pimpinan.blade.php', $pimpinanView);


// 3. Pegawai
$pegawaiView = file_get_contents(__DIR__ . '/resources/views/dashboard/pegawai.blade.php');
// Header subtitle
$pegawaiView = str_replace('<p>Daftar tugas dari pimpinan dan tugas mandiri Anda</p>', '<p>Daftar tugas dari pimpinan dan tugas mandiri Anda &mdash; {{ Auth::user()->nama }} ({{ Auth::user()->unitKerja->nama_unit ?? \'\' }})</p>', $pegawaiView);
$pegawaiView = str_replace('<h3 class="section-title"><span class="title-icon">✏️</span> Tambah To-Do Mandiri</h3>', '<h3 class="section-title" style="margin-bottom: 6px;"><span class="title-icon">✏️</span> Tambah To-Do Mandiri</h3>
    <p style="font-size:12px; color:var(--text-500); margin-bottom:16px;">Buat tugas mandiri untuk mencatat pekerjaan pribadi Anda.</p>', $pegawaiView);
// Remove form grid style overrides if they interfere, actually the original used exactly what I wrote.
file_put_contents(__DIR__ . '/resources/views/dashboard/pegawai.blade.php', $pegawaiView);


// 4. Sidebar labels in app.blade.php
$appLayout = file_get_contents(__DIR__ . '/resources/views/layouts/app.blade.php');
$appLayout = str_replace('<div class="menu-section-title">Fitur Admin</div>', '<div class="menu-section-title">Fitur Administrator</div>', $appLayout);
$appLayout = str_replace('<div class="menu-icon">📅</div> Kelola Kegiatan', '<div class="menu-icon">📅</div> Jadwal Kegiatan', $appLayout);
$appLayout = str_replace('<div class="menu-icon">🏢</div> Master Data', '<div class="menu-icon">📋</div> Master Data', $appLayout);

$appLayout = str_replace('<div class="menu-icon">📊</div> Dashboard Signage', '<div class="menu-icon">📊</div> Dashboard', $appLayout);

file_put_contents(__DIR__ . '/resources/views/layouts/app.blade.php', $appLayout);

echo "Texts updated to match dashboard.html exactly.\n";
