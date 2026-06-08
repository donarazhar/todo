<?php
$file = __DIR__ . '/resources/views/dashboard.blade.php';
$content = file_get_contents($file);

// 1. Remove login screen HTML precisely.
// In the original file, it's between `<div id="login-screen">` and `<!-- ============================================ MAIN APP LAYOUT ============================================ -->`
// Wait, the main layout is `<!-- ============================================ MAIN APP LAYOUT ============================================ --> \n <div id="app-layout">`
$startPos = strpos($content, '<div id="login-screen">');
$endPos = strpos($content, '<!-- ============================================', $startPos);

if ($startPos !== false && $endPos !== false) {
    $content = substr_replace($content, '', $startPos, $endPos - $startPos);
}

// 2. Replace static dbKegiatan
$dbKegiatanReplacement = <<<'EOD'
let dbKegiatan = {!! json_encode($kegiatans->map(function($k) {
            return [
                'id' => $k->id,
                'nama' => $k->nama_kegiatan,
                'jenis' => $k->jenis->nama_jenis ?? '',
                'unit' => $k->unitKerja->nama_unit ?? '',
                'lokasi' => $k->lokasi->nama_lokasi ?? '',
                'mulai' => $k->waktu_mulai->format('Y-m-d H:i'),
                'selesai' => $k->waktu_selesai->format('Y-m-d H:i'),
                'status' => $k->status
            ];
        })) !!};
EOD;
$content = preg_replace('/let dbKegiatan = \[.*?\];/s', $dbKegiatanReplacement, $content);

// 3. Replace static dbTasks
$dbTasksReplacement = <<<'EOD'
let dbTasks = {!! json_encode($tasks->map(function($t) {
            return [
                'id' => $t->id,
                'judul' => $t->judul,
                'deskripsi' => $t->deskripsi,
                'assigned' => $t->assignee->nama ?? '',
                'bobot' => $t->bobot,
                'mulai' => $t->tgl_mulai->format('Y-m-d'),
                'selesai' => $t->tgl_selesai->format('Y-m-d'),
                'status' => $t->status,
                'laporan' => $t->laporan,
                'sumber' => $t->sumber
            ];
        })) !!};
EOD;
$content = preg_replace('/let dbTasks = \[.*?\];/s', $dbTasksReplacement, $content);

// 4. Replace currentRole and setup init
$roleReplacement = <<<'EOD'
let currentRole = "{{ strtolower($role) }}";
        let currentUserData = {
            id: {{ $user->id }},
            nama: "{{ $user->nama }}",
            unit_id: {{ $user->unit_id ?? 'null' }}
        };
        
        // Auto set role on load instead of waiting for login
        window.addEventListener('DOMContentLoaded', () => {
            setRoleState(currentRole);
        });
EOD;
$content = preg_replace('/let currentRole = \'admin\';/s', $roleReplacement, $content);

// 5. Fix logout button
$content = str_replace('onclick="handleLogout()"', 'onclick="event.preventDefault(); document.getElementById(\'logout-form\').submit();"', $content);
$logoutForm = <<<'EOD'
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
EOD;
$content = str_replace('</body>', $logoutForm, $content);

file_put_contents($file, $content);
echo "dashboard.blade.php updated.\n";
