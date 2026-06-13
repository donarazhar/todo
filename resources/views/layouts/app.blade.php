<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1E3A8A">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="description" content="Task&Schedule — Platform manajemen tugas dan penjadwalan terpadu">
    <title>Task&Schedule - Dashboard Overview</title>
    <link rel="icon" type="image/png" href="{{ asset('app-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>

        /* ============================
           DESIGN SYSTEM & VARIABLES
        ============================ */
        :root {
            /* Corporate/Vibrant Blue Palette */
            --primary-900: #1E3A8A; /* Very dark blue */
            --primary-800: #1E40AF;
            --primary-700: #1D4ED8;
            --primary-600: #2563EB; /* Vibrant Blue */
            --primary-500: #3B82F6;
            --primary-400: #60A5FA;
            --primary-300: #93C5FD;
            --primary-100: #DBEAFE;
            --primary-50:  #EFF6FF;

            /* Remapping the old Teal variables to Blue shades so everywhere updates automatically */
            --teal-600: #1D4ED8;
            --teal-500: #2563EB;
            --teal-400: #3B82F6;
            --teal-300: #60A5FA;
            --teal-100: #DBEAFE;
            --teal-50:  #EFF6FF;

            /* Gradients using Dark Blue to Vibrant Blue */
            --gradient-primary: linear-gradient(135deg, #0B2545 0%, #1D4ED8 100%);
            --gradient-hero: linear-gradient(135deg, #0B2545 0%, #1E40AF 50%, #3B82F6 100%);
            --gradient-teal: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%); /* Old teal gradient is now Dark-to-Vibrant Blue */
            --gradient-card-blue: linear-gradient(135deg, #1D4ED8 0%, #60A5FA 100%);
            --gradient-card-teal: linear-gradient(135deg, #1E40AF 0%, #3B82F6 100%);
            --gradient-card-amber: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%);
            --gradient-card-purple: linear-gradient(135deg, #7C3AED 0%, #A78BFA 100%);

            --bg-app: #F4F7FB;
            --gradient-sidebar: linear-gradient(160deg, #071B33 0%, #1E3A8A 30%, #1D4ED8 70%, #2563EB 100%);
            --bg-sidebar: var(--gradient-sidebar);
            --bg-sidebar-hover: rgba(255,255,255,0.08);
            --bg-sidebar-active: rgba(255,255,255,0.14);
            --bg-white: #FFFFFF;
            --bg-card: #FFFFFF;

            --text-900: #1A202C;
            --text-700: #2D3748;
            --text-500: #718096;
            --text-400: #A0AEC0;
            --text-300: #CBD5E0;

            --border-100: #EDF2F7;
            --border-200: #E2E8F0;
            --border-300: #CBD5E0;

            --status-pending-bg: #EDF2F7;
            --status-pending-text: #4A5568;
            --status-active-bg: #FEF3C7;
            --status-active-text: #92400E;
            --status-done-bg: #D1FAE5;
            --status-done-text: #065F46;
            --status-danger-bg: #FEE2E2;
            --status-danger-text: #991B1B;

            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -1px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.08), 0 10px 10px -5px rgba(0,0,0,0.03);

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --radius-full: 9999px;

            --transition-fast: 0.15s ease;
            --transition-base: 0.25s ease;
            --transition-slow: 0.4s ease;
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
            scroll-behavior: smooth;
        }

        ::selection {
            background: var(--primary-400);
            color: white;
        }

        body {
            font-family: 'Inter', 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-app);
            color: var(--text-700);
            overflow: hidden;
            height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ============================
           UTILITY CLASSES
        ============================ */
        .badge {
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 11.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            letter-spacing: 0.01em;
            white-space: nowrap;
        }
        .badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .bg-belum { background: var(--status-pending-bg); color: var(--status-pending-text); }
        .bg-belum::before { background: var(--status-pending-text); }
        .bg-proses { background: var(--status-active-bg); color: var(--status-active-text); }
        .bg-proses::before { background: var(--status-active-text); }
        .bg-selesai { background: var(--status-done-bg); color: var(--status-done-text); }
        .bg-selesai::before { background: var(--status-done-text); }

        .btn {
            background: var(--gradient-teal);
            color: var(--bg-white);
            border: none;
            padding: 10px 20px;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 600;
            font-size: 13.5px;
            font-family: inherit;
            transition: all var(--transition-base);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.01em;
            position: relative;
            overflow: hidden;
        }
        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            transition: width 0.4s ease, height 0.4s ease;
            transform: translate(-50%, -50%);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        }
        .btn:hover::after {
            width: 300px;
            height: 300px;
        }
        .btn:active {
            transform: translateY(0);
        }
        .btn-secondary {
            background: var(--bg-white);
            border: 1px solid var(--border-200);
            color: var(--text-700);
        }
        .btn-secondary:hover {
            background: var(--border-100);
            box-shadow: none;
            transform: none;
        }
        .btn-danger {
            background: linear-gradient(135deg, #E53E3E 0%, #FC8181 100%);
        }
        .btn-danger:hover {
            box-shadow: 0 6px 20px rgba(229, 62, 62, 0.3);
        }
        .btn-sm {
            padding: 6px 14px;
            font-size: 12px;
        }

        /* ============================
           LOGIN SCREEN
        ============================ */
        #login-screen {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: var(--gradient-hero);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }
        #login-screen::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
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
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.25);
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
            letter-spacing: 0.01em;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border-200);
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-family: inherit;
            outline: none;
            color: var(--text-700);
            background: var(--bg-white);
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary-400);
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }

        /* ============================
           MAIN APP LAYOUT
        ============================ */
        #app-layout {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        /* ============================
           SIDEBAR
        ============================ */
        .sidebar {
            width: 270px;
            min-width: 270px;
            background: var(--bg-sidebar);
            color: var(--bg-white);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 0;
            position: relative;
            z-index: 100;
        }
        .sidebar::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 1px;
            height: 100%;
            background: linear-gradient(180deg, rgba(37, 99, 235, 0.3) 0%, transparent 30%, transparent 70%, rgba(37, 99, 235, 0.3) 100%);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .sidebar-brand-icon {
            width: 38px;
            height: 38px;
            background: var(--gradient-teal);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .sidebar-brand h3 {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.01em;
        }
        .sidebar-brand span {
            font-size: 10.5px;
            color: var(--teal-500);
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .sidebar-menu {
            flex-grow: 1;
            padding: 12px 12px;
            overflow-y: auto;
        }
        .menu-section-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,0.3);
            margin: 20px 12px 8px 12px;
            font-weight: 700;
        }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 14px;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 2px;
            cursor: pointer;
            transition: all var(--transition-fast);
            position: relative;
        }
        .menu-item:hover {
            background: var(--bg-sidebar-hover);
            color: rgba(255,255,255,0.95);
        }
        .menu-item.active {
            background: var(--bg-sidebar-active);
            color: var(--bg-white);
        }
        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: var(--teal-500);
        }
        .menu-item .menu-icon {
            width: 20px;
            text-align: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .sidebar-user {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .user-avatar {
            width: 38px;
            height: 38px;
            background: var(--gradient-card-blue);
            border: 2px solid var(--teal-500);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }
        .user-info h4 {
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
        }
        .user-info span {
            font-size: 11px;
            color: rgba(255,255,255,0.45);
        }
        .btn-logout {
            margin-left: auto;
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(255,255,255,0.1);
            background: transparent;
            color: #FC8181;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-fast);
        }
        .btn-logout:hover {
            background: rgba(252,129,129,0.1);
            border-color: rgba(252,129,129,0.3);
        }

        /* ============================
           CONTENT AREA
        ============================ */
        .content-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            min-width: 0;
        }

        .top-navbar {
            height: 64px;
            background: var(--bg-white);
            border-bottom: 1px solid var(--border-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 28px;
            flex-shrink: 0;
        }
        .role-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--primary-50);
            padding: 6px 16px;
            border-radius: var(--radius-full);
            font-size: 12.5px;
            font-weight: 600;
            color: var(--primary-600);
            border: 1px solid var(--primary-100);
        }
        .role-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--teal-500);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .quick-switch {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .quick-switch label {
            font-size: 12.5px;
            color: var(--text-500);
            font-weight: 500;
        }
        .quick-switch select {
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            border: 1.5px solid var(--border-200);
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            color: var(--text-700);
            background: var(--bg-white);
            outline: none;
            cursor: pointer;
            transition: border-color var(--transition-fast);
        }
        .quick-switch select:focus {
            border-color: var(--primary-400);
        }

        /* ============================
           CONTENT BODY
        ============================ */
        .content-body {
            padding: 28px;
            overflow-y: auto;
            flex-grow: 1;
        }

        /* ============================
           VIEW PANELS
        ============================ */
        .view-panel {
            display: none;
        }
        .view-panel.active {
            display: block;
            animation: fadeSlideIn 0.35s ease;
        }
        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============================
           PAGE HEADER
        ============================ */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
        }
        .page-header h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-900);
            letter-spacing: -0.02em;
        }
        .page-header p {
            font-size: 13.5px;
            color: var(--text-500);
            margin-top: 4px;
        }
        .page-header-nav h2 {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-900);
            letter-spacing: -0.02em;
            margin: 0;
            line-height: 1.2;
        }
        .page-header-nav p {
            font-size: 11.5px;
            color: var(--text-500);
            margin-top: 2px;
            margin-bottom: 0;
        }

        /* ============================
           STAT CARDS
        ============================ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }
        .stat-card {
            border-radius: var(--radius-lg);
            padding: 22px 24px;
            color: white;
            position: relative;
            overflow: hidden;
            transition: transform var(--transition-base), box-shadow var(--transition-base);
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }
        .stat-card::after {
            content: '';
            position: absolute;
            bottom: -30px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .sc-blue { background: var(--gradient-card-blue); }
        .sc-teal { background: var(--gradient-card-teal); }
        .sc-amber { background: var(--gradient-card-amber); }
        .sc-purple { background: var(--gradient-card-purple); }

        .stat-card .stat-icon {
            font-size: 22px;
            margin-bottom: 12px;
            display: block;
        }
        .stat-card h3 {
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            opacity: 0.85;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .stat-card .value {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
        }
        .stat-card .stat-sub {
            font-size: 11.5px;
            opacity: 0.7;
            margin-top: 4px;
        }

        /* ============================
           SECTION BOXES
        ============================ */
        .section-box {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
            border: 1px solid var(--border-100);
            transition: box-shadow var(--transition-base);
        }
        .section-box:hover {
            box-shadow: var(--shadow-md);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-900);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title .title-icon {
            font-size: 16px;
        }

        /* ============================
           DATA TABLES
        ============================ */
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th {
            background: var(--bg-app);
            color: var(--text-500);
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            padding: 12px 16px;
            border-bottom: 2px solid var(--border-200);
            letter-spacing: 0.05em;
            white-space: nowrap;
        }
        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-100);
            font-size: 13.5px;
            color: var(--text-700);
            vertical-align: middle;
        }
        tr {
            transition: background var(--transition-fast);
        }
        tr:hover td {
            background: rgba(237, 242, 247, 0.5);
        }
        tr:last-child td {
            border-bottom: none;
        }

        /* ============================
           PAGINATION
        ============================ */
        .pagination { display: flex; list-style: none; gap: 5px; margin-top: 15px; padding: 0 10px; align-items: center; justify-content: flex-end; }
        .page-item.active .page-link { background: var(--teal-500); color: white; border-color: var(--teal-500); }
        .page-item.disabled .page-link { color: var(--text-400); pointer-events: none; }
        .page-link { padding: 6px 12px; border: 1px solid var(--border-200); border-radius: var(--radius-sm); color: var(--text-700); text-decoration: none; font-size: 12.5px; transition: all var(--transition-fast); }
        .page-link:hover { background: var(--border-100); }
        .page-item:first-child .page-link, .page-item:last-child .page-link { font-weight: bold; }

        .pagination-mobile { display: none; margin-top: 16px; align-items: center; justify-content: space-between; background: var(--bg-card); padding: 10px 16px; border-radius: var(--radius-md); border: 1px solid var(--border-100); box-shadow: var(--shadow-sm); }
        .pagination-mobile .page-info { font-size: 12px; font-weight: 700; color: var(--text-500); }
        .pagination-mobile .page-nav-buttons { display: flex; gap: 8px; }
        .pagination-mobile .page-nav-buttons a, .pagination-mobile .page-nav-buttons span { padding: 6px 12px; border-radius: var(--radius-sm); font-size: 11.5px; font-weight: 600; text-decoration: none; border: 1px solid var(--border-200); color: var(--text-700); background: var(--bg-white); transition: all var(--transition-fast); display: flex; align-items: center; gap: 4px; }
        .pagination-mobile .page-nav-buttons a:hover { background: var(--border-100); }
        .pagination-mobile .page-nav-buttons span.disabled { color: var(--text-400); background: var(--bg-app); cursor: not-allowed; border-color: var(--border-100); }
        
        @media (max-width: 768px) {
            .pagination-mobile { display: flex; }
            .desktop-pagination { display: none !important; }
        }

        /* ============================
           SPLIT / GRID LAYOUTS
        ============================ */
        .split-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 24px;
        }
        .split-50 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        .grid-3-col {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        @media(max-width: 1200px) {
            .split-container { grid-template-columns: 1fr; }
            .split-50 { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        /* ============================
           CALENDAR
        ============================ */
        .calendar-wrapper {
            background: var(--bg-white);
            border-radius: var(--radius-md);
            overflow: hidden;
        }
        .calendar-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-100);
        }
        .calendar-nav h4 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-900);
        }
        .calendar-nav button {
            background: var(--border-100);
            border: none;
            width: 30px;
            height: 30px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background var(--transition-fast);
        }
        .calendar-nav button:hover {
            background: var(--border-200);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            padding: 10px;
        }
        .calendar-day-head {
            text-align: center;
            font-weight: 700;
            font-size: 11px;
            color: var(--text-500);
            padding: 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .calendar-cell {
            background: var(--bg-white);
            border: 1px solid var(--border-100);
            border-radius: var(--radius-sm);
            min-height: 72px;
            padding: 4px 5px;
            font-size: 11px;
            position: relative;
            transition: all var(--transition-fast);
        }
        .calendar-cell:hover {
            border-color: var(--primary-300);
            box-shadow: 0 0 0 2px rgba(30, 136, 229, 0.06);
        }
        .calendar-cell.today {
            border-color: var(--teal-500);
            background: var(--teal-50);
        }
        .calendar-cell.empty {
            background: var(--bg-app);
            border-color: transparent;
            min-height: auto;
        }
        .calendar-cell.empty:hover {
            border-color: transparent;
            box-shadow: none;
        }
        .calendar-cell .day-num {
            font-weight: 700;
            color: var(--text-500);
            margin-bottom: 3px;
            font-size: 11.5px;
        }
        .calendar-cell.today .day-num {
            color: var(--teal-600);
        }
        .calendar-event {
            background: var(--primary-50);
            color: var(--primary-600);
            border-left: 2.5px solid var(--primary-500);
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9.5px;
            font-weight: 600;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .calendar-event.teal {
            background: var(--teal-50);
            color: var(--teal-600);
            border-left-color: var(--teal-500);
        }
        .calendar-event.amber {
            background: #FEF3C7;
            color: #92400E;
            border-left-color: #F59E0B;
        }

        /* ============================
           PROGRESS BAR
        ============================ */
        .progress-bar-wrap {
            background: var(--border-100);
            border-radius: var(--radius-full);
            height: 8px;
            overflow: hidden;
            margin-top: 6px;
        }
        .progress-fill {
            height: 100%;
            border-radius: var(--radius-full);
            transition: width 0.8s ease;
        }
        .fill-blue { background: var(--gradient-card-blue); }
        .fill-teal { background: var(--gradient-card-teal); }
        .fill-amber { background: var(--gradient-card-amber); }

        /* ============================
           TABS
        ============================ */
        .tabs-bar {
            display: flex;
            gap: 0;
            border-bottom: 2px solid var(--border-100);
            margin-bottom: 20px;
        }
        .tab-btn {
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-500);
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            cursor: pointer;
            font-family: inherit;
            transition: all var(--transition-fast);
        }
        .tab-btn:hover {
            color: var(--text-700);
        }
        .tab-btn.active {
            color: var(--primary-500);
            border-bottom-color: var(--primary-500);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeSlideIn 0.25s ease;
        }

        /* ============================
           MODAL OVERLAY
        ============================ */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(11, 37, 69, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 5000;
        }
        .modal-overlay.show {
            display: flex;
        }
        .modal-box {
            background: var(--bg-white);
            border-radius: var(--radius-xl);
            padding: 32px;
            width: 100%;
            max-width: 520px;
            box-shadow: var(--shadow-xl);
            animation: modalPop 0.3s ease;
        }
        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .modal-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-900);
        }
        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-200);
            background: var(--bg-white);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all var(--transition-fast);
        }
        .modal-close:hover {
            background: var(--status-danger-bg);
            border-color: transparent;
            color: var(--status-danger-text);
        }

        /* ============================
           ERD DOCUMENTATION
        ============================ */
        .erd-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .erd-table-card {
            background: var(--bg-white);
            border: 1px solid var(--border-200);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: all var(--transition-base);
        }
        .erd-table-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        .erd-table-header {
            background: var(--gradient-primary);
            color: white;
            padding: 12px 16px;
            font-weight: 700;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.01em;
        }
        .erd-table-header.teal {
            background: var(--gradient-teal);
        }
        .erd-table-header.amber {
            background: var(--gradient-card-amber);
        }
        .erd-table-header.purple {
            background: var(--gradient-card-purple);
        }
        .erd-field {
            padding: 9px 16px;
            border-bottom: 1px solid var(--border-100);
            font-size: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .erd-field:last-child {
            border-bottom: none;
        }
        .erd-field .field-name {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            color: var(--text-700);
        }
        .erd-field .type {
            color: var(--text-500);
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 11px;
            background: var(--border-100);
            padding: 2px 8px;
            border-radius: var(--radius-sm);
        }
        .erd-field .key-badge {
            font-size: 9px;
            font-weight: 700;
            padding: 1px 5px;
            border-radius: 3px;
        }
        .key-pk { background: #FEF3C7; color: #92400E; }
        .key-fk { background: #DBEAFE; color: #1E40AF; }

        /* ============================
           FLOW DIAGRAM
        ============================ */
        .flow-container {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            border-radius: var(--radius-lg);
            padding: 28px;
            color: #E2E8F0;
        }
        .flow-title {
            color: #38BDF8;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .flow-steps {
            display: flex;
            align-items: center;
            gap: 0;
            flex-wrap: wrap;
            justify-content: center;
        }
        .flow-step {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: var(--radius-md);
            padding: 16px 20px;
            text-align: center;
            min-width: 140px;
            transition: all var(--transition-base);
        }
        .flow-step:hover {
            background: rgba(255,255,255,0.12);
            transform: translateY(-2px);
        }
        .flow-step .step-num {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.4);
            margin-bottom: 6px;
        }
        .flow-step .step-icon {
            font-size: 22px;
            margin-bottom: 6px;
        }
        .flow-step .step-label {
            font-size: 12px;
            font-weight: 600;
            color: #E2E8F0;
        }
        .flow-step .step-desc {
            font-size: 10px;
            color: rgba(255,255,255,0.45);
            margin-top: 4px;
        }
        .flow-arrow {
            font-size: 20px;
            color: var(--teal-500);
            padding: 0 8px;
        }

        .flow-note {
            background: rgba(56, 189, 248, 0.08);
            border: 1px solid rgba(56, 189, 248, 0.2);
            border-radius: var(--radius-md);
            padding: 14px 18px;
            margin-top: 20px;
            font-size: 12.5px;
            line-height: 1.7;
            color: #94A3B8;
        }
        .flow-note code {
            background: rgba(244, 114, 182, 0.15);
            color: #F472B6;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 11.5px;
        }
        .flow-note .hl-green {
            color: #6EE7B7;
            font-family: 'Consolas', monospace;
            font-size: 11.5px;
        }

        /* ============================
           TOAST NOTIFICATION
        ============================ */
        .toast {
            position: fixed;
            top: 24px;
            right: 24px;
            background: var(--bg-white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            transform: translateX(calc(100% + 24px));
            transition: transform 0.4s ease;
            max-width: 380px;
            border-left: 4px solid var(--teal-500);
        }
        .toast.show {
            transform: translateX(0);
        }
        .toast .toast-icon {
            font-size: 20px;
            flex-shrink: 0;
        }
        .toast .toast-text {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-700);
            line-height: 1.4;
        }

        /* ============================
           CHECKBOX STYLED LIST
        ============================ */
        .checkbox-list {
            border: 1.5px solid var(--border-200);
            border-radius: var(--radius-md);
            max-height: 120px;
            overflow-y: auto;
            padding: 8px;
        }
        .checkbox-list label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 8px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            cursor: pointer;
            transition: background var(--transition-fast);
            font-weight: 400;
            margin-bottom: 0;
        }
        .checkbox-list label:hover {
            background: var(--border-100);
        }
        .checkbox-list input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--teal-500);
        }

        /* ============================
           EMPTY STATE
        ============================ */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-400);
        }
        .empty-state .empty-icon {
            font-size: 40px;
            margin-bottom: 12px;
            opacity: 0.5;
        }
        .empty-state p {
            font-size: 13px;
        }

        /* ============================
           SCROLLBAR
        ============================ */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--border-300);
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-400);
        }
    
        /* ============================
           RESPONSIVE UI (Hamburger & Bottom Nav)
        ============================ */
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-700);
            cursor: pointer;
            padding: 4px;
            margin-right: 12px;
        }
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(11, 37, 69, 0.4);
            z-index: 8999;
            backdrop-filter: blur(2px);
        }
        
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; width: 100%;
            background: var(--bg-sidebar);
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 9999;
            height: 65px;
            padding-bottom: env(safe-area-inset-bottom);
        }
        .b-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            font-size: 10.5px;
            font-weight: 600;
            transition: all var(--transition-fast);
            cursor: pointer;
            gap: 4px;
            border: none;
            background: none;
            font-family: inherit;
        }
        .b-nav-item:hover {
            color: rgba(255,255,255,0.8);
        }
        .b-nav-item.active {
            color: var(--teal-400);
        }
        .b-nav-item .icon {
            font-size: 22px;
            margin-bottom: 2px;
            position: relative;
        }
        .b-nav-badge {
            position: absolute;
            top: -4px;
            right: -10px;
            background: #E53E3E;
            color: white;
            font-size: 9px;
            font-weight: 800;
            padding: 2px 5px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
            min-width: 16px;
            text-align: center;
            line-height: 1.2;
            font-family: 'Inter', sans-serif;
        }

        /* TABLET (768px - 1024px) */
        @media(max-width: 1024px) {
            .hamburger-btn {
                display: block;
            }
            .sidebar {
                position: fixed;
                left: -270px;
                top: 0;
                bottom: 0;
                height: 100vh;
                transition: left 0.3s ease;
                z-index: 9000;
                box-shadow: var(--shadow-xl);
            }
            .sidebar.open {
                left: 0;
            }
            .sidebar-backdrop.show {
                display: block;
            }
        }

        /* MOBILE (< 768px) */
        @media(max-width: 768px) {
            .hamburger-btn {
                display: none; /* Hide hamburger, use bottom nav */
            }
            .sidebar {
                display: none !important; /* Completely hide sidebar */
            }
            .sidebar-backdrop {
                display: none !important;
            }
            .bottom-nav {
                display: flex;
            }
            .content-area {
                padding-bottom: 65px; /* Space for bottom nav */
            }
            .top-navbar {
                padding: 0 16px;
            }
            .content-body {
                padding: 16px;
            }
            .role-indicator {
                font-size: 11px;
                padding: 4px 10px;
            }
            .page-header-nav h2 {
                font-size: 16px;
            }
        }

        /* ============================
           LARAVEL POLISH ADDITIONS
        ============================ */
        /* Error Validation UI */
        .form-group input.is-invalid, .form-group select.is-invalid, .form-group textarea.is-invalid {
            border-color: #E53E3E; box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }
        .text-error { color: #E53E3E; font-size: 11px; font-weight: 600; margin-top: 4px; display: block; }

        /* ============================
           DARK MODE OVERRIDES
        ============================ */
        body.dark-mode {
            --bg-app: #111827;
            --bg-white: #1F2937;
            --bg-card: #1F2937;
            --text-900: #F9FAFB;
            --text-700: #E5E7EB;
            --text-500: #9CA3AF;
            --text-400: #6B7280;
            --text-300: #4B5563;
            --border-100: #374151;
            --border-200: #4B5563;
            --border-300: #6B7280;
            --status-pending-bg: #374151;
            --status-pending-text: #D1D5DB;
            --status-active-bg: #78350F;
            --status-active-text: #FDE68A;
            --status-done-bg: #064E3B;
            --status-done-text: #A7F3D0;
            --status-danger-bg: #7F1D1D;
            --status-danger-text: #FECACA;
        }
        body.dark-mode .btn-secondary { background: var(--bg-card); color: var(--text-900); }
        body.dark-mode .form-group input, body.dark-mode .form-group select, body.dark-mode .form-group textarea { background: var(--bg-app); color: var(--text-900); }
        body.dark-mode .table th, body.dark-mode table th { background: var(--bg-app); color: var(--text-900); }
        body.dark-mode .agenda-event-item { background: var(--bg-app); }
        body.dark-mode .ptr-indicator { background: var(--bg-card); }

        /* ============================
           ACCESSIBILITY & PREFERENCES
        ============================ */
        *:focus-visible {
            outline: 2px solid var(--primary-400);
            outline-offset: 2px;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* ============================
           SKELETON LOADING
        ============================ */
        .skeleton {
            background: linear-gradient(90deg, var(--bg-card) 25%, var(--border-100) 50%, var(--bg-card) 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: var(--radius-sm);
            color: transparent !important;
            user-select: none;
            pointer-events: none;
        }
        .skeleton * {
            visibility: hidden;
        }
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* ============================
           PRINT STYLES
        ============================ */
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body>
    <div id="app-layout" x-data="{ 
        sidebarOpen: false,
        darkMode: localStorage.getItem('darkMode') === 'true',
        init() {
            if (this.darkMode) document.body.classList.add('dark-mode');
            $watch('darkMode', val => {
                localStorage.setItem('darkMode', val);
                if(val) document.body.classList.add('dark-mode');
                else document.body.classList.remove('dark-mode');
            })
        }
    }">
        
        <!-- SIDEBAR BACKDROP FOR TABLET -->
        <div class="sidebar-backdrop" :class="{ 'show': sidebarOpen }" @click="sidebarOpen = false"></div>

        <!-- SIDEBAR NAVIGASI -->
        <div class="sidebar" :class="{ 'open': sidebarOpen }">
            <div>
                <div class="sidebar-brand">
                    <div class="sidebar-brand-icon" style="background: transparent; box-shadow: none;">
                        <img src="{{ asset('app-icon.png') }}" alt="Icon" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <div>
                        <h3>Task&Schedule</h3>
                        <span>v.1.0 Beta</span>
                    </div>
                </div>
                                <div class="sidebar-menu">
                    <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="bi bi-house"></i></span>
                        <span>Dashboard Overview</span>
                    </a>

                    @if(Auth::user()->role->nama_role === 'Admin')
                    <div class="admin-feature">
                        <div class="menu-section-title">Fitur Administrator</div>
                        <a href="{{ route('master.index') }}" class="menu-item {{ request()->routeIs('master.index') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="bi bi-folder2-open"></i></span>
                            <span>Pengelolaan Master Data</span>
                        </a>
                        <a href="{{ route('kegiatan.index') }}" class="menu-item {{ request()->routeIs('kegiatan.index') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="bi bi-calendar-event"></i></span>
                            <span>Manajemen Penjadwalan</span>
                        </a>
                    </div>
                    @endif

                    @if(Auth::user()->role->nama_role === 'Pimpinan')
                    <div class="pimpinan-feature">
                        <div class="menu-section-title">Fitur Pimpinan</div>
                        <a href="{{ route('pimpinan.tasks') }}" class="menu-item {{ request()->routeIs('pimpinan.tasks') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="bi bi-journal-text"></i></span>
                            <span style="flex-grow: 1;">Delegasi To-Do List</span>
                            @if(isset($notifPimpinan) && $notifPimpinan > 0)
                                <span style="background: #E53E3E; color: white; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 10px; box-shadow: 0 2px 4px rgba(229, 62, 62, 0.3);">{{ $notifPimpinan }}</span>
                            @endif
                        </a>
                        <a href="{{ route('pimpinan.mandiri') }}" class="menu-item {{ request()->routeIs('pimpinan.mandiri') ? 'active' : '' }}">
                            <span class="menu-icon"><i class="bi bi-bullseye"></i></span>
                            <span>Todo Mandiri Pegawai</span>
                        </a>
                    </div>
                    @endif

                    @if(Auth::user()->role->nama_role === 'Pegawai')
                    <div class="pegawai-feature">
                        <div class="menu-section-title">Fitur Pegawai</div>
                        <a href="{{ route('pegawai.tasks', ['tab' => 'pimpinan']) }}" class="menu-item {{ request()->routeIs('pegawai.tasks') && request('tab', 'pimpinan') === 'pimpinan' ? 'active' : '' }}">
                            <span class="menu-icon"><i class="bi bi-person-badge"></i></span>
                            <span style="flex-grow: 1;">Delegasi Pimpinan</span>
                            @if(isset($notifPegawai) && $notifPegawai > 0)
                                <span style="background: #E53E3E; color: white; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 10px; box-shadow: 0 2px 4px rgba(229, 62, 62, 0.3);">{{ $notifPegawai }}</span>
                            @endif
                        </a>
                        <a href="{{ route('pegawai.tasks', ['tab' => 'mandiri']) }}" class="menu-item {{ request()->routeIs('pegawai.tasks') && request('tab') === 'mandiri' ? 'active' : '' }}">
                            <span class="menu-icon"><i class="bi bi-person"></i></span>
                            <span>To-Do Mandiri</span>
                        </a>
                    </div>
                    @endif

                    <!-- Dokumentasi -->
                    @if(Auth::user()->role->nama_role === 'Admin')
                    <div class="menu-section-title">Dokumentasi Teknis</div>
                    <a href="{{ route('docs.erd') }}" class="menu-item {{ request()->routeIs('docs.erd') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="bi bi-diagram-3"></i></span>
                        <span>Database & ERD</span>
                    </a>
                    <a href="{{ route('docs.alur') }}" class="menu-item {{ request()->routeIs('docs.alur') ? 'active' : '' }}">
                        <span class="menu-icon"><i class="bi bi-arrow-repeat"></i></span>
                        <span>Alur Aplikasi</span>
                    </a>
                    @endif
                </div></div>
            <div class="sidebar-user">
                <div class="user-avatar" id="avatar-initial">{{ substr(Auth::user()->nama, 0, 1) }}</div>
                <div class="user-info">
                    <h4 id="user-display-name">{{ Auth::user()->nama }}</h4>
                    <span id="user-display-role">{{ Auth::user()->role->nama_role }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout" title="Logout"><i class="bi bi-box-arrow-right"></i></button>
                </form>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="content-area">
            <!-- TOP NAVBAR -->
            <div class="top-navbar">
                <button class="hamburger-btn" @click="sidebarOpen = true"><i class="bi bi-list"></i></button>
                <div class="page-header-nav">
                    @yield('page_title')
                </div>
                <div style="margin-left: auto; display: flex; align-items: center; gap: 12px;">
                    <button @click="darkMode = !darkMode" class="btn btn-secondary" style="padding: 6px 10px; border-radius: var(--radius-full);" title="Toggle Dark Mode">
                        <i class="bi" :class="darkMode ? 'bi-sun-fill' : 'bi-moon-stars-fill'"></i>
                    </button>
                    <div class="role-indicator">
                        <div class="role-dot"></div>
                        Akses: <span id="current-role-txt">{{ Auth::user()->role->nama_role }}</span>
                    </div>
                </div>
            </div>

            <!-- SCROLLABLE CONTENT BODY -->
            <div class="content-body">
                
                

                @yield('content')
            </div>
        </div>
    </div>

    <!-- BOTTOM NAVIGATION (MOBILE ONLY) -->
    <nav class="bottom-nav">
        <a href="{{ route('dashboard') }}" class="b-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="icon"><i class="bi bi-house"></i></span><span class="label">Home</span>
        </a>
        
        @if(Auth::user()->role->nama_role === 'Admin')
            <a href="{{ route('master.index') }}" class="b-nav-item {{ request()->routeIs('master.index') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-folder2-open"></i></span><span class="label">Master</span>
            </a>
            <a href="{{ route('kegiatan.index') }}" class="b-nav-item {{ request()->routeIs('kegiatan.index') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-calendar-event"></i></span><span class="label">Jadwal</span>
            </a>
            <!-- Skipping docs in bottom nav to save space, Admin can use tablet/desktop -->
        @endif
        
        @if(Auth::user()->role->nama_role === 'Pimpinan')
            <a href="{{ route('pimpinan.tasks') }}" class="b-nav-item {{ request()->routeIs('pimpinan.tasks') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-journal-text"></i>
                    @if(isset($notifPimpinan) && $notifPimpinan > 0)
                        <span class="b-nav-badge">{{ $notifPimpinan }}</span>
                    @endif
                </span><span class="label">Delegasi</span>
            </a>
            <a href="{{ route('pimpinan.mandiri') }}" class="b-nav-item {{ request()->routeIs('pimpinan.mandiri') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-bullseye"></i></span><span class="label">Mandiri</span>
            </a>
        @endif
        
        @if(Auth::user()->role->nama_role === 'Pegawai')
            <a href="{{ route('pegawai.tasks', ['tab' => 'pimpinan']) }}" class="b-nav-item {{ request()->routeIs('pegawai.tasks') && request('tab', 'pimpinan') === 'pimpinan' ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-person-badge"></i>
                    @if(isset($notifPegawai) && $notifPegawai > 0)
                        <span class="b-nav-badge">{{ $notifPegawai }}</span>
                    @endif
                </span><span class="label">Delegasi</span>
            </a>
            <a href="{{ route('pegawai.tasks', ['tab' => 'mandiri']) }}" class="b-nav-item {{ request()->routeIs('pegawai.tasks') && request('tab') === 'mandiri' ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-person"></i></span><span class="label">Mandiri</span>
            </a>
        @endif
        
        <form method="POST" action="{{ route('logout') }}" style="display:contents;">
            @csrf
            <button type="submit" class="b-nav-item">
                <span class="icon"><i class="bi bi-box-arrow-right"></i></span><span class="label">Logout</span>
            </button>
        </form>
    </nav>

    <!-- TOAST NOTIFICATION -->
    <div id="toast" class="toast {{ session('success') ? 'show' : '' }}">
        <div class="toast-icon">✓</div>
        <div id="toast-text">{{ session('success') ?? '' }}</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast');
            if(toast && toast.classList.contains('show')) {
                setTimeout(() => { toast.classList.remove('show'); }, 4000);
            }

            // ============================
            // PULL-TO-REFRESH (Mobile only)
            // ============================
            if ('ontouchstart' in window) {
                const contentBody = document.querySelector('.content-body');
                if (contentBody) {
                    let startY = 0;
                    let pulling = false;
                    let indicator = document.querySelector('.ptr-indicator');
                    
                    // Create indicator if not exists
                    if (!indicator) {
                        indicator = document.createElement('div');
                        indicator.className = 'ptr-indicator';
                        indicator.innerHTML = '<i class="bi bi-arrow-clockwise"></i>';
                        contentBody.style.position = 'relative';
                        contentBody.insertBefore(indicator, contentBody.firstChild);
                    }

                    contentBody.addEventListener('touchstart', (e) => {
                        if (contentBody.scrollTop <= 0) {
                            startY = e.touches[0].clientY;
                            pulling = true;
                        }
                    }, { passive: true });

                    contentBody.addEventListener('touchmove', (e) => {
                        if (!pulling) return;
                        const currentY = e.touches[0].clientY;
                        const diff = currentY - startY;
                        
                        if (diff > 20 && diff < 120) {
                            indicator.classList.add('visible');
                            indicator.style.transform = `translateX(-50%) translateY(${Math.min(diff * 0.5, 30)}px)`;
                        }
                    }, { passive: true });

                    contentBody.addEventListener('touchend', () => {
                        if (!pulling) return;
                        pulling = false;
                        
                        if (indicator.classList.contains('visible')) {
                            indicator.classList.remove('visible');
                            indicator.classList.add('refreshing');
                            indicator.style.transform = 'translateX(-50%) translateY(10px)';
                            
                            setTimeout(() => {
                                window.location.reload();
                            }, 400);
                        }
                    }, { passive: true });
                }
            }
        });
        function showToast(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-text').innerText = msg;
            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); }, 4000);
        }
    </script>
    @stack('scripts')
</body>
</html>

