<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jadwal Pemeliharaan</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet" />
    
    <style>
        /* =========================================
           1. GLOBAL & BODY
           ========================================= */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(145deg, #f5f0ff 0%, #f0eaff 100%);
            color: #2d3748;
            min-height: 100vh;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(102, 126, 234, 0.05) 0%, transparent 30%),
                        radial-gradient(circle at 90% 70%, rgba(118, 75, 162, 0.06) 0%, transparent 35%);
            pointer-events: none;
            z-index: 0;
        }

        /* =========================================
           2. NAVBAR
           ========================================= */
        .navbar-blur {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.1);
            padding: 16px 0;
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            position: relative;
            z-index: 10;
        }
        .navbar-brand {
            font-weight: 700;
            color: #2d3748 !important;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .navbar-brand i {
            font-size: 2rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .nav-menu { display: flex; gap: 2rem; margin-left: 2rem; }
        .nav-menu .nav-link {
            color: #4a5568;
            font-weight: 500;
            padding: 0.5rem 0;
            position: relative;
            transition: all 0.3s;
            text-decoration: none;
        }
        .nav-menu .nav-link:hover, .nav-menu .nav-link.active { color: #667eea; }
        .nav-menu .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 0; height: 2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transition: width 0.3s;
        }
        .nav-menu .nav-link:hover::after, .nav-menu .nav-link.active::after { width: 100%; }
        .nav-menu .nav-link i { margin-right: 5px; font-size: 1.1rem; }

        /* =========================================
           3. TOMBOL LOGIN/LOGOUT
           ========================================= */
        .btn-login-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 60px;
            padding: 12px 36px;
            font-weight: 500;
            border: none;
            transition: 0.25s ease;
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .btn-login-modern:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6845a0 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
        }
        .btn-logout-modern {
            background: linear-gradient(135deg, #fc5c7d 0%, #6a82fb 100%);
            color: white;
            border-radius: 60px;
            padding: 12px 36px;
            font-weight: 500;
            border: none;
            transition: 0.25s ease;
            box-shadow: 0 6px 16px rgba(252, 92, 125, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-logout-modern:hover {
            background: linear-gradient(135deg, #e04a6a 0%, #5872e8 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(252, 92, 125, 0.4);
        }

        /* =========================================
           4. PAGE CONTENT & TITLE CARD
           ========================================= */
        .page-content { padding: 4rem 0 4rem 0; position: relative; z-index: 2; }
        .page-title-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px -20px rgba(102, 126, 234, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .page-title-card h2 { font-weight: 700; color: #2d3748; margin: 0; font-size: 1.7rem; }
        .page-title-card h2 i {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-right: 10px;
        }
        .page-title-card p { color: #718096; margin: 0.3rem 0 0 0; font-size: 0.95rem; }
        .user-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.6rem 1.4rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
        }

        /* =========================================
           5. CALENDAR WRAPPER & CUSTOM TOOLBAR
           ========================================= */
        .calendar-wrapper {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 40px -20px rgba(102, 126, 234, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.8);
            position: relative;
        }
        #calendar { font-family: 'Poppins', sans-serif; }
        .custom-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .custom-toolbar-left, .custom-toolbar-right { display: flex; gap: 0.5rem; align-items: center; }
        .btn-custom-view {
            background: white;
            color: #4a5568;
            border: 1px solid #cbd5e0;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
            cursor: pointer;
        }
        .btn-custom-view:hover { background: #f8f9fa; color: #2d3748; }
        .btn-custom-view.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
        }

        /* =========================================
           6. OVERRIDE FULLCALENDAR (WARNA & EVENT)
           ========================================= */
        .fc-toolbar.fc-header-toolbar { display: none !important; }
        .fc .fc-col-header-cell-cushion {
            color: #000000 !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            padding: 10px 0 !important;
        }
        .fc .fc-daygrid-day-number, .fc .fc-daygrid-day-top {
            color: #2d3748 !important;
            font-weight: 500 !important;
            text-decoration: none !important;
        }
        
        /* --- PERBAIKAN WARNA EVENT DISINI --- */
        .fc-event, .fc-event-dot {
            border: none !important;
            border-radius: 8px !important;
            padding: 3px 6px !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            cursor: pointer !important;
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3) !important;
            color: #ffffff !important;
        }
        .fc-event.status-selesai { background: linear-gradient(135deg, #38b2ac, #48bb78) !important; }
        .fc-event.status-ditolak { background: linear-gradient(135deg, #fc5c7d, #e53e3e) !important; }
        .fc-event.status-menunggu_verifikasi { background: linear-gradient(135deg, #3b82f6, #2563eb) !important; }
        .fc-day-today { background: rgba(102, 126, 234, 0.08) !important; }

        /* PAKSA WARNA TEKS EVENT JADI HITAM */
        .fc-event-title, .fc-event-time, .fc-event-title-container {
            color: #000000 !important;
            font-weight: 600 !important;
        }

        /* Event Pending: box putih seperti sebelumnya */
        .fc-event.status-pending {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            color: #1a202c !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08) !important;
        }

        /* Tulisan event pending hitam */
        .fc-event.status-pending .fc-event-title,
        .fc-event.status-pending .fc-event-time,
        .fc-event.status-pending .fc-event-title-container {
            color: #1a202c !important;
        }

        /* Titik biru kecil di depan event */
        .fc-event.status-pending .fc-event-title::before {
            content: '';
            display: inline-block;
            width: 7px;
            height: 7px;
            background: #4299e1;
            border-radius: 50%;
            margin-right: 6px;
            vertical-align: middle;
        }

        /* =========================================
           7. MODAL DETAIL & FORM
           ========================================= */
        .modal-content-modern {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 40px 80px -20px rgba(102, 126, 234, 0.4);
        }
        .modal-header-modern {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 1.5rem 2rem;
            border: none;
        }
        .modal-header-modern h5 { font-weight: 600; margin: 0; font-size: 1.2rem; }
        .modal-header-modern .btn-close { filter: brightness(0) invert(1); opacity: 0.9; }
        .modal-body-modern { padding: 2rem; background: #fafbff; }
        .detail-row { display: flex; padding: 0.7rem 0; border-bottom: 1px solid #eef2f7; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; color: #4a5568; min-width: 160px; font-size: 0.9rem; }
        .detail-value { color: #2d3748; font-size: 0.9rem; flex: 1; }
        .detail-value img { max-width: 200px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .modal-footer-modern {
            background: #fafbff;
            border-top: 1px solid #eef2f7;
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        /* =========================================
           8. TOMBOL AKSI MODAL (SELESAI/TOLAK)
           ========================================= */
        .btn-selesai-action {
            background: linear-gradient(135deg, #38b2ac, #48bb78);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.4rem;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.25s;
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
        }
        .btn-selesai-action:hover {
            background: linear-gradient(135deg, #2f9e99, #38a169);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(72, 187, 120, 0.4);
        }
        .btn-tolak-action {
            background: linear-gradient(135deg, #fc5c7d, #e53e3e);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.4rem;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.25s;
            box-shadow: 0 4px 12px rgba(229, 62, 62, 0.3);
        }
        .btn-tolak-action:hover {
            background: linear-gradient(135deg, #e04a6a, #c53030);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(229, 62, 62, 0.4);
        }

        /* =========================================
           9. BADGE STATUS
           ========================================= */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1.1rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        .status-badge.selesai { background: #c6f6d5; color: #22543d; }
        .status-badge.ditolak { background: #fed7d7; color: #822727; }
        .status-badge.pending { background: #fefcbf; color: #744210; }
        .status-badge.menunggu { background: #dbeafe; color: #1e40af; }

        /* =========================================
           10. SECTION HASIL DETAIL
           ========================================= */
        .result-section { margin-top: 1.5rem; padding: 1.2rem; border-radius: 14px; }
        .result-section.selesai { background: #f0fff4; border-left: 4px solid #48bb78; }
        .result-section.ditolak { background: #fff5f5; border-left: 4px solid #e53e3e; }
        .result-section h6 { font-weight: 600; margin-bottom: 1rem; font-size: 0.95rem; }
        .result-section.selesai h6 { color: #22543d; }
        .result-section.ditolak h6 { color: #822727; }

        /* =========================================
           11. FOOTER
           ========================================= */
        .footer-modern {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            padding: 3rem 0 1.5rem;
            border-top: 1px solid rgba(102, 126, 234, 0.2);
            color: #4a5568;
            position: relative;
            z-index: 2;
            margin-top: 4rem;
        }
        .footer-title { font-weight: 600; color: #2d3748; margin-bottom: 1.2rem; font-size: 1.1rem; }
        .footer-menu { list-style: none; padding: 0; margin: 0; }
        .footer-menu li { margin-bottom: 0.8rem; }
        .footer-menu a {
            color: #4a5568;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .footer-menu a:hover { color: #667eea; transform: translateX(5px); }
        .footer-menu i { color: #667eea; font-size: 1rem; }
        .footer-bottom {
            border-top: 1px solid rgba(102, 126, 234, 0.2);
            padding-top: 1.5rem;
            margin-top: 2rem;
            text-align: center;
            color: #718096;
        }

        /* =========================================
           12. RESPONSIVE (MOBILE)
           ========================================= */
        
        /* =========================================
                    MENU HAMBURGER MOBILE
        ========================================= */
        .btn-menu-mobile {
            width: 46px;
            height: 46px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.25);
        }

        .btn-menu-mobile i {
            font-size: 1.7rem;
        }

        .btn-menu-mobile:hover,
        .btn-menu-mobile:focus {
            color: white;
            background: linear-gradient(135deg, #5a6fd8, #6845a0);
        }

        /* =========================================
                    DROPDOWN MENU MOBILE
        ========================================= */
        .mobile-dropdown-menu {
            width: 100%;
            margin-top: 15px;
            padding-top: 12px;
            padding-bottom: 8px;
            border-top: 1px solid rgba(102, 126, 234, 0.15);
        }

        .mobile-menu-link {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 10px;
            color: #4a5568;
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            border: none;
            background: transparent;
            border-radius: 10px;
            text-align: left;
            transition: all 0.2s ease;
        }

        .mobile-menu-link i {
            width: 24px;
            font-size: 1.15rem;
            color: #667eea;
        }

        .mobile-menu-link:hover,
        .mobile-menu-link.active {
            background: #f2efff;
            color: #667eea;
        }

        .mobile-menu-divider {
            height: 1px;
            background: #eef2f7;
            margin: 8px 0;
        }

        /* LOGOUT MOBILE */
        .mobile-auth-button {
            padding: 12px 10px 6px 10px;
        }

        .mobile-auth-button form {
            margin: 0;
        }

        .mobile-auth-button .btn-logout-modern,
        .mobile-auth-button .btn-login-modern {
            padding: 12px 36px;
        }

        .mobile-logout {
            color: #c53030;
        }

        .mobile-logout i {
            color: #e53e3e;
        }

        .mobile-logout:hover {
            background: #fff5f5;
            color: #c53030;
        }

        /* LOGIN MOBILE */
        .mobile-login {
            color: #667eea;
        }

        .mobile-login i {
            color: #667eea;
        }

        @media (max-width: 992px) {
            .page-title-card { padding: 1.5rem; }
            .page-title-card h2 { font-size: 1.3rem; }
            .calendar-wrapper { padding: 1rem; }
            .custom-toolbar { flex-direction: column; align-items: stretch; }
            .custom-toolbar-left, .custom-toolbar-right { justify-content: center; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-blur sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('tenagakerja.dashboard') }}">
            <i class="bi bi-tools"></i>
            <span>Manajemen<b>Pemeliharaan</b></span>
        </a>
        <div class="nav-menu d-none d-lg-flex">
            <a href="{{ route('tenagakerja.dashboard') }}" class="nav-link"><i class="bi bi-house"></i> Dashboard</a>
            @if(in_array(Auth::user()->jenis_tugas, ['pemeliharaan_peralatan', 'keduanya']))
                <a href="{{ route('tenagakerja.checklist.index') }}" class="nav-link active"><i class="bi bi-check2-square"></i> Jadwal Pemeliharaan</a>
            @endif
            @if(in_array(Auth::user()->jenis_tugas, ['pengecekan_onderdil', 'keduanya']))
                <a href="{{ route('tenagakerja.pengecekan-onderdil.index') }}" class="nav-link"><i class="bi bi-clipboard-check"></i> Pengecekan Sparepart</a>
            @endif
            <a href="{{ route('tenagakerja.stok-onderdil.index') }}" class="nav-link"><i class="bi bi-box-seam"></i> Stok Sparepart</a>
        </div>
        <div class="d-none d-lg-flex">
            @auth
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout-modern">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            @else
                <a href="/login" class="btn btn-login-modern">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            @endauth
        </div>

        {{-- TOMBOL HAMBURGER MOBILE --}}
        <button class="btn btn-menu-mobile d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenuTenagaKerja" aria-expanded="false" aria-controls="mobileMenuTenagaKerja">
            <i class="bi bi-list"></i>
        </button>

        {{-- MENU MOBILE --}}
        <div class="collapse d-lg-none w-100" id="mobileMenuTenagaKerja">
            <div class="mobile-dropdown-menu">
                <a href="{{ route('tenagakerja.dashboard') }}" class="mobile-menu-link">
                    <i class="bi bi-house"></i>
                    <span>Dashboard</span>
                </a>
                @if(in_array(Auth::user()->jenis_tugas, ['pemeliharaan_peralatan', 'keduanya']))
                    <a href="{{ route('tenagakerja.checklist.index') }}" class="mobile-menu-link active">
                        <i class="bi bi-check2-square"></i>
                        <span>Jadwal Pemeliharaan</span>
                    </a>
                @endif
                @if(in_array(Auth::user()->jenis_tugas, ['pengecekan_onderdil', 'keduanya']))
                    <a href="{{ route('tenagakerja.pengecekan-onderdil.index') }}" class="mobile-menu-link">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Pengecekan Sparepart</span>
                    </a>
                @endif
                <a href="{{ route('tenagakerja.stok-onderdil.index') }}" class="mobile-menu-link">
                    <i class="bi bi-box-seam"></i>
                    <span>Stok Sparepart</span>
                </a>

                <div class="mobile-menu-divider"></div>
                <div class="mobile-auth-button">
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-logout-modern">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    @else
                        <a href="/login" class="btn btn-login-modern">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Login</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="page-content">
    <div class="container">
        @if (session('sukses'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                
                {{ session('sukses') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i> File atau data tidak dapat disimpan. </strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="page-title-card">
            <div>
                <h2><i class="bi bi-check2-square"></i> Checklist Jadwal Pemeliharaan</h2>
                <p>Jadwal pemeliharaan yang ditugaskan kepada Anda</p>
            </div>
            <div>
                <span class="user-badge">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </span>
            </div>
        </div>

        <div class="calendar-wrapper">
            <div class="custom-toolbar">
                <div class="custom-toolbar-left">
                    <button class="btn btn-custom-view" id="btnPrev"><i class="bi bi-chevron-left"></i></button>
                    <button class="btn btn-custom-view" id="btnNext"><i class="bi bi-chevron-right"></i></button>
                    <button class="btn btn-custom-view" id="btnToday">Hari Ini</button>
                    <h4 id="calendarTitle" class="ms-3 mb-0 fw-bold" style="font-size: 1.2rem; color: #2d3748;"></h4>
                </div>
                <div class="custom-toolbar-right">
                    <button class="btn btn-custom-view active" id="btnBulan">Bulan</button>
                    <button class="btn btn-custom-view" id="btnMinggu">Minggu</button>
                </div>
            </div>
            <div id="calendar"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5><i class="bi bi-calendar-check me-2"></i> Detail Pemeliharaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-modern" id="eventDetailBody"></div>
            <div class="modal-footer modal-footer-modern" id="eventDetailFooter"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSelesai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <form id="formSelesai" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-modern" style="background: linear-gradient(135deg, #38b2ac, #48bb78);">
                    <h5><i class="bi bi-check-circle me-2"></i> Konfirmasi Selesai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Pemeliharaan</label>
                        <input type="text" class="form-control" id="selesai_judul" readonly>
                    </div>
                    <!-- ==========================================
                        FORM MULTI-SPAREPART PEMELIHARAAN
                        ========================================== -->
                    <div class="mb-3" id="bagian_multi_sparepart">
                        <label class="form-label fw-semibold"> Sparepart yang Digunakan <small class="text-muted">(opsional)</small></label>
                        <p class="text-muted small mb-2">
                            Tambahkan setiap jenis sparepart beserta jumlah unit yang digunakan dalam pemeliharaan.
                        </p>
                        <!-- Tempat menampilkan daftar sparepart -->
                        <div id="daftar_sparepart_pemeliharaan"></div>
                        <!-- Tombol menambahkan jenis sparepart -->
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="tambah_sparepart_pemeliharaan">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Jenis Sparepart
                        </button>
                    </div>
                    <div class="mb-3 d-none">
                        <label class="form-label fw-semibold">Nama Sparepart <small class="text-muted">(opsional)</small></label>
                        <select class="form-control" name="nama_onderdil" id="select_nama_onderdil" disabled>
                            <option value="">-- Tidak menggunakan sparepart --</option>
                            @foreach($stokOnderdil as $stok)
                                <option value="{{ $stok->nama_sparepart }}">{{ $stok->nama_sparepart }} (Stok: {{ $stok->stok_terbaru }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-none" id="wrapper_bukti_onderdil">
                        <div class="row">
                            <div class="col-md-4 mb-3 d-none">
                                <label class="form-label fw-semibold text-danger">Jumlah Pemakaian <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="jumlah_pemakaian" id="input_jumlah_pemakaian" min="1" value="1" disabled>
                                <small class="text-muted">Berapa unit yang dipakai?</small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold text-danger">Upload Bukti Pemakaian <span class="text-danger">*</span></label>
                                <input type="file" class="form-control validasi-gambar" id="bukti_pemakaian_onderdil" name="bukti_pemakaian_onderdil" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                <small class="text-muted d-block mt-1"> Wajib diisi karena Anda memilih sparepart.</small>
                                <small class="text-muted d-block"> Format file: JPG, JPEG, PNG, atau GIF. Maksimal 5 MB. </small>
                                <div id="error_bukti_pemakaian_onderdil"
                                    class="text-danger small mt-1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Detail Penyelesaian <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="detail_penyelesaian" rows="4" placeholder="Jelaskan detail penyelesaian..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gambar <small class="text-muted">(Bukti Penyelesaian Alat)</small></label>
                        <input type="file" class="form-control validasi-gambar" id="gambar_bukti" name="gambar_bukti" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                        <small class="text-muted d-block mt-1"> Format file: JPG, JPEG, PNG, atau GIF. Maksimal 5 MB. </small>
                        <div id="error_gambar_bukti"
                            class="text-danger small mt-1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-selesai-action">
                        <i class="bi bi-check-circle"></i> Simpan Selesai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <form id="formTolak" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-modern" style="background: linear-gradient(135deg, #fc5c7d, #e53e3e);">
                    <h5><i class="bi bi-x-circle me-2"></i> Tolak Pemeliharaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Pemeliharaan</label>
                        <input type="text" class="form-control" id="tolak_judul" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="keterangan_tolak" rows="3" placeholder="Contoh: Stok sparepart kosong" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alasan_penolakan" rows="3" placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tolak-action">
                        <i class="bi bi-x-circle"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="footer-modern">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-tools" style="font-size: 2rem; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    <span class="fw-bold fs-4">Manajemen Pemeliharaan</span>
                </div>
                <p class="text-secondary mb-3">Sistem informasi manajemen yang mengintegrasikan pemeliharaan peralatan dengan sparepart pada PLTMH Sangir Hulu</p>
            </div>
            <div class="col-lg-3 offset-lg-1">
                <h5 class="footer-title">Menu Utama</h5>
                <ul class="footer-menu">
                    <li><a href="{{ route('tenagakerja.dashboard') }}"><i class="bi bi-house"></i> Dashboard</a></li>
                    @if(in_array(Auth::user()->jenis_tugas, ['pemeliharaan_peralatan', 'keduanya']))
                        <li><a href="{{ route('tenagakerja.checklist.index') }}"><i class="bi bi-check2-square"></i> Jadwal Pemeliharaan</a></li>
                    @endif
                    @if(in_array(Auth::user()->jenis_tugas, ['pengecekan_onderdil', 'keduanya']))
                        <li><a href="{{ route('tenagakerja.pengecekan-onderdil.index') }}"><i class="bi bi-clipboard-check"></i> Pengecekan Sparepart</a></li>
                    @endif
                    <li><a href="{{ route('tenagakerja.stok-onderdil.index') }}"><i class="bi bi-box-seam"></i> Stok Sparepart</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h5 class="footer-title">Kontak</h5>
                <ul class="footer-menu">
                    <li>
                        <a href="https://maps.app.goo.gl/FwQrd4ReV9ooRBoq9" target="_blank">
                            <i class="bi bi-geo-alt"></i> Jorong Letter W, Nagari Lubuk Gadang Tim., Kec. Sangir, Kabupaten Solok Selatan, Prov. Sumatera Barat
                        </a>
                    </li>
                    <li><i class="bi bi-envelope"></i> <a href="mailto:adminpemeliharaan.pltmsh@gmail.com">adminpemeliharaan.pltmsh@gmail.com</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-6 text-md-start">
                    <i class="bi bi-c-circle me-1" style="color: #667eea;"></i> 2026 Manajemen Pemeliharaan. All rights reserved.
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="me-3"><i class="bi bi-shield-lock" style="color: #667eea;"></i> Secure System</span>
                    <span><i class="bi bi-arrow-repeat" style="color: #667eea;"></i> Real-time Updates</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var events = @json($events);

        // Tanggal hari ini berdasarkan waktu PLTMH (WIB)
        var tanggalHariIni = @json(now('Asia/Jakarta')->format('Y-m-d'));

        // Mengubah misal 2026-11-09 menjadi 9 November 2026
        function formatTanggal(tanggal) {
            if (!tanggal) return '-';
            var bagian = tanggal.substring(0, 10).split('-');
            var tanggalObj = new Date(Number(bagian[0]), Number(bagian[1]) - 1, Number(bagian[2]));
            return tanggalObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        // Mengubah tanggal pengiriman menjadi tanggal + jam
        function formatTanggalJam(tanggal) {
            if (!tanggal) return 'Belum tercatat';
            var bagian = tanggal.split(' ');
            return formatTanggal(bagian[0]) + (bagian[1] ? ', ' + bagian[1] + ' WIB' : '');
        }

        // Menghitung selisih hari menggunakan tanggal kalender
        function selisihHari(tanggalTarget, tanggalAktual) {
            if (!tanggalTarget || !tanggalAktual) return 0;
            var awal = tanggalTarget.substring(0, 10).split('-').map(Number);
            var akhir = tanggalAktual.substring(0, 10).split('-').map(Number);
            var waktuAwal = Date.UTC(awal[0], awal[1] - 1, awal[2]);
            var waktuAkhir = Date.UTC(akhir[0], akhir[1] - 1, akhir[2]);

            return Math.round((waktuAkhir - waktuAwal) / 86400000);
        }

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: false,
            events: events,
            views: { dayGridMonth: { dayMaxEvents: 3 }, dayGridWeek: { dayMaxEvents: 4 }},
            eventClick: function (info) {
                var props = info.event.extendedProps;
                var eventId = info.event.id;
                var status = props.status || 'pending';

                var html = '';
                html += '<div class="detail-row"><div class="detail-label">Judul</div><div class="detail-value"><strong>' + info.event.title + '</strong></div></div>';
                html += '<div class="detail-row"><div class="detail-label">Batas Penyelesaian</div><div class="detail-value">' + info.event.start.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) + '</div></div>';
                html += '<div class="detail-row"><div class="detail-label">Nama Peralatan</div><div class="detail-value">' + (props.nama_peralatan || '-') + '</div></div>';
                html += '<div class="detail-row"><div class="detail-label">Lokasi</div><div class="detail-value">' + (props.lokasi || '-') + '</div></div>';
                html += '<div class="detail-row"><div class="detail-label">Quantity</div><div class="detail-value">' + (props.quantity || '-') + '</div></div>';
                html += '<div class="detail-row"><div class="detail-label">Serial Number</div><div class="detail-value">' + (props.serial_number || '-') + '</div></div>';
                html += '<div class="detail-row"><div class="detail-label">Kapasitas</div><div class="detail-value">' + (props.kapasitas || '-') + '</div></div>';
                html += '<div class="detail-row"><div class="detail-label">Merek</div><div class="detail-value">' + (props.merek || '-') + '</div></div>';
                html += '<div class="detail-row"><div class="detail-label">Tipe</div><div class="detail-value">' + (props.tipe || '-') + '</div></div>';
                html += '<div class="detail-row"><div class="detail-label">Tahun Pembuatan</div><div class="detail-value">' + (props.tahun_pembuatan || '-') + '</div></div>';
                html += '<div class="detail-row"><div class="detail-label">Keterangan</div><div class="detail-value">' + (props.keterangan || '-') + '</div></div>';
                if (props.gambar) {
                    html += '<div class="detail-row"><div class="detail-label">Gambar</div><div class="detail-value"><img src="/' + props.gambar + '" alt="Gambar"></div></div>';
                }

                // ============================================
                // INFORMASI KETEPATAN WAKTU PEMELIHARAAN
                // ============================================
                // Tanggal target aktif mengikuti jadwal terbaru.
                var targetAktif = info.event.startStr.substring(0, 10);

                // Target awal tidak berubah walaupun pernah direvisi.
                var targetAwal = props.tanggal_target_awal || targetAktif;

                // Waktu penyelesaian dan verifikasi.
                var tanggalKirim = props.tanggal_kirim_hasil;
                var tanggalVerifikasi = props.tanggal_verifikasi;

                // Hasil perhitungan dari helper Laravel.
                var teksKetepatan = props.ketepatan_label || 'Informasi belum tersedia';
                var warnaKetepatan = 'text-secondary';

                if (props.ketepatan_warna === 'danger') {
                    warnaKetepatan = 'text-danger';
                } else if (props.ketepatan_warna === 'success') {
                    warnaKetepatan = 'text-success';
                }

                // ============================================
                // PENANGANAN PENOLAKAN TUGAS
                // ============================================
                /* Penolakan tugas tidak dianggap sebagai pengiriman hasil pekerjaan. */
                var pengajuanPenolakan = (status === 'menunggu_verifikasi' || status === 'ditolak') && !props.detail_penyelesaian && Boolean(props.alasan_penolakan);

                if (pengajuanPenolakan) {
                    teksKetepatan = 'Penolakan Tugas - Tidak Dinilai';
                    warnaKetepatan = 'text-secondary';
                }

                // Riwayat perubahan jadwal.
                var jumlahRevisi = Number(props.jumlah_revisi || 0);
                var pernahTerlambat = props.pernah_terlambat_sebelum_revisi === true;
                var hariTerlambatTercatat = Number(props.hari_terlambat_tercatat || 0);
                var daftarRiwayat = Array.isArray(props.riwayat_revisi_detail) ? props.riwayat_revisi_detail : [];

                // ============================================
                // TAMPILAN INFORMASI KETEPATAN WAKTU
                // ============================================
                html += '<div class="result-section" style="background:#f3f6ff;border-left:4px solid #667eea;">';
                html += '<h6 style="color:#4a5568;">' + '<i class="bi bi-clock-history me-2"></i>' + 'Ketepatan Waktu Pemeliharaan' + '</h6>';
                // Target awal.
                html += '<div class="detail-row">' + '<div class="detail-label">Target Awal</div>' + '<div class="detail-value">' + formatTanggal(targetAwal) + '</div></div>';
                // Target aktif ditampilkan jika pernah ada revisi.
                if (jumlahRevisi > 0 || targetAwal !== targetAktif) {
                    html += '<div class="detail-row">' + '<div class="detail-label">Target Setelah Revisi</div>' + '<div class="detail-value">' + formatTanggal(targetAktif) + '</div></div>';
                }
                // Tanggal pengiriman hasil.
                html += '<div class="detail-row">' + '<div class="detail-label">Hasil Dikirim</div>' + '<div class="detail-value">' + formatTanggalJam(tanggalKirim) + '</div></div>';
                // Tanggal verifikasi Supervisor.
                html += '<div class="detail-row">' + '<div class="detail-label">Verifikasi Supervisor</div>' + '<div class="detail-value">' + formatTanggalJam(tanggalVerifikasi) + '</div></div>';
                // Status ketepatan waktu dari helper.
                html += '<div class="detail-row">' + '<div class="detail-label">Ketepatan Waktu</div>' + '<div class="detail-value">' + '<strong class="' + warnaKetepatan + '">' + teksKetepatan + '</strong></div></div>';
                // Keterlambatan sebelum revisi.
                if (pernahTerlambat) {
                    html += '<div class="detail-row">' + '<div class="detail-label">Riwayat Terlambat</div>' + '<div class="detail-value text-danger">' + '<i class="bi bi-exclamation-triangle me-1"></i>' + hariTerlambatTercatat + ' hari' + ' (keterlambatan terbesar sebelum revisi).' + '</div></div>';
                }
                // Jumlah revisi.
                if (jumlahRevisi > 0) {
                    html += '<div class="detail-row">' + '<div class="detail-label">Jumlah Revisi</div>' + '<div class="detail-value">' + jumlahRevisi + ' kali' + '</div></div>';
                }
                
                // ============================================
                // RINCIAN RIWAYAT REVISI PEMELIHARAAN
                // ============================================
                if (daftarRiwayat.length > 0) {
                    html += '<div class="mt-3">';
                    html += '<h6 class="fw-semibold mb-3">' + '<i class="bi bi-clock-history me-2"></i>' + 'Rincian Riwayat Revisi' + '</h6>';
                    daftarRiwayat.forEach(function(revisi, index) {
                        var hariTerlambat = Number(revisi.hari_terlambat || 0);
                        var informasiKeterlambatan = '';

                        if (hariTerlambat > 0) {
                            informasiKeterlambatan = '<span class="text-danger fw-semibold">' + 'Terlambat ' + hariTerlambat + ' hari sebelum revisi' + '</span>';
                        } else {
                            informasiKeterlambatan = '<span class="text-success">' + 'Tidak terlambat sebelum revisi' + '</span>';
                        }

                        // Amankan alasan revisi sebelum dimasukkan ke HTML.
                        var alasanAman = String(revisi.alasan_revisi || '-')
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#39;');

                        html += '<div class="border rounded p-3 mb-2 bg-white">';
                        html += '<div class="fw-bold mb-2">' + 'Revisi ke-' + (index + 1) + '</div>';
                        html += '<div class="detail-row">' + '<div class="detail-label">Target Sebelumnya</div>' + '<div class="detail-value">' + formatTanggal(revisi.tanggal_lama) + '</div></div>';
                        html += '<div class="detail-row">' + '<div class="detail-label">Target Baru</div>' + '<div class="detail-value">' + formatTanggal(revisi.tanggal_baru) + '</div></div>';
                        html += '<div class="detail-row">' + '<div class="detail-label">Tanggal Revisi</div>' + '<div class="detail-value">' + formatTanggalJam(revisi.direvisi_pada) + '</div></div>';
                        html += '<div class="detail-row">' + '<div class="detail-label">Keterlambatan</div>' + '<div class="detail-value">' + informasiKeterlambatan + '</div></div>';
                        html += '<div class="detail-row">' + '<div class="detail-label">Alasan Revisi</div>' + '<div class="detail-value">' + alasanAman + '</div></div>';
                        html += '</div>';
                    });
                    html += '</div>';
                }
                html += '</div>';

                if ((status === 'selesai' || status === 'menunggu_verifikasi') && props.detail_penyelesaian) {
                    html += '<div class="result-section ' + (status === 'selesai' ? 'selesai' : '') + '">';
                    html += '<h6><i class="bi bi-check-circle me-2"></i> Detail Penyelesaian</h6>';
                    // TAMPILKAN RINCIAN MULTI-SPAREPART
                    // Ambil rincian sparepart dari data kalender
                    var daftarPemakaian = Array.isArray(props.pemakaian_onderdil) ? props.pemakaian_onderdil : [];

                    // Amankan teks sebelum ditampilkan sebagai HTML
                    function amankanNamaSparepart(teks) {
                        return String(teks ?? '-')
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#39;');
                    }

                    var rincianSparepart = '-';
                    var totalPemakaian = 0;
                    if (daftarPemakaian.length > 0) {
                        // Tampilkan seluruh sparepart
                        rincianSparepart = daftarPemakaian.map(function (item, index) {
                            var nama = amankanNamaSparepart(item.nama);
                            var jumlah = Number(item.jumlah) || 0;
                            totalPemakaian += jumlah;
                            return '<div class="mb-1">' + (index + 1) + '. ' + nama + ' (' + jumlah + ' unit)' + '</div>';
                        }).join('');
                    } else if (props.nama_onderdil) {
                        // Tetap mendukung data pemeliharaan lama
                        rincianSparepart = amankanNamaSparepart(props.nama_onderdil);
                        totalPemakaian = Number(props.jumlah_pemakaian) || 0;
                    }

                    // Tampilkan daftar sparepart
                    html += '<div class="detail-row">' + '<div class="detail-label">Sparepart Dipakai</div>' + '<div class="detail-value">' + rincianSparepart + '</div></div>';
                    // Tampilkan total unit pemakaian
                    html += '<div class="detail-row">' + '<div class="detail-label">Total Pemakaian</div>' + '<div class="detail-value">' + (totalPemakaian > 0 ? totalPemakaian + ' unit' : '-') + '</div></div>';
                    html += '<div class="detail-row"><div class="detail-label">Detail Penyelesaian</div><div class="detail-value">' + (props.detail_penyelesaian || '-') + '</div></div>';
                    if (props.gambar_bukti) {
                        html += '<div class="detail-row"><div class="detail-label">Bukti Alat</div><div class="detail-value"><img src="/' + props.gambar_bukti + '" alt="Bukti"></div></div>';
                    }
                    if (props.bukti_pemakaian_onderdil) {
                        html += '<div class="detail-row"><div class="detail-label">Bukti Pemakaian Sparepart</div><div class="detail-value"><img src="/' + props.bukti_pemakaian_onderdil + '" alt="Bukti Sparepart"></div></div>';
                    }
                    html += '</div>';
                }

                // DETAIL PENOLAKAN / CATATAN DARI SUPERVISOR
                if (props.alasan_penolakan) {
                    html += '<div class="result-section ditolak">';
                    // Jika status kembali Pending karena hasil pekerjaan ditolak Supervisor
                    if (status === 'pending') {
                        html += '<h6><i class="bi bi-exclamation-triangle me-2"></i> Catatan Perbaikan dari Supervisor</h6>';
                    } else {
                        // Jika tenaga kerja sendiri mengajukan penolakan
                        html += '<h6><i class="bi bi-x-circle me-2"></i> Detail Penolakan</h6>';
                    }

                    html += '<div class="detail-row"><div class="detail-label">Keterangan</div><div class="detail-value">' + (props.keterangan_tolak || '-') + '</div></div>';
                    html += '<div class="detail-row"><div class="detail-label">Alasan Penolakan</div><div class="detail-value">' + (props.alasan_penolakan || '-') + '</div></div>';
                    html += '</div>';
                }

                document.getElementById('eventDetailBody').innerHTML = html;

                var footer = '';
                if (status === 'pending') {
                    footer += '<button type="button" class="btn btn-tolak-action" onclick="openTolakModal(' + eventId + ', \'' + info.event.title.replace(/'/g, "\\'") + '\')">';
                    footer += '<i class="bi bi-x-circle"></i> Tolak';
                    footer += '</button>';
                    footer += '<button type="button" class="btn btn-selesai-action" onclick="openSelesaiModal(' + eventId + ', \'' + info.event.title.replace(/'/g, "\\'") + '\')">';
                    footer += '<i class="bi bi-check-circle"></i> Centang (Selesai)';
                    footer += '</button>';
                } else if (status === 'selesai') {
                    footer += '<span class="status-badge selesai"><i class="bi bi-check-circle-fill"></i> Sudah diselesaikan</span>';
                } else if (status === 'menunggu_verifikasi') {
                    footer += '<span class="status-badge menunggu"><i class="bi bi-hourglass-split"></i> Menunggu Verifikasi SPV</span>';
                } else {
                    footer += '<span class="status-badge ditolak"><i class="bi bi-x-circle-fill"></i> Sudah ditolak</span>';
                }

                document.getElementById('eventDetailFooter').innerHTML = footer;

                var modal = new bootstrap.Modal(document.getElementById('eventDetailModal'));
                modal.show();
            },
            eventDidMount: function (info) {
                var status = info.event.extendedProps.status || 'pending';
                info.el.classList.add('status-' + status);

                if (status === 'selesai') {
                    info.el.querySelector('.fc-event-title').innerHTML = '✔ ' + info.event.title;
                } else if (status === 'ditolak') {
                    info.el.querySelector('.fc-event-title').innerHTML = '✘ ' + info.event.title;
                } else if (status === 'menunggu_verifikasi') {
                    info.el.querySelector('.fc-event-title').innerHTML = '⏳ ' + info.event.title;
                } else {
                    info.el.querySelector('.fc-event-title').innerHTML = '⏳ ' + info.event.title;
                }
            },
            datesSet: function(info) {
                var title = info.view.title;
                title = title.charAt(0).toUpperCase() + title.slice(1);
                document.getElementById('calendarTitle').innerText = title;
            }
        });

        calendar.render();

        document.getElementById('btnPrev').addEventListener('click', function() { calendar.prev(); });
        document.getElementById('btnNext').addEventListener('click', function() { calendar.next(); });
        document.getElementById('btnToday').addEventListener('click', function() { calendar.today(); });

        var btnBulan = document.getElementById('btnBulan');
        var btnMinggu = document.getElementById('btnMinggu');

        function setActiveButton(activeBtn) {
            [btnBulan, btnMinggu].forEach(btn => btn.classList.remove('active'));
            activeBtn.classList.add('active');
        }

        btnBulan.addEventListener('click', function() {
            calendar.setOption('height', 'auto');    
            calendar.changeView('dayGridMonth');
            setActiveButton(btnBulan);
        });

        btnMinggu.addEventListener('click', function() {
            calendar.setOption('height', 230);
            calendar.changeView('dayGridWeek');
            setActiveButton(btnMinggu);
        });
        /* ============================================
                MULTI-SPAREPART: TAMBAH BARIS INPUT
        ============================================ */
        // Ambil daftar sparepart dari database Laravel
        const pilihanStokMulti = [
            @foreach($stokOnderdil as $stok) {
                id: @json($stok->id),
                nama: @json($stok->nama_sparepart),
                tersedia: @json($stok->stok_terbaru)
            },
            @endforeach
        ];

        // Ambil elemen HTML form multi-sparepart
        const daftarSparepartMulti = document.getElementById(
            'daftar_sparepart_pemeliharaan'
        );

        const tombolTambahSparepart = document.getElementById(
            'tambah_sparepart_pemeliharaan'
        );

        // Nomor unik untuk setiap baris input
        let nomorSparepartMulti = 0;

        /* ============================================
            PENGATURAN BUKTI PEMAKAIAN MULTI-SPAREPART
        ============================================ */
        function perbaruiBuktiPemakaian() {
            const jumlahBaris = daftarSparepartMulti .querySelectorAll('.baris-sparepart').length;
            const wrapperBukti = document.getElementById('wrapper_bukti_onderdil');
            const inputBukti = document.getElementById('bukti_pemakaian_onderdil');
            const errorBukti = document.getElementById('error_bukti_pemakaian_onderdil');
            // Jika ada sparepart, bukti wajib diunggah
            if (jumlahBaris > 0) {
                wrapperBukti.classList.remove('d-none');
                inputBukti.required = true;
            } else {
                // Jika tidak ada sparepart, sembunyikan bukti
                wrapperBukti.classList.add('d-none');
                inputBukti.required = false;
                inputBukti.value = '';
                if (errorBukti) {
                    errorBukti.innerHTML = '';
                }
            }
        }

        /* ============================================
                RESET FORM MULTI-SPAREPART
        ============================================ */
        window.resetMultiSparepart = function () {
            // Hapus semua baris sparepart
            daftarSparepartMulti.innerHTML = '';
            // Kembalikan nomor baris ke awal
            nomorSparepartMulti = 0;
            // Perbarui tampilan bukti pemakaian
            perbaruiBuktiPemakaian();
        };

        // Jalankan ketika tombol Tambah Jenis Sparepart diklik
        tombolTambahSparepart.addEventListener('click', function () {
            const nomor = nomorSparepartMulti++;
            // Buat wadah untuk satu baris sparepart
            const baris = document.createElement('div');
            baris.className = 'baris-sparepart border rounded p-3 mb-2 bg-white';
            // Buat input nama dan jumlah sparepart
            baris.innerHTML = `
                <div class="row g-2 align-items-end">
                    <div class="col-md-7">
                        <label class="form-label">Nama Sparepart</label>
                        <select class="form-select pilihan-sparepart" name="spareparts[${nomor}][stok_onderdil_id]" required>
                            <option value="">-- Pilih Sparepart --</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jumlah Pemakaian</label>
                        <input type="number" class="form-control" name="spareparts[${nomor}][jumlah]" min="1" value="1" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 hapus-sparepart"> Hapus </button>
                    </div>
                </div>
            `;

            // Isi pilihan sparepart berdasarkan stok database
            const select = baris.querySelector('.pilihan-sparepart');
            pilihanStokMulti.forEach(function (stok) {
                const option = document.createElement('option');
                option.value = stok.id;
                option.textContent = stok.nama + ' (Stok: ' + stok.tersedia + ')';
                select.appendChild(option);
            });

            // Masukkan baris baru ke dalam form
            daftarSparepartMulti.appendChild(baris);

            // Perbarui tampilan bukti pemakaian
            perbaruiBuktiPemakaian();

            // Tombol hapus hanya menghapus baris yang dipilih
            baris.querySelector('.hapus-sparepart') .addEventListener('click', function () {
                baris.remove();
                // Perbarui bukti setelah baris dihapus
                perbaruiBuktiPemakaian();
            });
        });
        
        /* ============================================
                  VALIDASI DUPLIKAT SPAREPART
        ============================================ */
        document.getElementById('formSelesai') .addEventListener('submit', function (event) {
            // Ambil seluruh pilihan sparepart dari form baru
            const pilihan = daftarSparepartMulti
                .querySelectorAll('.pilihan-sparepart');

            // Ambil ID sparepart yang dipilih
            const idSparepart = Array.from(pilihan)
                .map(function (select) {
                    return select.value;
                })
                .filter(function (id) {
                    return id !== '';
                });

            // Periksa apakah ada ID sparepart yang sama
            const idUnik = new Set(idSparepart);
            if (idUnik.size !== idSparepart.length) {
                // Batalkan pengiriman checklist
                event.preventDefault();
                alert(
                    'Jenis sparepart yang sama tidak boleh dipilih lebih dari satu kali. ' +
                    'Silakan pilih jenis sparepart berbeda.'
                );
                return;
            }
        });

        var selectOnderdil = document.getElementById('select_nama_onderdil');
        var wrapperBukti = document.getElementById('wrapper_bukti_onderdil');
        var inputBukti = wrapperBukti.querySelector('input[type="file"]');
        var inputJumlah = document.getElementById('input_jumlah_pemakaian');

        selectOnderdil.addEventListener('change', function() {
            if (this.value !== "") {
                wrapperBukti.classList.remove('d-none');
                inputBukti.setAttribute('required', 'required');
                inputJumlah.setAttribute('required', 'required');
            } else {
                wrapperBukti.classList.add('d-none');
                inputBukti.removeAttribute('required');
                inputJumlah.removeAttribute('required');
                inputBukti.value = '';
                inputJumlah.value = 1;
            }
        });
        
        document.querySelectorAll('.validasi-gambar').forEach(function(input) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                const errorElement =
                    document.getElementById('error_' + this.id);
                errorElement.innerHTML = '';
                if (!file) {
                    return;
                }
                // Maksimal 5 MB
                const maxSize = 5 * 1024 * 1024;
                // Format yang diperbolehkan
                const allowedExtensions = [
                    'jpg',
                    'jpeg',
                    'png',
                    'gif'
                ];
                const fileName = file.name.toLowerCase();
                const extension =
                    fileName.split('.').pop();
                // CEK FORMAT FILE
                if (!allowedExtensions.includes(extension)) {
                    errorElement.innerHTML =
                        '<i class="bi bi-exclamation-circle"></i> ' +
                        'Format file tidak diperbolehkan. ' +
                        'Gunakan JPG, JPEG, PNG, atau GIF.';
                    // Hapus file dari input
                    this.value = '';
                    return;
                }
                // CEK UKURAN FILE
                if (file.size > maxSize) {
                    errorElement.innerHTML =
                        '<i class="bi bi-exclamation-circle"></i> ' +
                        'Ukuran file terlalu besar. ' +
                        'Ukuran maksimal file adalah 5 MB.';
                    // Hapus file dari input
                    this.value = '';
                    return;
                }
                // Jika file valid
                errorElement.innerHTML =
                    '<span class="text-success">' +
                    '<i class="bi bi-check-circle"></i> ' +
                    'File sesuai. Siap diunggah.' +
                    '</span>';
            });
        });
    });

    function openSelesaiModal(id, judul) {
        document.getElementById('selesai_judul').value = judul;
        var form = document.getElementById('formSelesai');
        form.action = '/tenagakerja/checklist/' + id + '/selesai';
        var detailModal = bootstrap.Modal.getInstance(document.getElementById('eventDetailModal'));
        if (detailModal) detailModal.hide();
        
        document.getElementById('select_nama_onderdil').value = "";
        document.getElementById('wrapper_bukti_onderdil').classList.add('d-none');
        document.getElementById('wrapper_bukti_onderdil').querySelector('input[type="file"]').removeAttribute('required');
        document.getElementById('input_jumlah_pemakaian').removeAttribute('required');
        document.getElementById('input_jumlah_pemakaian').value = 1;
        
        // Reset input pada form Konfirmasi Selesai
        form.reset();

        // Isi kembali judul setelah form di-reset
        document.getElementById('selesai_judul').value = judul;

        // Bersihkan seluruh baris multi-sparepart
        window.resetMultiSparepart();

        // Bersihkan notifikasi validasi file sebelumnya
        document.getElementById('error_gambar_bukti').innerHTML = '';
        document.getElementById('error_bukti_pemakaian_onderdil').innerHTML = '';

        var modal = new bootstrap.Modal(document.getElementById('modalSelesai'));
        modal.show();
    }

    function openTolakModal(id, judul) {
        document.getElementById('tolak_judul').value = judul;
        var form = document.getElementById('formTolak');
        form.action = '/tenagakerja/checklist/' + id + '/tolak';
        var detailModal = bootstrap.Modal.getInstance(document.getElementById('eventDetailModal'));
        if (detailModal) detailModal.hide();
        var modal = new bootstrap.Modal(document.getElementById('modalTolak'));
        modal.show();
    }
</script>
</body>
</html>
