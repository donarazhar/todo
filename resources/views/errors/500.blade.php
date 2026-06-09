<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Server</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-600: #2563EB;
            --text-900: #111827;
            --text-500: #6B7280;
            --bg-body: #F9FAFB;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-body); display: flex; align-items: center; justify-content: center; height: 100vh; color: var(--text-900); }
        .error-container { text-align: center; max-width: 400px; padding: 40px; }
        .error-code { font-size: 80px; font-weight: 700; color: #DC2626; line-height: 1; margin-bottom: 16px; }
        .error-title { font-size: 24px; font-weight: 700; margin-bottom: 12px; }
        .error-desc { font-size: 14px; color: var(--text-500); margin-bottom: 30px; line-height: 1.5; }
        .btn { display: inline-block; background-color: var(--primary-600); color: white; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; transition: opacity 0.2s; }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <h1 class="error-title">Kesalahan Server</h1>
        <p class="error-desc">Maaf, terjadi kesalahan internal pada server kami. Silakan coba lagi nanti atau hubungi administrator.</p>
        <a href="{{ url('/') }}" class="btn">Kembali ke Dashboard</a>
    </div>
</body>
</html>
