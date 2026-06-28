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
            align-items: center;
            gap: 8px;
            margin-bottom: 40px;
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
            max-width: 90%;
        }

        /* Google Login Button */
        .btn-google {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 14px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            color: var(--text-dark);
            text-decoration: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-google:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .btn-google img {
            width: 20px;
            height: 20px;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 28px 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .divider:not(:empty)::before {
            margin-right: .5em;
        }

        .divider:not(:empty)::after {
            margin-left: .5em;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 16px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 16px 16px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            color: var(--text-dark);
            transition: var(--transition);
            outline: none;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(13, 59, 102, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 18px;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--text-dark);
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
            padding: 16px;
            background: #9ca3af; /* Default gray as requested in reference, but will make it primary color on hover/focus */
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 8px;
        }

        /* If input has value, make button primary color (Alpine handles this) */
        .btn-submit.active {
            background: var(--primary-color);
            box-shadow: 0 4px 12px rgba(13, 59, 102, 0.2);
        }

        .btn-submit.active:hover {
            background: var(--primary-hover);
        }

        /* Links */
        .forgot-password {
            display: block;
            text-align: right;
            margin-top: 16px;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-color);
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
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

    <div class="login-wrapper" x-data="{ 
            username: '{{ old('username') }}', 
            password: '', 
            showPassword: false 
        }">
        
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('logo-alazhar.png') }}" alt="YPI Al Azhar Logo" style="width: 48px; height: 48px; border-radius: 8px; object-fit: contain;">
                <div class="logo-text">Al Azhar Task<span style="color: #9ca3af; font-weight: 500; margin: 0 2px;">&</span><span style="color: #2563eb;">Schedule System</span></div>
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

        <a href="{{ route('auth.presensi') }}" class="btn-google">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Using a simple lock icon or generic SSO icon instead of Google -->
                <path d="M12 17c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6-9h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM8.9 6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2H8.9V6zM18 20H6V10h12v10z" fill="#0d3b66"/>
            </svg>
            Login dengan Presensi
        </a>

        <div class="divider">Or</div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <input 
                    type="text" 
                    name="username" 
                    class="form-control" 
                    placeholder="Username/Email" 
                    x-model="username"
                    required 
                    autocomplete="username"
                >
            </div>

            <div class="form-group">
                <input 
                    :type="showPassword ? 'text' : 'password'" 
                    name="password" 
                    class="form-control" 
                    placeholder="Password" 
                    x-model="password"
                    required
                >
                <button type="button" class="password-toggle" @click="showPassword = !showPassword" aria-label="Toggle password visibility">
                    <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                </button>
            </div>

            <button 
                type="submit" 
                class="btn-submit"
                :class="{'active': username.length > 0 && password.length > 0}"
            >
                Login
            </button>

            <a href="#" class="forgot-password">Forgot Password?</a>
        </form>

        <div class="terms">
            By logging in to Tasks, you agree to all of YPI Al Azhar's <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>.
        </div>

        <div class="footer">
            Don't have an account yet? <a href="#">Sign up</a>
        </div>

    </div>

</body>
</html>
