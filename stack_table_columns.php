<?php
$adminBladePath = __DIR__ . '/resources/views/dashboard/admin.blade.php';
$adminBlade = file_get_contents($adminBladePath);

// 1. Replace headers
$oldHeaders = <<<'EOD'
            <thead>
                <tr>
                    <th>Kegiatan</th>
                    <th>Unit Penyelenggara</th>
                    <th>Lokasi</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Peserta Terlibat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
EOD;

$newHeaders = <<<'EOD'
            <thead>
                <tr>
                    <th>Detail Kegiatan</th>
                    <th>Waktu Pelaksanaan</th>
                    <th>Peserta Terlibat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
EOD;

$adminBlade = str_replace($oldHeaders, $newHeaders, $adminBlade);

// 2. Replace tbody row content
// Wait, replacing exact HTML can be tricky with formatting. Let's use regex or specific str_replace blocks.
$oldRowBody = <<<'EOD'
                    <td><strong>{{ $keg->nama_kegiatan }}</strong></td>
                    <td>{{ $keg->unitKerja->nama_unit ?? '-' }}</td>
                    <td>{{ $keg->lokasi->nama_lokasi ?? '-' }}</td>
                    <td>{{ $keg->waktu_mulai->format('d M Y, H:i') }}</td>
                    <td style="color:var(--text-500);">{{ $keg->waktu_selesai->format('d M Y, H:i') }}</td>
EOD;

$newRowBody = <<<'EOD'
                    <td>
                        <div style="font-weight:700; color:var(--text-900); font-size:13.5px; margin-bottom:4px;">{{ $keg->nama_kegiatan }}</div>
                        <div style="font-size:11.5px; color:var(--primary-500); font-weight:600; margin-bottom:2px;">🏢 {{ $keg->unitKerja->nama_unit ?? '-' }}</div>
                        <div style="font-size:11.5px; color:var(--text-500);">📍 {{ $keg->lokasi->nama_lokasi ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-size:12.5px; font-weight:600; color:var(--text-700);">Mulai: {{ $keg->waktu_mulai->format('d M Y, H:i') }}</div>
                        <div style="font-size:12px; color:var(--text-500); margin-top:3px;">Selesai: {{ $keg->waktu_selesai->format('d M Y, H:i') }}</div>
                    </td>
EOD;

$adminBlade = str_replace($oldRowBody, $newRowBody, $adminBlade);
file_put_contents($adminBladePath, $adminBlade);
echo "Table columns stacked successfully.\n";
