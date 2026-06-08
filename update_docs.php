<?php

// 1. Update ERD
$erdPath = __DIR__ . '/resources/views/docs/erd.blade.php';
$erdContent = <<<'EOD'
@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Struktur Database & ERD</h2>
        <p>Blueprint arsitektur database relasional untuk aplikasi Task&Schedule</p>
    </div>
</div>

<!-- Penjelasan Relasi -->
<div class="flow-container" style="margin-bottom: 24px;">
    <div class="flow-title">🔗 Relasi Logika Antar Tabel</div>
    <div class="flow-note" style="margin-top:0;">
        <strong style="color:#38BDF8;">Alur Delegasi Tugas:</strong><br>
        Pimpinan mengisi form To-Do → Sistem melakukan <code>INSERT INTO tasks</code> dengan field <code>assigned_to</code> = ID pegawai terpilih dan <code>created_by</code> = ID pimpinan login.
        Dashboard Pegawai menampilkan data dengan query <span class="hl-green">SELECT * FROM tasks WHERE assigned_to = :session_user_id</span>.
        <br><br>
        <strong style="color:#38BDF8;">Relasi Kegiatan – Peserta:</strong><br>
        Tabel <code>kegiatan</code> terhubung ke tabel <code>kegiatan_peserta</code> (pivot many-to-many) → terhubung ke tabel <code>users</code>.
        Satu kegiatan bisa memiliki banyak peserta, dan satu user bisa hadir di banyak kegiatan.
    </div>
</div>

<!-- ERD Cards -->
<div class="erd-grid">
    <!-- Tabel roles -->
    <div class="erd-table-card">
        <div class="erd-table-header purple">🏷️ roles</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama_role</span><span class="type">VARCHAR(50)</span></div>
        <div class="erd-field"><span class="field-name">keterangan</span><span class="type">TEXT</span></div>
    </div>

    <!-- Tabel unit_kerja -->
    <div class="erd-table-card">
        <div class="erd-table-header">🏢 unit_kerja</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama_unit</span><span class="type">VARCHAR(100)</span></div>
        <div class="erd-field"><span class="field-name">kode_unit</span><span class="type">VARCHAR(20)</span></div>
        <div class="erd-field"><span class="field-name">kepala_unit_id</span><span class="type">INT <span class="key-badge key-fk">FK</span></span></div>
    </div>

    <!-- Tabel users -->
    <div class="erd-table-card">
        <div class="erd-table-header">👤 users</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama</span><span class="type">VARCHAR(100)</span></div>
        <div class="erd-field"><span class="field-name">username</span><span class="type">VARCHAR(50) UNIQUE</span></div>
        <div class="erd-field"><span class="field-name">password</span><span class="type">VARCHAR(255)</span></div>
        <div class="erd-field"><span class="field-name">role_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → roles.id</span></div>
        <div class="erd-field"><span class="field-name">unit_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → unit_kerja.id</span></div>
        <div class="erd-field"><span class="field-name">created_at</span><span class="type">TIMESTAMP</span></div>
    </div>

    <!-- Tabel lokasi_kegiatan -->
    <div class="erd-table-card">
        <div class="erd-table-header amber">📍 lokasi_kegiatan</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama_lokasi</span><span class="type">VARCHAR(150)</span></div>
        <div class="erd-field"><span class="field-name">alamat</span><span class="type">TEXT</span></div>
    </div>

    <!-- Tabel jenis_kegiatan -->
    <div class="erd-table-card">
        <div class="erd-table-header amber">🏷️ jenis_kegiatan</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama_jenis</span><span class="type">VARCHAR(100)</span></div>
        <div class="erd-field"><span class="field-name">keterangan</span><span class="type">TEXT</span></div>
    </div>

    <!-- Tabel kegiatan -->
    <div class="erd-table-card">
        <div class="erd-table-header teal">📅 kegiatan</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama_kegiatan</span><span class="type">VARCHAR(200)</span></div>
        <div class="erd-field"><span class="field-name">jenis_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → jenis_kegiatan.id</span></div>
        <div class="erd-field"><span class="field-name">unit_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → unit_kerja.id</span></div>
        <div class="erd-field"><span class="field-name">lokasi_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → lokasi_kegiatan.id</span></div>
        <div class="erd-field"><span class="field-name">waktu_mulai</span><span class="type">DATETIME</span></div>
        <div class="erd-field"><span class="field-name">waktu_selesai</span><span class="type">DATETIME</span></div>
        <div class="erd-field"><span class="field-name">status</span><span class="type">ENUM('Belum','Berlangsung','Selesai')</span></div>
        <div class="erd-field"><span class="field-name">created_by <span class="key-badge key-fk">FK</span></span><span class="type">INT → users.id</span></div>
        <div class="erd-field"><span class="field-name">created_at</span><span class="type">TIMESTAMP</span></div>
    </div>

    <!-- Tabel kegiatan_peserta (Pivot) -->
    <div class="erd-table-card">
        <div class="erd-table-header teal">🔗 kegiatan_peserta</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">kegiatan_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → kegiatan.id</span></div>
        <div class="erd-field"><span class="field-name">user_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → users.id</span></div>
    </div>

    <!-- Tabel tasks (To-Do) -->
    <div class="erd-table-card" style="border: 2px solid var(--teal-500);">
        <div class="erd-table-header teal">✅ tasks (To-Do List)</div>
        <div class="erd-field"><span class="field-name">🔑 id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">judul</span><span class="type">VARCHAR(200)</span></div>
        <div class="erd-field"><span class="field-name">deskripsi</span><span class="type">TEXT</span></div>
        <div class="erd-field"><span class="field-name">bobot</span><span class="type">INT (1-100)</span></div>
        <div class="erd-field"><span class="field-name">tgl_mulai</span><span class="type">DATE</span></div>
        <div class="erd-field"><span class="field-name">tgl_selesai</span><span class="type">DATE</span></div>
        <div class="erd-field"><span class="field-name">status</span><span class="type">ENUM('Berlangsung','Selesai')</span></div>
        <div class="erd-field"><span class="field-name">laporan</span><span class="type">TEXT</span></div>
        <div class="erd-field"><span class="field-name">sumber</span><span class="type">ENUM('Pimpinan','Mandiri')</span></div>
        <div class="erd-field"><span class="field-name">created_by <span class="key-badge key-fk">FK</span></span><span class="type">INT → users.id</span></div>
        <div class="erd-field"><span class="field-name">assigned_to <span class="key-badge key-fk">FK</span></span><span class="type">INT → users.id</span></div>
        <div class="erd-field"><span class="field-name">created_at</span><span class="type">TIMESTAMP</span></div>
    </div>
</div>
@endsection
EOD;
file_put_contents($erdPath, $erdContent);


// 2. Update Alur
$alurPath = __DIR__ . '/resources/views/docs/alur.blade.php';
$alurContent = <<<'EOD'
@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h2>Alur Aplikasi & Workflow</h2>
        <p>Diagram alur proses utama pada sistem Task&Schedule</p>
    </div>
</div>

<!-- Alur 1: Login & Autentikasi -->
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
            <div class="step-desc">Cek ke tabel users + role</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 3</div>
            <div class="step-icon">🔀</div>
            <div class="step-label">Routing Role</div>
            <div class="step-desc">Admin / Pimpinan / Pegawai</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 4</div>
            <div class="step-icon">🏠</div>
            <div class="step-label">Dashboard</div>
            <div class="step-desc">Menu sesuai hak akses</div>
        </div>
    </div>
    <div class="flow-note">
        PHP Session menyimpan <code>$_SESSION['user_id']</code>, <code>$_SESSION['role']</code>, dan <code>$_SESSION['unit_id']</code>. Setiap halaman melakukan pengecekan middleware: jika role tidak sesuai, redirect ke halaman yang sesuai.
    </div>
</div>

<!-- Alur 2: Penjadwalan -->
<div class="flow-container" style="margin-bottom: 24px;">
    <div class="flow-title">📅 Alur 2 — Admin Membuat Penjadwalan Kegiatan</div>
    <div class="flow-steps">
        <div class="flow-step">
            <div class="step-num">Step 1</div>
            <div class="step-icon">📝</div>
            <div class="step-label">Isi Form</div>
            <div class="step-desc">Nama, unit, lokasi, waktu</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 2</div>
            <div class="step-icon">👥</div>
            <div class="step-label">Pilih Peserta</div>
            <div class="step-desc">Multi-select pegawai</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 3</div>
            <div class="step-icon">💾</div>
            <div class="step-label">Simpan DB</div>
            <div class="step-desc">INSERT kegiatan + pivot</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 4</div>
            <div class="step-icon">📱</div>
            <div class="step-label">Tampil di Kalender</div>
            <div class="step-desc">Semua peserta melihat</div>
        </div>
    </div>
    <div class="flow-note">
        Data masuk ke tabel <code>kegiatan</code> dan peserta terdaftar di tabel pivot <code>kegiatan_peserta</code>. Kalender pegawai menampilkan kegiatan dimana <span class="hl-green">user_id IN (SELECT user_id FROM kegiatan_peserta WHERE kegiatan_id = ?)</span>.
    </div>
</div>

<!-- Alur 3: Delegasi To-Do -->
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
        <div class="flow-arrow">→</div>
        <div class="flow-step" style="border-color: rgba(32, 201, 151, 0.4);">
            <div class="step-num">Step 4</div>
            <div class="step-icon">👤</div>
            <div class="step-label">Muncul di Pegawai</div>
            <div class="step-desc">Otomatis di dashboard</div>
        </div>
    </div>
    <div class="flow-note">
        <strong style="color:#FBBF24;">⚡ Fitur Kunci:</strong> Saat Pimpinan submit, sistem meng-<code>INSERT INTO tasks</code> dengan <code>created_by</code> = pimpinan, <code>assigned_to</code> = pegawai pilihan, dan <code>sumber</code> = 'Pimpinan'. Dashboard pegawai memfilter: <span class="hl-green">SELECT * FROM tasks WHERE assigned_to = :session_user_id</span>. Sehingga tugas otomatis muncul tanpa intervensi manual.
    </div>
</div>

<!-- Alur 4: Pegawai Melapor -->
<div class="flow-container">
    <div class="flow-title">✅ Alur 4 — Pegawai Menyelesaikan Tugas & Mengirim Laporan</div>
    <div class="flow-steps">
        <div class="flow-step">
            <div class="step-num">Step 1</div>
            <div class="step-icon">📋</div>
            <div class="step-label">Lihat To-Do</div>
            <div class="step-desc">Cek tugas masuk</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 2</div>
            <div class="step-icon">⚙️</div>
            <div class="step-label">Kerjakan</div>
            <div class="step-desc">Status: Berlangsung</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 3</div>
            <div class="step-icon">📄</div>
            <div class="step-label">Kirim Laporan</div>
            <div class="step-desc">Isi hasil kerja</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 4</div>
            <div class="step-icon">👁️</div>
            <div class="step-label">Pimpinan Review</div>
            <div class="step-desc">Lihat di monitoring</div>
        </div>
    </div>
    <div class="flow-note">
        Pegawai klik "Kirim Laporan" → modal muncul → isi deskripsi hasil → <code>UPDATE tasks SET status='Selesai', laporan=:isi WHERE id=:task_id</code>. Pimpinan melihat perubahan status real-time di halaman Monitoring Kerja Pegawai.
    </div>
</div>
@endsection
EOD;
file_put_contents($alurPath, $alurContent);

echo "Update complete for both docs.\n";
