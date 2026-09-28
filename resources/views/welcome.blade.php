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
        
        /* background dengan efek gradient ungu soft */
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
        }
        .navbar-brand i {
            font-size: 2rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 4px 6px rgba(102, 126, 234, 0.3));
        }
        
        /* Menu navigasi */
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
        }
        .nav-menu .nav-link:hover {
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
        .nav-menu .nav-link:hover::after {
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
        }
        .btn-login-modern:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6845a0 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
        }

        /* hero section */
        .hero-section {
            min-height: 80vh;
            display: flex;
            align-items: center;
            padding: 2rem 0 5rem 0;
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            color: #2d3748;
            text-shadow: 0 2px 10px rgba(102, 126, 234, 0.1);
        }
        .hero-title span {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 4px 8px rgba(102, 126, 234, 0.3));
        }
        .hero-description {
            font-size: 1.15rem;
            color: #4a5568;
            margin: 1.8rem 0 2.5rem 0;
            max-width: 520px;
        }

        /* floating card dengan efek ungu */
        .floating-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 38px;
            padding: 2.3rem 2rem;
            box-shadow: 0 40px 70px -20px rgba(102, 126, 234, 0.25),
                       0 0 0 1px rgba(102, 126, 234, 0.1) inset;
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
            width: auto;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 8px 16px rgba(102, 126, 234, 0.15));
        }

        /* feature section dengan warna ungu */
        .feature-section {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(4px);
            position: relative;
            z-index: 2;
            border-top: 1px solid rgba(102, 126, 234, 0.15);
            border-bottom: 1px solid rgba(102, 126, 234, 0.15);
        }
        .feature-item {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border-radius: 28px;
            padding: 1.4rem 1.2rem;
            transition: all 0.25s;
            border: 1px solid rgba(102, 126, 234, 0.1);
            box-shadow: 0 10px 22px -12px rgba(102, 126, 234, 0.15);
            height: 100%;
        }
        .feature-item:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(102, 126, 234, 0.3);
            box-shadow: 0 20px 30px -14px rgba(102, 126, 234, 0.25);
            transform: scale(1.02);
        }
        .feature-icon {
            background: linear-gradient(145deg, #f0f3ff, #e6eaff);
            width: 54px;
            height: 54px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 2rem;
            margin-bottom: 1.2rem;
            box-shadow: 0 10px 18px -8px rgba(102, 126, 234, 0.25);
        }
        .feature-item h5 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: #2d3748;
        }
        .feature-item p {
            font-size: 0.9rem;
            color: #4a5568;
            margin-bottom: 0;
        }

        /* CTA section dengan warna ungu */
        .cta-gradient {
            background: linear-gradient(125deg, rgba(102, 126, 234, 0.03) 0%, rgba(118, 75, 162, 0.03) 100%),
                        radial-gradient(circle at 20% 40%, rgba(102, 126, 234, 0.05) 0%, transparent 40%);
            position: relative;
            z-index: 2;
            border-top: 1px solid rgba(102, 126, 234, 0.15);
        }

        .hero-badge {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 60px;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.2);
        }

        /* footer dengan menu */
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
            .hero-title { font-size: 2.8rem; }
            .floating-card { margin: 2rem auto 0 auto; }
            .nav-menu { display: none; } /* Sembunyikan menu di mobile, bisa ditambahkan toggle nanti */
            .social-icons { justify-content: center; margin-top: 1rem; }
        }
    </style>
</head>
<body>

<!-- NAVBAR dengan menu baru -->
<nav class="navbar navbar-expand-lg navbar-blur fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-tools"></i> 
            <span>Manajemen<b>Onderdil</b></span>
        </a>
        
        <!-- Menu navigasi baru -->
        <div class="nav-menu">
            <a href="#" class="nav-link"><i class="bi bi-calendar-week"></i> Jadwal</a>
            <a href="#" class="nav-link"><i class="bi bi-check2-square"></i> Checklist</a>
            <a href="#" class="nav-link"><i class="bi bi-box-seam"></i> Stok Onderdil</a>
        </div>
        
        <div class="d-flex gap-2 align-items-center">
            <a href="/login" class="btn btn-login-modern">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
        </div>
    </div>
</nav>

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <h1 class="hero-title">
                    Kelola onderdil <span>tanpa ribet</span>, <br>fokus bisnis makin lancar
                </h1>
                <p class="hero-description">
                    Satu platform untuk stok masuk, keluar, dan laporan akurat. 
                    Digunakan lebih dari 340 bengkel & distributor di Indonesia.
                </p>
            </div>

            <div class="col-lg-5">
                <div class="floating-card">
                    <img src="assets/img/logopt.png" alt="Logo StokOnderdil" class="card-logo">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="feature-section py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="hero-badge">FITUR UTAMA</span>
            <h2 class="fw-bold display-6 mb-3">Kendalikan stok onderdil<br>dalam satu pintu.</h2>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-item p-4" style="border-radius: 32px;">
                    <div class="feature-icon"><i class="bi bi-database-add"></i></div>
                    <h4 class="h5 fw-semibold">Data barang terpusat</h4>
                    <p class="text-secondary">Kategori, harga, stok minimal, semua tersaji rapi.</p>
                </div>
            </div>
           <div class="col-md-4">
                <div class="feature-item p-4" style="border-radius: 32px;">
                    <div class="feature-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h4 class="h5 fw-semibold">Manajemen Jadwal Pemeliharaan</h4>
                    <p class="text-secondary">
                        Sistem memungkinkan supervisor untuk membuat,
                        melihat, memperbarui, dan menghapus jadwal
                        pemeliharaan secara terstruktur dan terkontrol.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-item p-4" style="border-radius: 32px;">
                    <div class="feature-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h4 class="h5 fw-semibold">Laporan Pemeliharaan</h4>
                    <p class="text-secondary">
                        Sistem menyediakan laporan pemeliharaan lengkap
                        termasuk laporan stok, jadwal pemeliharaan,
                        dan hasil checklist yang dapat dicetak.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-gradient py-5">
    <div class="container text-center py-3">
        <h3 class="fw-bold mb-4">Sudah punya akun? Langsung <span style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">login</span></h3>
        <p class="text-secondary col-lg-6 mx-auto mb-4">Tim bengkel dan manajer gudang dapat mengakses semua fitur setelah masuk.</p>
        <a href="/login" class="btn btn-login-modern btn-lg">
            <i class="bi bi-arrow-right-circle"></i> Login sekarang
        </a>
        <div class="mt-4 small text-secondary">
            <i class="bi bi-info-circle" style="color: #667eea;"></i> Registrasi hanya untuk admin internal — hubungi IT
        </div>
    </div>
</section>

<!-- FOOTER BARU dengan menu Jadwal, Checklist, Stok Onderdil -->
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
                    <li><a href="#"><i class="bi bi-check2-square"></i> Checklist</a></li>
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
</body>
</html>