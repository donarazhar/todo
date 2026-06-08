<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Task&Schedule</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-600: #0E4D8F;
            --primary-500: #1565C0;
            --primary-400: #1E88E5;
            --primary-300: #42A5F5;
            --primary-100: #BBDEFB;
            --primary-50: #E3F2FD;
            --teal-600: #00897B;
            --teal-500: #20C997;
            --gradient-hero: linear-gradient(135deg, #0B2545 0%, #0E4D8F 50%, #137A7F 100%);
            --gradient-teal: linear-gradient(135deg, #20C997 0%, #0E9AA7 100%);
            --bg-white: #FFFFFF;
            --text-900: #1A202C;
            --text-700: #2D3748;
            --text-500: #718096;
            --border-200: #E2E8F0;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.08), 0 10px 10px -5px rgba(0,0,0,0.03);
            --transition-fast: 0.15s ease;
            --transition-base: 0.25s ease;
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            height: 100vh;
        }

        #login-screen {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: var(--gradient-hero);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        #login-screen::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(32, 201, 151, 0.08) 0%, transparent 70%);
            top: -200px;
            right: -200px;
        }
        #login-screen::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(30, 136, 229, 0.1) 0%, transparent 70%);
            bottom: -100px;
            left: -100px;
        }

        .login-box {
            background: var(--bg-white);
            padding: 44px 40px;
            border-radius: var(--radius-xl);
            width: 100%;
            max-width: 440px;
            box-shadow: var(--shadow-xl);
            text-align: center;
            position: relative;
            z-index: 1;
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-logo {
            width: 56px;
            height: 56px;
            background: var(--gradient-teal);
            border-radius: var(--radius-lg);
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 6px 20px rgba(32, 201, 151, 0.25);
        }
        .login-box h2 {
            color: var(--primary-600);
            margin-bottom: 6px;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .login-box p {
            color: var(--text-500);
            margin-bottom: 28px;
            font-size: 13.5px;
            line-height: 1.5;
        }

        .form-group {
            text-align: left;
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-700);
        }
        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border-200);
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-family: inherit;
            outline: none;
            color: var(--text-700);
        }
        .form-group input:focus {
            border-color: var(--primary-400);
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }
        .btn {
            background: var(--gradient-teal);
            color: var(--bg-white);
            border: none;
            padding: 12px 20px;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            font-family: inherit;
            width: 100%;
            margin-top: 10px;
        }
        .alert {
            background: #FEE2E2;
            color: #991B1B;
            padding: 10px;
            border-radius: var(--radius-md);
            font-size: 13px;
            margin-bottom: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div id="login-screen">
        <div class="login-box">
            <div class="login-logo">📅</div>
            <h2>Task&Schedule</h2>
            <p>Silakan masuk menggunakan akun organisasi Anda.</p>
            
            @if($errors->any())
                <div class="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username (admin/pimpinan/budi)" required autofocus autocomplete="off">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password (password)" required>
                </div>
                <button type="submit" class="btn" style="width: 100%; justify-content: center; margin-top: 8px;">Masuk ke Sistem</button>
            </form>
        </div>
    </div>
</body>
</html>
