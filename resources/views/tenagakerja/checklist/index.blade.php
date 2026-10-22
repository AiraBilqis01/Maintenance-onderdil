<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checklist Pemeliharaan</title>
    
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
        .page-content { padding: 7rem 0 4rem 0; position: relative; z-index: 2; }
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
        @media (max-width: 992px) {
            .nav-menu { display: none; }
            .page-title-card { padding: 1.5rem; }
            .page-title-card h2 { font-size: 1.3rem; }
            .calendar-wrapper { padding: 1rem; }
            .custom-toolbar { flex-direction: column; align-items: stretch; }
            .custom-toolbar-left, .custom-toolbar-right { justify-content: center; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-blur fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('tenagakerja.dashboard') }}">
            <i class="bi bi-tools"></i>
            <span>Manajemen<b>Onderdil</b></span>
        </a>
        <div class="nav-menu">
            <a href="{{ route('tenagakerja.dashboard') }}" class="nav-link"><i class="bi bi-house"></i> Dashboard</a>
            <a href="{{ route('tenagakerja.checklist.index') }}" class="nav-link"><i class="bi bi-check2-square"></i> Checklist</a>
            <a href="{{ route('tenagakerja.stok-onderdil.index') }}" class="nav-link"><i class="bi bi-box-seam"></i> Stok Onderdil</a>
        </div>
        <div class="d-flex gap-2 align-items-center">
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
    </div>
</nav>

<div class="page-content">
    <div class="container">
        <div class="page-title-card">
            <div>
                <h2><i class="bi bi-check2-square"></i> Checklist Pemeliharaan</h2>
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
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Onderdil <small class="text-muted">(opsional)</small></label>
                        <select class="form-control" name="nama_onderdil" id="select_nama_onderdil">
                            <option value="">-- Tidak menggunakan onderdil --</option>
                            @foreach($stokOnderdil as $stok)
                                <option value="{{ $stok->nama_sparepart }}">{{ $stok->nama_sparepart }} (Stok: {{ $stok->stok_terbaru }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-none" id="wrapper_bukti_onderdil">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold text-danger">Jumlah Pemakaian <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="jumlah_pemakaian" id="input_jumlah_pemakaian" min="1" value="1">
                                <small class="text-muted">Berapa unit yang dipakai?</small>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-semibold text-danger">Upload Bukti Pemakaian <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="bukti_pemakaian_onderdil" accept="image/*">
                                <small class="text-muted">Wajib diisi karena Anda memilih onderdil.</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_selesai" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Detail Penyelesaian <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="detail_penyelesaian" rows="4" placeholder="Jelaskan detail penyelesaian..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gambar <small class="text-muted">(Bukti Penyelesaian Alat)</small></label>
                        <input type="file" class="form-control" name="gambar_bukti" accept="image/*">
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
                        <textarea class="form-control" name="keterangan_tolak" rows="3" placeholder="Contoh: Stok onderdil kosong" required></textarea>
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
                    <span class="fw-bold fs-4">Manajemen Onderdil</span>
                </div>
                <p class="text-secondary mb-3">Sistem informasi manajemen yang mengintegrasikan pemeliharaan peralatan dengan onderdil pada PLTMH Sangir Hulu</p>
            </div>
            <div class="col-lg-3 offset-lg-1">
                <h5 class="footer-title">Menu Utama</h5>
                <ul class="footer-menu">
                    <li><a href="{{ route('tenagakerja.dashboard') }}"><i class="bi bi-house"></i> Dashboard</a></li>
                    <li><a href="{{ route('tenagakerja.checklist.index') }}"><i class="bi bi-check2-square"></i> Checklist</a></li>
                    <li><a href="#"><i class="bi bi-box-seam"></i> Stok Onderdil</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h5 class="footer-title">Kontak</h5>
                <ul class="footer-menu">
                    <li>
                        <a href="https://maps.app.goo.gl/FwQrd4ReV9ooRBoq9" target="_blank">
                            <i class="bi bi-geo-alt"></i> Jl. Industri No. 123, Jakarta
                        </a>
                    </li>
                    <li><i class="bi bi-envelope"></i> info@manajemenonderdil.id</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-6 text-md-start">
                    <i class="bi bi-c-circle me-1" style="color: #667eea;"></i> 2026 Manajemen Onderdil. All rights reserved.
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

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: false,
            events: events,
            eventClick: function (info) {
                var props = info.event.extendedProps;
                var eventId = info.event.id;
                var status = props.status || 'pending';

                var html = '';
                html += '<div class="detail-row"><div class="detail-label">Judul</div><div class="detail-value"><strong>' + info.event.title + '</strong></div></div>';
                html += '<div class="detail-row"><div class="detail-label">Tanggal Selesai</div><div class="detail-value">' + info.event.start.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) + '</div></div>';
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

                if (status === 'selesai' || status === 'menunggu_verifikasi') {
                    html += '<div class="result-section ' + (status === 'selesai' ? 'selesai' : '') + '">';
                    html += '<h6><i class="bi bi-check-circle me-2"></i> Detail Penyelesaian</h6>';
                    html += '<div class="detail-row"><div class="detail-label">Nama Onderdil</div><div class="detail-value">' + (props.nama_onderdil || '-') + '</div></div>';
                    html += '<div class="detail-row"><div class="detail-label">Jumlah Pemakaian</div><div class="detail-value">' + (props.jumlah_pemakaian ? props.jumlah_pemakaian + ' unit' : '-') + '</div></div>';
                    html += '<div class="detail-row"><div class="detail-label">Detail Penyelesaian</div><div class="detail-value">' + (props.detail_penyelesaian || '-') + '</div></div>';
                    if (props.gambar_bukti) {
                        html += '<div class="detail-row"><div class="detail-label">Bukti Alat</div><div class="detail-value"><img src="/' + props.gambar_bukti + '" alt="Bukti"></div></div>';
                    }
                    if (props.bukti_pemakaian_onderdil) {
                        html += '<div class="detail-row"><div class="detail-label">Bukti Pemakaian Onderdil</div><div class="detail-value"><img src="/' + props.bukti_pemakaian_onderdil + '" alt="Bukti Onderdil"></div></div>';
                    }
                    html += '</div>';
                }

                if (status === 'ditolak' || status === 'menunggu_verifikasi') {
                    if (props.alasan_penolakan) {
                        html += '<div class="result-section ditolak">';
                        html += '<h6><i class="bi bi-x-circle me-2"></i> Detail Penolakan</h6>';
                        html += '<div class="detail-row"><div class="detail-label">Keterangan</div><div class="detail-value">' + (props.keterangan_tolak || '-') + '</div></div>';
                        html += '<div class="detail-row"><div class="detail-label">Alasan Penolakan</div><div class="detail-value">' + (props.alasan_penolakan || '-') + '</div></div>';
                        html += '</div>';
                    }
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
            calendar.changeView('dayGridMonth');
            setActiveButton(btnBulan);
        });

        btnMinggu.addEventListener('click', function() {
            calendar.changeView('timeGridWeek');
            setActiveButton(btnMinggu);
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