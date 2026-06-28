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

        .footer {
            margin-top: auto;
            padding-top: 32px;
            text-align: center;
            font-size: 14px;
            color: var(--text-dark);
        }

        .footer a {
            color: var(--primary-color);
            font-weight: 700;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
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



        <div class="terms">
            By logging in to Tasks, you agree to all of YPI Al Azhar's <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>.
        </div>



    </div>

</body>
</html>
