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
    <div class="flow-title"><i class="bi bi-lock-fill"></i> Alur 1 — Login & Routing Berdasarkan Role</div>
    <div class="flow-steps">
        <div class="flow-step">
            <div class="step-num">Step 1</div>
            <div class="step-icon"><i class="bi bi-person"></i></div>
            <div class="step-label">User Login</div>
            <div class="step-desc">Input username & password</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 2</div>
            <div class="step-icon"><i class="bi bi-search"></i></div>
            <div class="step-label">Verifikasi</div>
            <div class="step-desc">Cek ke tabel users + role</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 3</div>
            <div class="step-icon"><i class="bi bi-shuffle"></i></div>
            <div class="step-label">Routing Role</div>
            <div class="step-desc">Admin / Pimpinan / Pegawai</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 4</div>
            <div class="step-icon"><i class="bi bi-house"></i></div>
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
    <div class="flow-title"><i class="bi bi-calendar-event"></i> Alur 2 — Admin Membuat Penjadwalan Kegiatan</div>
    <div class="flow-steps">
        <div class="flow-step">
            <div class="step-num">Step 1</div>
            <div class="step-icon"><i class="bi bi-pencil-square"></i></div>
            <div class="step-label">Isi Form</div>
            <div class="step-desc">Nama, unit, lokasi, waktu</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 2</div>
            <div class="step-icon"><i class="bi bi-people"></i></div>
            <div class="step-label">Pilih Peserta</div>
            <div class="step-desc">Multi-select pegawai</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 3</div>
            <div class="step-icon"><i class="bi bi-floppy"></i></div>
            <div class="step-label">Simpan DB</div>
            <div class="step-desc">INSERT kegiatan + pivot</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 4</div>
            <div class="step-icon"><i class="bi bi-phone"></i></div>
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
    <div class="flow-title"><i class="bi bi-card-checklist"></i> Alur 3 — Pimpinan Mendelegasikan Tugas (Fitur Utama)</div>
    <div class="flow-steps">
        <div class="flow-step">
            <div class="step-num">Step 1</div>
            <div class="step-icon"><i class="bi bi-person-badge"></i></div>
            <div class="step-label">Pimpinan Input</div>
            <div class="step-desc">Judul, deskripsi, bobot</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 2</div>
            <div class="step-icon"><i class="bi bi-bullseye"></i></div>
            <div class="step-label">Pilih Pegawai</div>
            <div class="step-desc">Siapa yang ditugaskan</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 3</div>
            <div class="step-icon">⚡</div>
            <div class="step-label">Auto Insert</div>
            <div class="step-desc">Masuk ke tabel tasks</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 4</div>
            <div class="step-icon"><i class="bi bi-person"></i></div>
            <div class="step-label">Muncul di Pegawai</div>
            <div class="step-desc">Otomatis di dashboard</div>
        </div>
    </div>
    <div class="flow-note">
        <strong style="color:var(--warning-600);">⚡ Fitur Kunci:</strong> Saat Pimpinan submit, sistem meng-<code>INSERT INTO tasks</code> dengan <code>created_by</code> = pimpinan, <code>assigned_to</code> = pegawai pilihan, dan <code>sumber</code> = 'Pimpinan'. Dashboard pegawai memfilter: <span class="hl-green">SELECT * FROM tasks WHERE assigned_to = :session_user_id</span>. Sehingga tugas otomatis muncul tanpa intervensi manual.
    </div>
</div>

<!-- Alur 4: Pegawai Melapor -->
<div class="flow-container" style="margin-bottom: 24px;">
    <div class="flow-title"><i class="bi bi-check-circle"></i> Alur 4 — Pegawai Mengirim Laporan & Pimpinan Review</div>
    <div class="flow-steps">
        <div class="flow-step">
            <div class="step-num">Step 1</div>
            <div class="step-icon"><i class="bi bi-card-checklist"></i></div>
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
            <div class="step-icon"><i class="bi bi-file-earmark-arrow-up"></i></div>
            <div class="step-label">Kirim Laporan</div>
            <div class="step-desc">Teks & Lampiran File</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 4</div>
            <div class="step-icon">👁️</div>
            <div class="step-label">Pimpinan Review</div>
            <div class="step-desc">Setujui / Revisi</div>
        </div>
    </div>
    <div class="flow-note">
        Pegawai mengisi form hasil dan melampirkan file → <code>UPDATE tasks SET status='Menunggu Review', laporan=:isi, file_laporan=:file</code>. Pimpinan me-review: Jika disetujui → status='Selesai'. Jika ditolak → status='Revisi' dan menambahkan catatan di tabel <code>task_comments</code>.
    </div>
</div>

<!-- Alur 5: Tugas Mandiri & Hierarki Unit -->
<div class="flow-container">
    <div class="flow-title"><i class="bi bi-diagram-3"></i> Alur 5 — Tugas Mandiri Pimpinan & Filter Hierarki Unit</div>
    <div class="flow-steps">
        <div class="flow-step">
            <div class="step-num">Step 1</div>
            <div class="step-icon"><i class="bi bi-person-workspace"></i></div>
            <div class="step-label">Tugas Mandiri</div>
            <div class="step-desc">Dibuat oleh Pimpinan</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 2</div>
            <div class="step-icon"><i class="bi bi-building"></i></div>
            <div class="step-label">Tentukan Unit</div>
            <div class="step-desc">Pilih target evaluasi</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 3</div>
            <div class="step-icon"><i class="bi bi-diagram-2"></i></div>
            <div class="step-label">Filter Hierarki</div>
            <div class="step-desc">Sub-unit otomatis tampil</div>
        </div>
        <div class="flow-arrow">→</div>
        <div class="flow-step">
            <div class="step-num">Step 4</div>
            <div class="step-icon"><i class="bi bi-images"></i></div>
            <div class="step-label">Review Bukti</div>
            <div class="step-desc">Popup gambar / lampiran</div>
        </div>
    </div>
    <div class="flow-note">
        Pimpinan membuat Tugas Mandiri (untuk dieksekusi sendiri tapi menargetkan unit tertentu). Filter unit secara rekursif akan mencari seluruh <em>descendant</em> (sub-unit di bawahnya) berdasar <code>parent_id</code>. Gambar lampiran tugas/laporan dapat diklik untuk tampil langsung melalui <em>Popup Modal</em> (Alpine.js).
    </div>
</div>
@endsection
