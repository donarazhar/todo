<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login ke sistem Task&Schedule — Kelola tugas dan jadwal organisasi Anda dengan mudah.">
    <title>Login — Task&Schedule</title>
    <link rel="icon" type="image/png" href="{{ asset('app-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* ============================
           DESIGN TOKENS
        ============================ */
        :root {
            --primary-900: #0B2545;
            --primary-800: #0C3461;
            --primary-700: #0D3F78;
            --primary-600: #0E4D8F;
            --primary-500: #1565C0;
            --primary-400: #1E88E5;
            --primary-300: #42A5F5;
            --primary-200: #90CAF9;
            --primary-100: #BBDEFB;
            --primary-50: #E3F2FD;

            --teal-700: #1E40AF;
            --teal-600: #1D4ED8;
            --teal-500: #2563EB;
            --teal-400: #3B82F6;
            --teal-300: #60A5FA;
            --teal-200: #93C5FD;
            --teal-100: #DBEAFE;

            --gradient-hero: linear-gradient(135deg, #0B2545 0%, #1E40AF 40%, #3B82F6 100%);
            --gradient-hero-vivid: linear-gradient(160deg, #071B33 0%, #1E3A8A 30%, #1D4ED8 70%, #2563EB 100%);
            --gradient-teal: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
            --gradient-glow: radial-gradient(circle at 50% 50%, rgba(37, 99, 235, 0.15), transparent 70%);

            --bg-white: #FFFFFF;
            --bg-glass: rgba(255, 255, 255, 0.06);
            --bg-glass-strong: rgba(255, 255, 255, 0.1);

            --text-900: #1A202C;
            --text-700: #2D3748;
            --text-500: #718096;
            --text-400: #A0AEC0;
            --text-300: #CBD5E0;
            --text-white: #FFFFFF;
            --text-white-70: rgba(255, 255, 255, 0.7);
            --text-white-50: rgba(255, 255, 255, 0.5);
            --text-white-30: rgba(255, 255, 255, 0.3);

            --border-200: #E2E8F0;
            --border-100: #EDF2F7;
            --border-glass: rgba(255, 255, 255, 0.12);

            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-2xl: 32px;

            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -1px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 25px -5px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --shadow-xl: 0 25px 50px -12px rgba(0,0,0,0.15);
            --shadow-glow-teal: 0 0 40px rgba(37, 99, 235, 0.2);
            --shadow-input-focus: 0 0 0 4px rgba(37, 99, 235, 0.12);

            --transition-fast: 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-smooth: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* ============================
           RESET & BASE
        ============================ */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--gradient-hero-vivid);
            color: var(--text-700);
            min-height: 100vh;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ============================
           LAYOUT: SPLIT SCREEN
        ============================ */
        .login-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        /* --- Left Panel (Branding) --- */
        .login-branding {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }

        /* Animated background particles */
        .particle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0;
            animation: float-particle linear infinite;
        }

        .particle:nth-child(1) {
            width: 6px; height: 6px;
            background: rgba(37, 99, 235, 0.4);
            left: 15%; top: 80%;
            animation-duration: 12s;
            animation-delay: 0s;
        }
        .particle:nth-child(2) {
            width: 4px; height: 4px;
            background: rgba(66, 165, 245, 0.35);
            left: 35%; top: 90%;
            animation-duration: 15s;
            animation-delay: 2s;
        }
        .particle:nth-child(3) {
            width: 8px; height: 8px;
            background: rgba(37, 99, 235, 0.3);
            left: 65%; top: 85%;
            animation-duration: 18s;
            animation-delay: 4s;
        }
        .particle:nth-child(4) {
            width: 3px; height: 3px;
            background: rgba(187, 222, 251, 0.4);
            left: 80%; top: 75%;
            animation-duration: 10s;
            animation-delay: 1s;
        }
        .particle:nth-child(5) {
            width: 5px; height: 5px;
            background: rgba(37, 99, 235, 0.25);
            left: 50%; top: 95%;
            animation-duration: 14s;
            animation-delay: 3s;
        }
        .particle:nth-child(6) {
            width: 7px; height: 7px;
            background: rgba(66, 165, 245, 0.2);
            left: 25%; top: 70%;
            animation-duration: 16s;
            animation-delay: 5s;
        }

        @keyframes float-particle {
            0% {
                opacity: 0;
                transform: translateY(0) translateX(0) scale(0.5);
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: translateY(-100vh) translateX(30px) scale(1);
            }
        }

        /* Decorative orbs */
        .login-branding::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 65%);
            top: -150px;
            right: -150px;
            animation: orb-drift 20s ease-in-out infinite alternate;
        }

        .login-branding::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(30, 136, 229, 0.08) 0%, transparent 65%);
            bottom: -120px;
            left: -120px;
            animation: orb-drift 25s ease-in-out infinite alternate-reverse;
        }

        @keyframes orb-drift {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -20px) scale(1.1); }
            100% { transform: translate(-20px, 20px) scale(0.95); }
        }

        .branding-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 440px;
            animation: fadeInUp 0.8s ease forwards;
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            background: var(--gradient-teal);
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 32px;
            box-shadow: var(--shadow-glow-teal), 0 8px 32px rgba(37, 99, 235, 0.3);
            position: relative;
            animation: logo-breathe 4s ease-in-out infinite;
        }

        .brand-logo::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: var(--radius-xl);
            background: var(--gradient-teal);
            z-index: -1;
            opacity: 0.3;
            filter: blur(12px);
        }

        @keyframes logo-breathe {
            0%, 100% { transform: scale(1); box-shadow: var(--shadow-glow-teal), 0 8px 32px rgba(37, 99, 235, 0.3); }
            50% { transform: scale(1.04); box-shadow: var(--shadow-glow-teal), 0 12px 48px rgba(37, 99, 235, 0.4); }
        }

        .brand-title {
            font-size: 38px;
            font-weight: 900;
            color: var(--text-white);
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 16px;
        }

        .brand-title span {
            background: linear-gradient(135deg, var(--teal-300), var(--teal-200));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-subtitle {
            font-size: 16px;
            color: var(--text-white-70);
            line-height: 1.7;
            margin-bottom: 48px;
            font-weight: 400;
        }

        /* Feature list */
        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 20px;
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-md);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all var(--transition-base);
        }

        .feature-item:hover {
            background: var(--bg-glass-strong);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateX(6px);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: var(--bg-glass-strong);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .feature-text h4 {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-white);
            margin-bottom: 2px;
        }

        .feature-text p {
            font-size: 12px;
            color: var(--text-white-50);
            line-height: 1.4;
        }

        /* --- Right Panel (Form) --- */
        .login-form-panel {
            width: 520px;
            min-width: 520px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            background: var(--bg-white);
        }

        /* Subtle top accent */
        .login-form-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-teal);
        }

        .form-wrapper {
            width: 100%;
            max-width: 380px;
            animation: fadeInRight 0.6s ease forwards;
        }

        /* Form header */
        .form-header {
            margin-bottom: 36px;
        }

        .form-header .welcome-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: var(--primary-50);
            color: var(--primary-600);
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
            letter-spacing: 0.02em;
        }

        .form-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-900);
            letter-spacing: -0.03em;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .form-header p {
            font-size: 14px;
            color: var(--text-500);
            line-height: 1.6;
        }

        /* Alert */
        .alert-error {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 16px;
            background: linear-gradient(135deg, #FFF5F5 0%, #FED7D7 100%);
            border: 1px solid #FEB2B2;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            animation: shake 0.4s ease;
        }

        .alert-error .alert-icon {
            font-size: 18px;
            flex-shrink: 0;
            line-height: 1;
        }

        .alert-error .alert-message {
            font-size: 13px;
            color: #C53030;
            font-weight: 500;
            line-height: 1.5;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            50% { transform: translateX(6px); }
            75% { transform: translateX(-4px); }
        }

        /* Form groups */
        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-700);
            margin-bottom: 8px;
            letter-spacing: 0.01em;
        }

        .form-group label .label-icon {
            font-size: 14px;
            opacity: 0.7;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            opacity: 0.4;
            transition: opacity var(--transition-fast);
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 1.5px solid var(--border-200);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-family: inherit;
            color: var(--text-700);
            background: var(--bg-white);
            outline: none;
            transition: all var(--transition-base);
        }

        .form-input::placeholder {
            color: var(--text-400);
            font-weight: 400;
        }

        .form-input:hover {
            border-color: var(--text-300);
        }

        .form-input:focus {
            border-color: var(--teal-500);
            box-shadow: var(--shadow-input-focus);
        }

        .form-input:focus + .input-icon,
        .form-input:focus ~ .input-icon {
            opacity: 0.8;
        }

        .input-wrapper:focus-within .input-icon {
            opacity: 0.8;
            color: var(--teal-600);
        }

        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            opacity: 0.4;
            transition: opacity var(--transition-fast);
            padding: 4px;
            line-height: 1;
        }

        .password-toggle:hover {
            opacity: 0.8;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 14px 24px;
            background: var(--gradient-teal);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 15px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            transition: all var(--transition-base);
            letter-spacing: 0.01em;
            margin-top: 28px;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.35);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-login .btn-arrow {
            transition: transform var(--transition-fast);
        }

        .btn-login:hover .btn-arrow {
            transform: translateX(4px);
        }

        /* Footer text */
        .form-footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-100);
        }

        .form-footer p {
            font-size: 12px;
            color: var(--text-400);
            line-height: 1.6;
        }

        .form-footer .org-name {
            font-weight: 600;
            color: var(--text-500);
        }

        /* Security badge */
        .security-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 16px;
            padding: 6px 12px;
            background: var(--border-100);
            border-radius: 100px;
            font-size: 11px;
            color: var(--text-400);
            font-weight: 500;
        }

        .security-badge .shield-icon {
            font-size: 12px;
        }

        /* ============================
           ANIMATIONS
        ============================ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Feature items staggered animation */
        .feature-item:nth-child(1) { animation: fadeInUp 0.6s ease 0.2s both; }
        .feature-item:nth-child(2) { animation: fadeInUp 0.6s ease 0.35s both; }
        .feature-item:nth-child(3) { animation: fadeInUp 0.6s ease 0.5s both; }

        /* ============================
           RESPONSIVE: TABLET (768px - 1024px)
        ============================ */
        @media (max-width: 1024px) {
            .login-branding {
                padding: 48px 36px;
            }

            .brand-title {
                font-size: 32px;
            }

            .brand-subtitle {
                font-size: 14.5px;
                margin-bottom: 36px;
            }

            .brand-logo {
                width: 68px;
                height: 68px;
                font-size: 30px;
                margin-bottom: 24px;
            }

            .login-form-panel {
                width: 460px;
                min-width: 460px;
                padding: 32px;
            }

            .form-header h1 {
                font-size: 24px;
            }
        }

        /* ============================
           RESPONSIVE: SMALL TABLET / LARGE PHONE (max 768px)
        ============================ */
        @media (max-width: 768px) {
            body {
                overflow-y: auto;
            }

            .login-container {
                flex-direction: column;
                min-height: 100vh;
            }

            .login-branding {
                min-height: auto;
                padding: 48px 28px 40px;
                flex: 0 0 auto;
            }

            .brand-logo {
                width: 60px;
                height: 60px;
                font-size: 28px;
                margin-bottom: 20px;
                border-radius: var(--radius-lg);
            }

            .brand-title {
                font-size: 28px;
                margin-bottom: 10px;
            }

            .brand-subtitle {
                font-size: 13.5px;
                margin-bottom: 28px;
            }

            .feature-list {
                display: none;
            }

            .login-form-panel {
                width: 100%;
                min-width: 100%;
                flex: 1;
                border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
                padding: 36px 28px 40px;
                box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.1);
            }

            .login-form-panel::before {
                display: none;
            }

            /* Drag handle indicator */
            .login-form-panel::after {
                content: '';
                position: absolute;
                top: 12px;
                left: 50%;
                transform: translateX(-50%);
                width: 40px;
                height: 4px;
                border-radius: 100px;
                background: var(--border-200);
            }

            .form-wrapper {
                max-width: 100%;
                padding-top: 8px;
            }

            .form-header {
                margin-bottom: 28px;
            }

            .form-header h1 {
                font-size: 22px;
            }

            .form-header p {
                font-size: 13px;
            }

            .form-input {
                padding: 14px 16px 14px 44px;
                font-size: 16px; /* Prevent iOS zoom */
            }

            .btn-login {
                padding: 15px 24px;
                font-size: 15px;
            }

            .form-footer {
                margin-top: 24px;
                padding-top: 20px;
            }
        }

        /* ============================
           RESPONSIVE: SMALL PHONE (max 480px)
        ============================ */
        @media (max-width: 480px) {
            .login-branding {
                padding: 36px 24px 32px;
            }

            .brand-logo {
                width: 52px;
                height: 52px;
                font-size: 24px;
                margin-bottom: 16px;
            }

            .brand-title {
                font-size: 24px;
            }

            .brand-subtitle {
                font-size: 13px;
                margin-bottom: 0;
            }

            .login-form-panel {
                padding: 32px 20px 36px;
            }

            .form-header .welcome-back {
                font-size: 11px;
                padding: 5px 12px;
            }

            .form-header h1 {
                font-size: 20px;
            }

            .form-group label {
                font-size: 12.5px;
            }

            .form-input {
                padding: 12px 14px 12px 40px;
            }

            .btn-login {
                padding: 14px 20px;
            }

            .security-badge {
                font-size: 10px;
            }
        }

        /* ============================
           LARGE SCREENS (1440px+)
        ============================ */
        @media (min-width: 1440px) {
            .login-form-panel {
                width: 560px;
                min-width: 560px;
            }

            .brand-title {
                font-size: 42px;
            }

            .brand-subtitle {
                font-size: 17px;
            }

            .brand-logo {
                width: 90px;
                height: 90px;
                font-size: 40px;
                border-radius: var(--radius-2xl);
            }
        }

        /* ============================
           ACCESSIBILITY & FOCUS
        ============================ */
        *:focus-visible {
            outline: 2px solid var(--teal-500);
            outline-offset: 2px;
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Loading state */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.85;
        }

        .btn-login.loading .btn-text {
            opacity: 0;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spinner 0.6s linear infinite;
        }

        @keyframes spinner {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container" id="login-container">
        <!-- Left: Branding Panel -->
        <div class="login-branding">
            <!-- Floating particles -->
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>

            <div class="branding-content">
                <div class="brand-logo" aria-hidden="true" style="background: transparent; box-shadow: none; border: none;">
                    <img src="{{ asset('app-icon.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
                <h1 class="brand-title">Task<span>&</span>Schedule</h1>
                <p class="brand-subtitle">Platform manajemen tugas & penjadwalan terpadu untuk meningkatkan produktivitas organisasi Anda.</p>

                <div class="feature-list">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-check-circle"></i></div>
                        <div class="feature-text">
                            <h4>Manajemen Tugas</h4>
                            <p>Kelola dan pantau seluruh tugas dalam satu dashboard</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-bar-chart-line"></i></div>
                        <div class="feature-text">
                            <h4>Laporan Real-time</h4>
                            <p>Monitor progres tugas secara real-time dan terukur</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-people"></i></div>
                        <div class="feature-text">
                            <h4>Kolaborasi Tim</h4>
                            <p>Koordinasi tugas antar unit kerja dengan mudah</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Login Form Panel -->
        <div class="login-form-panel">
            <div class="form-wrapper">
                <div class="form-header">
                    <div class="welcome-back">
                        <span><i class="bi bi-hand-wave"></i></span> Selamat datang kembali
                    </div>
                    <h1 id="login-heading">Masuk ke Akun Anda</h1>
                    <p>Silakan masuk menggunakan akun organisasi Anda untuk melanjutkan.</p>
                </div>

                @if($errors->any())
                    <div class="alert-error" role="alert">
                        <span class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
                        <span class="alert-message">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" id="login-form" aria-labelledby="login-heading">
                    @csrf
                    <div class="form-group">
                        <label for="username">
                            <span class="label-icon"><i class="bi bi-person-fill"></i></span>
                            Username
                        </label>
                        <div class="input-wrapper">
                            <input
                                type="text"
                                id="username"
                                name="username"
                                class="form-input"
                                value="{{ old('username') }}"
                                placeholder="Masukkan username Anda"
                                required
                                autofocus
                                autocomplete="username"
                            >
                            <span class="input-icon"><i class="bi bi-person"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">
                            <span class="label-icon"><i class="bi bi-lock-fill"></i></span>
                            Password
                        </label>
                        <div class="input-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-input"
                                placeholder="Masukkan password Anda"
                                required
                                autocomplete="current-password"
                            >
                            <span class="input-icon"><i class="bi bi-lock"></i></span>
                            <button type="button" class="password-toggle" id="password-toggle" aria-label="Tampilkan password" tabindex="-1">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="btn-login">
                        <span class="btn-text">Masuk ke Sistem</span>
                        <span class="btn-arrow">→</span>
                    </button>
                </form>

                <div class="form-footer">
                    <p>Sistem ini hanya untuk pengguna terdaftar di<span class="org-name"> Organisasi Anda</span>.</p>
                    <div class="security-badge">
                        <span class="shield-icon"><i class="bi bi-shield-lock-fill"></i></span>
                        Koneksi aman & terenkripsi
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password toggle
        const passwordToggle = document.getElementById('password-toggle');
        const passwordInput = document.getElementById('password');

        if (passwordToggle && passwordInput) {
            passwordToggle.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                this.textContent = isPassword ? '🙈' : '👁️';
                this.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
            });
        }

        // Form loading state
        const loginForm = document.getElementById('login-form');
        const loginBtn = document.getElementById('btn-login');

        if (loginForm && loginBtn) {
            loginForm.addEventListener('submit', function() {
                loginBtn.classList.add('loading');
            });
        }

        // Input animation — focus glow
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.closest('.form-group').style.transform = 'translateY(-1px)';
                this.closest('.form-group').style.transition = 'transform 0.2s ease';
            });
            input.addEventListener('blur', function() {
                this.closest('.form-group').style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
