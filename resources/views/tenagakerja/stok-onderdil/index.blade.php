<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stok Onderdil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(102, 126, 234, 0.05) 0%, transparent 30%),
                        radial-gradient(circle at 90% 70%, rgba(118, 75, 162, 0.06) 0%, transparent 35%),
                        radial-gradient(circle at 30% 80%, rgba(102, 126, 234, 0.04) 0%, transparent 40%);
            pointer-events: none;
            z-index: 0;
        }

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
            background-clip: text;
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
        .nav-menu .nav-link:hover,
        .nav-menu .nav-link.active { color: #667eea; }
        .nav-menu .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 0; height: 2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transition: width 0.3s;
        }
        .nav-menu .nav-link:hover::after,
        .nav-menu .nav-link.active::after { width: 100%; }
        .nav-menu .nav-link i { margin-right: 5px; font-size: 1.1rem; }

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

        .table-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px -20px rgba(102, 126, 234, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .table-modern {
            margin: 0;
            width: 100%;
        }
        .table-modern thead th {
            background: #f8fafc;
            padding: 1rem 1.3rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4a5568;
            border-bottom: 2px solid #eef2f7;
            white-space: nowrap;
        }
        .table-modern tbody td {
            padding: 1rem 1.3rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f4f8;
            font-size: 0.9rem;
            color: #2d3748;
        }
        .table-modern tbody tr:hover { background: #f8fafc; }
        .table-modern tbody tr:last-child td { border-bottom: none; }

        .img-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eef2f7;
        }

        .stok-badge {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .stok-aman { background: #c6f6d5; color: #22543d; }
        .stok-rendah { background: #fefcbf; color: #744210; }
        .stok-habis { background: #fed7d7; color: #822727; }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #a0aec0;
        }
        .empty-state i {
            font-size: 3rem;
            color: #cbd5e0;
            display: block;
            margin-bottom: 1rem;
        }

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
        .social-icons { display: flex; gap: 1rem; justify-content: flex-end; }
        .social-icons a {
            width: 36px; height: 36px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
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
    <a href="#" class="nav-link"><i class="bi bi-calendar-week"></i> Jadwal</a>
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
                <h2><i class="bi bi-box-seam"></i> Stok Onderdil</h2>
                <p>Daftar stok onderdil yang tersedia (hanya lihat)</p>
            </div>
            <div>
                <span class="user-badge">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </span>
            </div>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Sparepart</th>
                            <th>Stok Sebelum</th>
                            <th>Periode</th>
                            <th>Kondisi Sekarang</th>
                            <th>Pemakaian</th>
                            <th>Stok Terbaru</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokOnderdil as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($item->gambar)
                                    <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama_sparepart }}" class="img-thumb">
                                @else
                                    <div class="img-thumb d-flex align-items-center justify-content-center bg-light">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $item->nama_sparepart }}</strong></td>
                            <td>{{ $item->stok_periode_sebelum }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->periode)->format('d/m/Y') }}</td>
                            <td>{{ $item->kondisi_sekarang }}</td>
                            <td>{{ $item->pemakaian }}</td>
                            <td>
                                @if($item->stok_terbaru <= 0)
                                    <span class="stok-badge stok-habis">Habis</span>
                                @elseif($item->stok_terbaru <= 5)
                                    <span class="stok-badge stok-rendah">{{ $item->stok_terbaru }}</span>
                                @else
                                    <span class="stok-badge stok-aman">{{ $item->stok_terbaru }}</span>
                                @endif
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p class="mb-0">Belum ada data stok onderdil</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
                    <li><a href="{{ route('tenagakerja.dashboard') }}"><i class="bi bi-house"></i> Dashboard</a></li>
                    <li><a href="#"><i class="bi bi-calendar-week"></i> Jadwal</a></li>
                    <li><a href="{{ route('tenagakerja.checklist.index') }}"><i class="bi bi-check2-square"></i> Checklist</a></li>
                    <li><a href="{{ route('tenagakerja.stok-onderdil.index') }}"><i class="bi bi-box-seam"></i> Stok Onderdil</a></li>
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
</body>
</html>