<?php
$indexView = file_get_contents(__DIR__ . '/resources/views/dashboard/index.blade.php');
$indexView = str_replace('<h2>Dashboard Utama</h2>', '<h2>Dashboard Overview & Statistik</h2>', $indexView);
$indexView = str_replace('<p>Ringkasan statistik sistem dan monitor kegiatan (TV Signage View)</p>', '<p>Ringkasan performa dan jadwal kegiatan seluruh divisi</p>', $indexView);
file_put_contents(__DIR__ . '/resources/views/dashboard/index.blade.php', $indexView);
echo "Dashboard index updated.\n";
