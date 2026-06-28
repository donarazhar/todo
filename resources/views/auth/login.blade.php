<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Login ke sistem Al Azhar Task & Schedule System — Kelola tugas dan jadwal organisasi Anda dengan mudah.">
    <title>Login — Al Azhar Task & Schedule System</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-alazhar.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        :root {
            --primary-color: #0d3b66; /* Corporate dark blue */
            --primary-hover: #14508a;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --border-color: #d1d5db;
            --bg-body: #f9fafb;
            --bg-surface: #ffffff;
            --transition: all 0.2s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-font-smoothing: antialiased;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            background: var(--bg-surface);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 32px 24px;
        }

        /* Desktop enhancements */
        @media (min-width: 640px) {
            .login-wrapper {
                min-height: auto;
                border-radius: 24px;
                box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
                margin: 40px 20px;
                padding: 48px 40px;
            }
        }

        /* Header / Logo */
        .header {
            margin-bottom: 32px;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .logo-mark {
            width: 32px;
            height: 32px;
            background: var(--primary-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            font-weight: 800;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--primary-color);
            text-align: center;
        }

        .welcome-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 12px;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .welcome-subtitle {
            font-size: 13.5px;
            color: var(--text-muted);
            line-height: 1.5;
            text-align: center;
            margin: 0 auto;
        }





        /* Alert Error */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 24px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        /* Main Submit Button */
        .btn-submit {
            width: 100%;
            height: 2.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #fff;
            border: none;
            border-radius: 0.65rem;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(29,78,216,0.35);
            transition: transform 0.18s, box-shadow 0.18s, opacity 0.18s;
            margin-top: 8px;
        }

        .btn-submit:hover { 
            transform: translateY(-1px); 
            box-shadow: 0 6px 20px rgba(29,78,216,0.42); 
            color: #fff;
        }
        
        .btn-submit:active { 
            transform: translateY(0); 
            box-shadow: 0 2px 8px rgba(29,78,216,0.3); 
        }



        .terms {
            margin-top: 32px;
            font-size: 13px;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .terms a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 24px;
        }

        .footer-links a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            font-size: 13.5px;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        /* ── Modal CSS ── */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            padding: 1rem;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            background: #fff;
            width: 100%;
            max-width: 600px;
            border-radius: 1.25rem;
            padding: 1.25rem;
            transform: translateY(20px);
            transition: transform 0.3s ease;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-height: 90vh;
            overflow-y: auto;
        }

        @media (min-width: 768px) {
            .modal-content {
                padding: 2rem;
                max-width: 700px;
            }
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-header h2 {
            font-size: 1.15rem;
            color: #0f172a;
            font-weight: 700;
            margin: 0;
        }

        @media (min-width: 768px) {
            .modal-header h2 { font-size: 1.25rem; }
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
            transition: color 0.2s;
            line-height: 1;
            padding: 0;
        }

        .btn-close:hover {
            color: #ef4444;
        }

        .modal-body {
            font-size: 0.85rem;
            color: #334155;
            line-height: 1.5;
        }
        
        @media (min-width: 768px) {
            .modal-body { font-size: 0.9rem; line-height: 1.6; }
        }

        .modal-body h4 {
            color: #0f172a;
            margin: 0 0 0.25rem;
            font-size: 0.95rem;
        }

        .modal-body ul, .modal-body ol {
            padding-left: 1.25rem;
            margin-bottom: 1rem;
        }

        .modal-body p {
            margin-bottom: 1rem;
        }

        .help-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
            margin-top: 1rem;
        }

        @media (min-width: 768px) {
            .help-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .contact-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.2s;
        }

        .contact-card:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

    </style>
</head>
<body>

    <div class="login-wrapper">
        
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('logo-alazhar.png') }}" alt="YPI Al Azhar Logo" style="width: 48px; height: 48px; border-radius: 8px; object-fit: contain;">
                <div class="logo-text">
                    Al Azhar Task<span style="color: #9ca3af; font-weight: 500; margin: 0 4px;">&</span><br>
                    <span style="color: #2563eb;">Schedule System</span>
                </div>
            </div>

            <p class="welcome-subtitle" style="margin-top: -12px;">Kelola tugas dan jadwal organisasi Anda dengan lebih mudah dan cepat!</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-circle-fill" style="margin-top: 2px;"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <a href="{{ route('auth.presensi') }}" class="btn-submit" style="text-decoration: none;">
            <i class="bi bi-shield-lock" style="font-size: 1.1rem;"></i>
            Masuk via SSO PresensiGPS
        </a>



        <div class="footer-links">
            <a href="#" onclick="openModal('modal-tentang'); return false;">Tentang</a>
            <a href="#" onclick="openModal('modal-bantuan'); return false;">Bantuan</a>
            <a href="#" onclick="openModal('modal-kontak'); return false;">Kontak</a>
        </div>

        <p class="terms" style="text-align: center;">
            &copy; {{ date('Y') }} Al Azhar Task & Schedule System <br>
            Dibuat oleh DAL Army (2026)
        </p>

    </div>

<!-- Modal Tentang -->
<div class="modal-overlay" id="modal-tentang" onclick="closeModal(event, 'modal-tentang')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2>Tentang Aplikasi</h2>
            <button class="btn-close" onclick="closeModal(null, 'modal-tentang')"><i class="bi bi-x"></i></button>
        </div>
        <div class="modal-body">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <img src="{{ asset('logo-alazhar.png') }}" alt="Logo" style="width: 70px; margin-bottom: 10px;">
                <h3 style="color: #0f172a; font-weight: 700; margin-bottom: 0.25rem;">Al Azhar Task & Schedule System</h3>
                <p style="color: #64748b; font-size: 0.85rem;">Sistem Manajemen Tugas dan Penjadwalan</p>
            </div>
            <p><strong>Al Azhar Task & Schedule System</strong> adalah aplikasi manajemen tugas yang saling terintegrasi bagi unit-unit di lingkungan Yayasan Pesantren Islam (YPI) Al Azhar.</p>
            <p>Aplikasi ini memudahkan pembuatan, penugasan, dan pemantauan tugas secara *real-time* sehingga semua aktivitas organisasi menjadi lebih tertata dan terukur.</p>
            <ul>
                <li><strong>Terpusat:</strong> Seluruh laporan progres dan jadwal rapat dikelola di dalam satu sistem terintegrasi dengan identitas PresensiGPS.</li>
                <li><strong>Sistem Penugasan:</strong> Mendukung kolaborasi tim antar divisi yang berjenjang.</li>
            </ul>
            <p>Aplikasi ini dibuat dan dikembangkan pada tahun <strong>2026</strong> oleh tim pengembang <strong>DAL Army</strong>, untuk mengoptimalkan produktivitas unit.</p>
        </div>
    </div>
</div>

<!-- Modal Bantuan -->
<div class="modal-overlay" id="modal-bantuan" onclick="closeModal(event, 'modal-bantuan')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2>Bantuan & Alur Kerja</h2>
            <button class="btn-close" onclick="closeModal(null, 'modal-bantuan')"><i class="bi bi-x"></i></button>
        </div>
        <div class="modal-body">
            <p>Aplikasi ini memiliki alur kerja yang difokuskan pada manajemen tugas (TODO):</p>
            
            <div class="help-grid">
                <div>
                    <h4>1. Dashboard</h4>
                    <p style="margin-bottom:0">Ringkasan seluruh tugas, progres, dan jadwal terdekat untuk unit kerja Anda.</p>
                </div>
                <div>
                    <h4>2. Daftar Tugas</h4>
                    <p style="margin-bottom:0">Pembuatan tugas baru, delegasi ke bawahan, dan pelaporan progres masing-masing pekerjaan.</p>
                </div>
                <div>
                    <h4>3. Kalender / Jadwal</h4>
                    <p style="margin-bottom:0">Pusat penjadwalan meeting, deadline tugas, dan kegiatan operasional lainnya.</p>
                </div>
                <div>
                    <h4>4. Presensi Terintegrasi</h4>
                    <p style="margin-bottom:0">Data pegawai Anda disinkronisasi secara langsung dari sistem SSO PresensiGPS.</p>
                </div>
            </div>

            <hr style="margin: 1.5rem 0; border: 0; border-top: 1px solid #e2e8f0;">
            <p style="font-size: 0.85rem; color: #64748b;">Jika Anda mengalami kendala login, silakan gunakan menu <strong>Kontak</strong>.</p>
        </div>
    </div>
</div>

<!-- Modal Kontak -->
<div class="modal-overlay" id="modal-kontak" onclick="closeModal(event, 'modal-kontak')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2>Hubungi Kami</h2>
            <button class="btn-close" onclick="closeModal(null, 'modal-kontak')"><i class="bi bi-x"></i></button>
        </div>
        <div class="modal-body">
            <p>Jika Anda mengalami kesulitan akses, kendala teknis, atau membutuhkan bantuan terkait aplikasi ini, silakan hubungi tim dukungan kami:</p>
            
            <a href="https://wa.me/6288214740182" target="_blank" class="contact-card">
                <div class="contact-icon" style="background-color: #25D366;">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <div>
                    <strong style="display: block; color: #0f172a;">WhatsApp Support</strong>
                    <span style="color: #64748b; font-size: 0.85rem;">0882-1474-0182</span>
                </div>
            </a>

            <a href="mailto:donarazhar@gmail.com" class="contact-card">
                <div class="contact-icon" style="background-color: #ef4444;">
                    <i class="bi bi-envelope"></i>
                </div>
                <div>
                    <strong style="display: block; color: #0f172a;">Email Support</strong>
                    <span style="color: #64748b; font-size: 0.85rem;">donarazhar@gmail.com</span>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    // Modal Logic
    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden'; // Mencegah scroll di background
    }

    function closeModal(event, id) {
        if (event && event.target !== event.currentTarget) return; // Hanya tutup jika klik overlay atau tombol X
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = 'auto'; // Mengembalikan scroll
    }
</script>

</body>
</html>
