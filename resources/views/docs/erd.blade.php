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
    <div class="flow-title"><i class="bi bi-link"></i> Relasi Logika Antar Tabel</div>
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
        <div class="erd-table-header purple"><i class="bi bi-tag"></i> roles</div>
        <div class="erd-field"><span class="field-name"><i class="bi bi-key-fill"></i> id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama_role</span><span class="type">VARCHAR(50)</span></div>
        <div class="erd-field"><span class="field-name">keterangan</span><span class="type">TEXT</span></div>
    </div>

    <!-- Tabel unit_kerja -->
    <div class="erd-table-card">
        <div class="erd-table-header"><i class="bi bi-building"></i> unit_kerja</div>
        <div class="erd-field"><span class="field-name"><i class="bi bi-key-fill"></i> id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">parent_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → unit_kerja.id</span></div>
        <div class="erd-field"><span class="field-name">nama_unit</span><span class="type">VARCHAR(100)</span></div>
        <div class="erd-field"><span class="field-name">kode_unit</span><span class="type">VARCHAR(20)</span></div>
        <div class="erd-field"><span class="field-name">kepala_unit_id</span><span class="type">INT <span class="key-badge key-fk">FK</span></span></div>
    </div>

    <!-- Tabel users -->
    <div class="erd-table-card">
        <div class="erd-table-header"><i class="bi bi-person"></i> users</div>
        <div class="erd-field"><span class="field-name"><i class="bi bi-key-fill"></i> id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama</span><span class="type">VARCHAR(100)</span></div>
        <div class="erd-field"><span class="field-name">email</span><span class="type">VARCHAR(255) UNIQUE</span></div>
        <div class="erd-field"><span class="field-name">username</span><span class="type">VARCHAR(50) UNIQUE</span></div>
        <div class="erd-field"><span class="field-name">password</span><span class="type">VARCHAR(255)</span></div>
        <div class="erd-field"><span class="field-name">google_id</span><span class="type">VARCHAR(255)</span></div>
        <div class="erd-field"><span class="field-name">foto</span><span class="type">VARCHAR(255)</span></div>
        <div class="erd-field"><span class="field-name">role_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → roles.id</span></div>
        <div class="erd-field"><span class="field-name">unit_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → unit_kerja.id</span></div>
        <div class="erd-field"><span class="field-name">created_at</span><span class="type">TIMESTAMP</span></div>
    </div>

    <!-- Tabel lokasi_kegiatan -->
    <div class="erd-table-card">
        <div class="erd-table-header amber"><i class="bi bi-geo-alt-fill"></i> lokasi_kegiatan</div>
        <div class="erd-field"><span class="field-name"><i class="bi bi-key-fill"></i> id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama_lokasi</span><span class="type">VARCHAR(150)</span></div>
        <div class="erd-field"><span class="field-name">alamat</span><span class="type">TEXT</span></div>
    </div>

    <!-- Tabel jenis_kegiatan -->
    <div class="erd-table-card">
        <div class="erd-table-header amber"><i class="bi bi-tag"></i> jenis_kegiatan</div>
        <div class="erd-field"><span class="field-name"><i class="bi bi-key-fill"></i> id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">nama_jenis</span><span class="type">VARCHAR(100)</span></div>
        <div class="erd-field"><span class="field-name">keterangan</span><span class="type">TEXT</span></div>
    </div>

    <!-- Tabel kegiatan -->
    <div class="erd-table-card">
        <div class="erd-table-header teal"><i class="bi bi-calendar-event"></i> kegiatan</div>
        <div class="erd-field"><span class="field-name"><i class="bi bi-key-fill"></i> id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
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
        <div class="erd-table-header teal"><i class="bi bi-link"></i> kegiatan_peserta</div>
        <div class="erd-field"><span class="field-name"><i class="bi bi-key-fill"></i> id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">kegiatan_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → kegiatan.id</span></div>
        <div class="erd-field"><span class="field-name">user_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → users.id</span></div>
    </div>

    <!-- Tabel tasks (To-Do) -->
    <div class="erd-table-card" style="border: 2px solid var(--teal-500);">
        <div class="erd-table-header teal"><i class="bi bi-check-circle"></i> tasks (To-Do List)</div>
        <div class="erd-field"><span class="field-name"><i class="bi bi-key-fill"></i> id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">judul</span><span class="type">VARCHAR(200)</span></div>
        <div class="erd-field"><span class="field-name">deskripsi</span><span class="type">TEXT</span></div>
        <div class="erd-field"><span class="field-name">prioritas</span><span class="type">ENUM('Tinggi','Sedang','Rendah')</span></div>
        <div class="erd-field"><span class="field-name">bobot</span><span class="type">INT (1-100)</span></div>
        <div class="erd-field"><span class="field-name">tgl_mulai</span><span class="type">DATE</span></div>
        <div class="erd-field"><span class="field-name">tgl_selesai</span><span class="type">DATE</span></div>
        <div class="erd-field"><span class="field-name">status</span><span class="type">ENUM('Berlangsung','Menunggu Review','Revisi','Selesai')</span></div>
        <div class="erd-field"><span class="field-name">laporan</span><span class="type">TEXT</span></div>
        <div class="erd-field"><span class="field-name">file_laporan</span><span class="type">VARCHAR(255)</span></div>
        <div class="erd-field"><span class="field-name">sumber</span><span class="type">ENUM('Pimpinan','Mandiri')</span></div>
        <div class="erd-field"><span class="field-name">created_by <span class="key-badge key-fk">FK</span></span><span class="type">INT → users.id</span></div>
        <div class="erd-field"><span class="field-name">assigned_to <span class="key-badge key-fk">FK</span></span><span class="type">INT → users.id</span></div>
        <div class="erd-field"><span class="field-name">created_at</span><span class="type">TIMESTAMP</span></div>
    </div>

    <!-- Tabel task_comments -->
    <div class="erd-table-card" style="border: 2px solid var(--teal-500);">
        <div class="erd-table-header teal"><i class="bi bi-chat-dots"></i> task_comments</div>
        <div class="erd-field"><span class="field-name"><i class="bi bi-key-fill"></i> id <span class="key-badge key-pk">PK</span></span><span class="type">INT AUTO_INCREMENT</span></div>
        <div class="erd-field"><span class="field-name">task_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → tasks.id</span></div>
        <div class="erd-field"><span class="field-name">user_id <span class="key-badge key-fk">FK</span></span><span class="type">INT → users.id</span></div>
        <div class="erd-field"><span class="field-name">komentar</span><span class="type">TEXT</span></div>
        <div class="erd-field"><span class="field-name">created_at</span><span class="type">TIMESTAMP</span></div>
    </div>
</div>
@endsection