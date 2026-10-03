<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Onderdil</title>
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
        .nav-menu .nav-link:hover { color: #667eea; }
        .nav-menu .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 0; height: 2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transition: width 0.3s;
        }
        .nav-menu .nav-link:hover::after { width: 100%; }
        .nav-menu .nav-link i { margin-right: 5px; font-size: 1.1rem; }

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
        }

        .hero-section {
            min-height: 60vh;
            display: flex;
            align-items: center;
            padding: 2rem 0 3rem 0;
            position: relative;
            z-index: 2;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            color: #2d3748;
        }
        .hero-title span {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-description {
            font-size: 1.15rem;
            color: #4a5568;
            margin: 1.8rem 0 2.5rem 0;
            max-width: 520px;
        }

        .floating-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border-radius: 38px;
            padding: 2.3rem 2rem;
            box-shadow: 0 40px 70px -20px rgba(102, 126, 234, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.8);
            max-width: 400px;
            margin-left: auto;
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .floating-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 45px 80px -20px rgba(102, 126, 234, 0.35);
        }
        .card-logo {
            max-width: 180px;
            max-height: 180px;
            object-fit: contain;
        }

        .stats-section {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(4px);
            padding: 3rem 0;
            position: relative;
            z-index: 2;
            border-top: 1px solid rgba(102, 126, 234, 0.15);
            border-bottom: 1px solid rgba(102, 126, 234, 0.15);
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 24px;
            padding: 1.5rem 1.3rem;
            transition: all 0.25s;
            border: 1px solid rgba(102, 126, 234, 0.1);
            box-shadow: 0 10px 22px -12px rgba(102, 126, 234, 0.15);
            height: 100%;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -14px rgba(102, 126, 234, 0.25);
        }
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
        }
        .stat-icon.total { background: linear-gradient(145deg, #e6eaff, #d6dcff); color: #667eea; }
        .stat-icon.pending { background: linear-gradient(145deg, #fff8dc, #fefcbf); color: #b7791f; }
        .stat-icon.selesai { background: linear-gradient(145deg, #e6ffed, #c6f6d5); color: #2f855a; }
        .stat-icon.ditolak { background: linear-gradient(145deg, #fff5f5, #fed7d7); color: #c53030; }

        .stat-info h3 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
            color: #2d3748;
            line-height: 1;
        }
        .stat-info p {
            margin: 0.2rem 0 0 0;
            color: #718096;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 24px;
            padding: 1.5rem;
            border: 1px solid rgba(102, 126, 234, 0.1);
            box-shadow: 0 10px 22px -12px rgba(102, 126, 234, 0.15);
            height: 100%;
        }
        .chart-card h5 {
            font-weight: 700;
            color: #2d3748;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        .chart-card h5 i {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-right: 6px;
        }
        .chart-wrapper {
            position: relative;
            height: 200px;
        }

        .table-section {
            padding: 3rem 0;
            position: relative;
            z-index: 2;
        }
        .table-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px -12px rgba(102, 126, 234, 0.2);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }
        .table-card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #eef2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .table-card-header h4 {
            font-weight: 700;
            color: #2d3748;
            margin: 0;
            font-size: 1.2rem;
        }
        .table-card-header h4 i {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-right: 8px;
        }
        .table-card-header a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: 0.2s;
        }
        .table-card-header a:hover {
            color: #764ba2;
            transform: translateX(3px);
        }

        .table-modern {
            margin: 0;
            width: 100%;
        }
        .table-modern thead th {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #4a5568;
            border-bottom: 2px solid #eef2f7;
        }
        .table-modern tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f4f8;
            font-size: 0.9rem;
            color: #2d3748;
        }
        .table-modern tbody tr:hover {
            background: #f8fafc;
        }
        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.35rem 0.9rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.78rem;
            white-space: nowrap;
        }
        .status-badge.pending { background: #fefcbf; color: #744210; }
        .status-badge.selesai { background: #c6f6d5; color: #22543d; }
        .status-badge.ditolak { background: #fed7d7; color: #822727; }

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
            .hero-title { font-size: 2.5rem; }
            .floating-card { margin: 2rem auto 0 auto; }
            .nav-menu { display: none; }
            .social-icons { justify-content: center; margin-top: 1rem; }
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

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <h1 class="hero-title">
                    Selamat datang, <span>{{ Auth::user()->name ?? 'Tenaga Kerja' }}</span>
                </h1>
                <p class="hero-description">
                    Berikut rekap tugas pemeliharaan Anda. Pantau status, lihat detail, dan kelola checklist dari sini.
                </p>
                <a href="{{ route('tenagakerja.checklist.index') }}" class="btn btn-login-modern btn-lg">
                    <i class="bi bi-check2-square"></i> Buka Checklist
                </a>
            </div>

            <div class="col-lg-5">
                <div class="floating-card">
                    <img src="assets/img/logopt.png" alt="Logo" class="card-logo">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-8">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="stat-card">
                            <div class="stat-icon total"><i class="bi bi-clipboard-data"></i></div>
                            <div class="stat-info">
                                <h3>{{ $total ?? 0 }}</h3>
                                <p>Total Tugas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card">
                            <div class="stat-icon pending"><i class="bi bi-clock-history"></i></div>
                            <div class="stat-info">
                                <h3>{{ $pending ?? 0 }}</h3>
                                <p>Belum Selesai</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card">
                            <div class="stat-icon selesai"><i class="bi bi-check-circle"></i></div>
                            <div class="stat-info">
                                <h3>{{ $selesai ?? 0 }}</h3>
                                <p>Selesai</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card">
                            <div class="stat-icon ditolak"><i class="bi bi-x-circle"></i></div>
                            <div class="stat-info">
                                <h3>{{ $ditolak ?? 0 }}</h3>
                                <p>Ditolak</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="chart-card">
                    <h5><i class="bi bi-pie-chart-fill"></i> Ringkasan Status</h5>
                    <div class="chart-wrapper">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="table-section">
    <div class="container">
        <div class="table-card">
            <div class="table-card-header">
                <h4><i class="bi bi-list-check"></i> Daftar Tugas Pemeliharaan</h4>
                <a href="{{ route('tenagakerja.checklist.index') }}">Lihat Kalender <i class="bi bi-arrow-right"></i></a>
            </div>

            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Pemeliharaan</th>
                            <th>Nama Peralatan</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal ?? [] as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $item->judul_pemeliharaan }}</strong></td>
                            <td>{{ $item->nama_peralatan }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</td>
                            <td>
                                @if($item->status == 'selesai')
                                    <span class="status-badge selesai"><i class="bi bi-check-circle-fill"></i> Selesai</span>
                                @elseif($item->status == 'ditolak')
                                    <span class="status-badge ditolak"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                                @else
                                    <span class="status-badge pending"><i class="bi bi-clock-fill"></i> Belum Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p class="mb-0">Belum ada tugas pemeliharaan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('statusChart').getContext('2d');

        var pending = {{ $pending ?? 0 }};
        var selesai = {{ $selesai ?? 0 }};
        var ditolak = {{ $ditolak ?? 0 }};

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Belum Selesai', 'Selesai', 'Ditolak'],
                datasets: [{
                    data: [pending, selesai, ditolak],
                    backgroundColor: [
                        '#f6e05e',
                        '#48bb78',
                        '#f56565'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Poppins',
                                size: 11,
                                weight: '500'
                            },
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#2d3748',
                        titleFont: { family: 'Poppins', size: 12 },
                        bodyFont: { family: 'Poppins', size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                var total = pending + selesai + ditolak;
                                var val = context.parsed;
                                var pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                return ' ' + context.label + ': ' + val + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
</body>
</html>