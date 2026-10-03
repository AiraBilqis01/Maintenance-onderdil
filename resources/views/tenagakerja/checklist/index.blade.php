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
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
    <style>
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
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(102, 126, 234, 0.05) 0%, transparent 30%),
                        radial-gradient(circle at 90% 70%, rgba(118, 75, 162, 0.06) 0%, transparent 35%),
                        radial-gradient(circle at 30% 80%, rgba(102, 126, 234, 0.04) 0%, transparent 40%);
            pointer-events: none;
            z-index: 0;
        }

        .navbar-blur {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.1);
            padding: 16px 0;
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            position: relative;
            z-index: 10;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.02em;
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
            background-clip: text;
            filter: drop-shadow(0 4px 6px rgba(102, 126, 234, 0.3));
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            margin-left: 2rem;
        }
        .nav-menu .nav-link {
            color: #4a5568;
            font-weight: 500;
            padding: 0.5rem 0;
            position: relative;
            transition: all 0.3s;
            text-decoration: none;
        }
        .nav-menu .nav-link:hover,
        .nav-menu .nav-link.active {
            color: #667eea;
        }
        .nav-menu .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transition: width 0.3s;
        }
        .nav-menu .nav-link:hover::after,
        .nav-menu .nav-link.active::after {
            width: 100%;
        }
        .nav-menu .nav-link i {
            margin-right: 5px;
            font-size: 1.1rem;
        }

        .btn-login-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 60px;
            padding: 12px 36px;
            font-weight: 500;
            border: none;
            transition: 0.25s ease;
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 10;
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
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 10;
        }
        .btn-logout-modern:hover {
            background: linear-gradient(135deg, #e04a6a 0%, #5872e8 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(252, 92, 125, 0.4);
        }

        .page-content {
            padding: 7rem 0 4rem 0;
            position: relative;
            z-index: 2;
        }

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

        .page-title-card h2 {
            font-weight: 700;
            color: #2d3748;
            margin: 0;
            font-size: 1.7rem;
        }

        .page-title-card h2 i {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-right: 10px;
        }

        .page-title-card p {
            color: #718096;
            margin: 0.3rem 0 0 0;
            font-size: 0.95rem;
        }

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

        .calendar-wrapper {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 40px -20px rgba(102, 126, 234, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        #calendar {
            font-family: 'Poppins', sans-serif;
        }

        .fc .fc-toolbar-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
        }

        .fc .fc-button-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            text-transform: capitalize;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.2);
        }

        .fc .fc-button-primary:hover {
            background: linear-gradient(135deg, #5a6fd8, #6845a0);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(102, 126, 234, 0.3);
        }

        .fc .fc-button-primary:disabled {
            background: #cbd5e0;
            box-shadow: none;
        }

        .fc .fc-button-primary:not(:disabled):active,
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: linear-gradient(135deg, #4c5bb8, #5b3d8f);
        }

        .fc-daygrid-day-number,
        .fc-col-header-cell-cushion {
            color: #4a5568;
            font-weight: 500;
            text-decoration: none;
        }

        .fc-day-today {
            background: rgba(102, 126, 234, 0.08) !important;
        }

        .fc-event {
            border: none;
            border-radius: 8px;
            padding: 3px 6px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            background: linear-gradient(135deg, #667eea, #764ba2);
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
        }

        .fc-event:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
        }

        .fc-event.status-selesai {
            background: linear-gradient(135deg, #38b2ac, #48bb78) !important;
            box-shadow: 0 2px 6px rgba(72, 187, 120, 0.4);
        }

        .fc-event.status-ditolak {
            background: linear-gradient(135deg, #fc5c7d, #e53e3e) !important;
            box-shadow: 0 2px 6px rgba(229, 62, 62, 0.4);
        }

        .fc-event.status-pending {
            background: linear-gradient(135deg, #667eea, #764ba2) !important;
        }

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

        .modal-header-modern h5 {
            font-weight: 600;
            margin: 0;
            font-size: 1.2rem;
        }

        .modal-header-modern .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.9;
        }

        .modal-body-modern {
            padding: 2rem;
            background: #fafbff;
        }

        .detail-row {
            display: flex;
            padding: 0.7rem 0;
            border-bottom: 1px solid #eef2f7;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 160px;
            font-size: 0.9rem;
        }

        .detail-value {
            color: #2d3748;
            font-size: 0.9rem;
            flex: 1;
        }

        .detail-value img {
            max-width: 200px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .modal-footer-modern {
            background: #fafbff;
            border-top: 1px solid #eef2f7;
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

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

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1.1rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .status-badge.selesai {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-badge.ditolak {
            background: #fed7d7;
            color: #822727;
        }

        .status-badge.pending {
            background: #fefcbf;
            color: #744210;
        }

        .result-section {
            margin-top: 1.5rem;
            padding: 1.2rem;
            border-radius: 14px;
        }

        .result-section.selesai {
            background: #f0fff4;
            border-left: 4px solid #48bb78;
        }

        .result-section.ditolak {
            background: #fff5f5;
            border-left: 4px solid #e53e3e;
        }

        .result-section h6 {
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .result-section.selesai h6 { color: #22543d; }
        .result-section.ditolak h6 { color: #822727; }

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

        .footer-title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1.2rem;
            font-size: 1.1rem;
        }

        .footer-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-menu li {
            margin-bottom: 0.8rem;
        }

        .footer-menu a {
            color: #4a5568;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .footer-menu a:hover {
            color: #667eea;
            transform: translateX(5px);
        }

        .footer-menu i {
            color: #667eea;
            font-size: 1rem;
        }

        .footer-bottom {
            border-top: 1px solid rgba(102, 126, 234, 0.2);
            padding-top: 1.5rem;
            margin-top: 2rem;
            text-align: center;
            color: #718096;
        }

        .social-icons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .social-icons a {
            width: 36px;
            height: 36px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            transition: all 0.3s;
        }

        .social-icons a:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-3px);
        }

        @media (max-width: 992px) {
            .nav-menu { display: none; }
            .social-icons { justify-content: center; margin-top: 1rem; }
            .page-title-card { padding: 1.5rem; }
            .page-title-card h2 { font-size: 1.3rem; }
            .calendar-wrapper { padding: 1rem; }
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
            <a href="#" class="nav-link"><i class="bi bi-calendar-week"></i> Jadwal</a>
            <a href="{{ route('tenagakerja.checklist.index') }}" class="nav-link active"><i class="bi bi-check2-square"></i> Checklist</a>
            <a href="#" class="nav-link"><i class="bi bi-box-seam"></i> Stok Onderdil</a>
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
            <div class="modal-body modal-body-modern" id="eventDetailBody">
            </div>
            <div class="modal-footer modal-footer-modern" id="eventDetailFooter">
            </div>
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
                        <input type="text" class="form-control" name="nama_onderdil" placeholder="Tergantung kerusakan">
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
                        <label class="form-label fw-semibold">Gambar <small class="text-muted">(Bukti Penyelesaian)</small></label>
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
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-tools" style="font-size: 2rem; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    <span class="fw-bold fs-4">Manajemen Onderdil</span>
                </div>
                <p class="text-secondary mb-3">Sistem informasi manajemen onderdil terintegrasi untuk bengkel dan distributor di seluruh Indonesia.</p>
                <div class="social-icons">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            <div class="col-lg-2 offset-lg-1">
                <h5 class="footer-title">Menu Utama</h5>
                <ul class="footer-menu">
                    <li><a href="#"><i class="bi bi-calendar-week"></i> Jadwal</a></li>
                    <li><a href="{{ route('tenagakerja.checklist.index') }}"><i class="bi bi-check2-square"></i> Checklist</a></li>
                    <li><a href="#"><i class="bi bi-box-seam"></i> Stok Onderdil</a></li>
                </ul>
            </div>

            <div class="col-lg-2">
                <h5 class="footer-title">Lainnya</h5>
                <ul class="footer-menu">
                    <li><a href="#"><i class="bi bi-info-circle"></i> Tentang Kami</a></li>
                    <li><a href="#"><i class="bi bi-envelope"></i> Kontak</a></li>
                    <li><a href="#"><i class="bi bi-question-circle"></i> Bantuan</a></li>
                    <li><a href="#"><i class="bi bi-shield-check"></i> Privasi</a></li>
                </ul>
            </div>

            <div class="col-lg-3">
                <h5 class="footer-title">Kontak</h5>
                <ul class="footer-menu">
                    <li><i class="bi bi-geo-alt"></i> Jl. Industri No. 123, Jakarta</li>
                    <li><i class="bi bi-telephone"></i> (021) 1234-5678</li>
                    <li><i class="bi bi-envelope"></i> info@manajemenonderdil.id</li>
                    <li><i class="bi bi-clock"></i> Senin - Jumat, 08:00 - 17:00</li>
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
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                list: 'Daftar'
            },
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

                if (status === 'selesai') {
                    html += '<div class="result-section selesai">';
                    html += '<h6><i class="bi bi-check-circle me-2"></i> Detail Penyelesaian</h6>';
                    html += '<div class="detail-row"><div class="detail-label">Nama Onderdil</div><div class="detail-value">' + (props.nama_onderdil || '-') + '</div></div>';
                    html += '<div class="detail-row"><div class="detail-label">Detail Penyelesaian</div><div class="detail-value">' + (props.detail_penyelesaian || '-') + '</div></div>';
                    if (props.gambar_bukti) {
                        html += '<div class="detail-row"><div class="detail-label">Bukti</div><div class="detail-value"><img src="/' + props.gambar_bukti + '" alt="Bukti"></div></div>';
                    }
                    html += '</div>';
                }

                if (status === 'ditolak') {
                    html += '<div class="result-section ditolak">';
                    html += '<h6><i class="bi bi-x-circle me-2"></i> Detail Penolakan</h6>';
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
                } else {
                    info.el.querySelector('.fc-event-title').innerHTML = '⏳ ' + info.event.title;
                }

                info.el.setAttribute('title', info.event.title + ' — ' + status);
            }
        });

        calendar.render();
    });

    function openSelesaiModal(id, judul) {
        document.getElementById('selesai_judul').value = judul;
        var form = document.getElementById('formSelesai');
        form.action = '/tenagakerja/checklist/' + id + '/selesai';
        var detailModal = bootstrap.Modal.getInstance(document.getElementById('eventDetailModal'));
        if (detailModal) detailModal.hide();
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