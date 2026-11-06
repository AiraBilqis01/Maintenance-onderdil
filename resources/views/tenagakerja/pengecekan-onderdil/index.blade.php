<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jadwal Pengecekan Sparepart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            background:
                radial-gradient(circle at 10% 20%, rgba(102,126,234,0.05) 0%, transparent 30%),
                radial-gradient(circle at 90% 70%, rgba(118,75,162,0.06) 0%, transparent 35%);
            pointer-events: none;
            z-index: 0;
        }

        /* =========================
           NAVBAR
           ========================= */
        .navbar-blur {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 30px rgba(102,126,234,0.1);
            padding: 16px 0;
            border-bottom: 1px solid rgba(102,126,234,0.2);
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
            background: linear-gradient(135deg,#667eea,#764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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
            background: linear-gradient(135deg,#667eea,#764ba2);
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

        /* =========================
           LOGIN / LOGOUT
           ========================= */
        .btn-login-modern {
            background: linear-gradient(135deg,#667eea,#764ba2);
            color: white;
            border-radius: 60px;
            padding: 12px 36px;
            font-weight: 500;
            border: none;
            transition: 0.25s ease;
            box-shadow: 0 6px 16px rgba(102,126,234,0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-login-modern:hover {
            background: linear-gradient(135deg,#5a6fd8,#6845a0);
            color: white;
            transform: translateY(-2px);
        }

        .btn-logout-modern {
            background: linear-gradient(135deg,#fc5c7d,#6a82fb);
            color: white;
            border-radius: 60px;
            padding: 12px 36px;
            font-weight: 500;
            border: none;
            transition: 0.25s ease;
            box-shadow: 0 6px 16px rgba(252,92,125,0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-logout-modern:hover {
            background: linear-gradient(135deg,#e04a6a,#5872e8);
            color: white;
            transform: translateY(-2px);
        }

        /* =========================
           CONTENT
           ========================= */
        .page-content {
            padding: 4rem 0;
            position: relative;
            z-index: 2;
        }

        .page-title-card {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px -20px rgba(102,126,234,0.25);
            border: 1px solid rgba(255,255,255,0.8);
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
            background: linear-gradient(135deg,#667eea,#764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-right: 10px;
        }

        .page-title-card p {
            color: #718096;
            margin: 0.3rem 0 0;
            font-size: 0.95rem;
        }

        .user-badge {
            background: linear-gradient(135deg,#667eea,#764ba2);
            color: white;
            padding: 0.6rem 1.4rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 16px rgba(102,126,234,0.3);
        }

        /* =========================
           CALENDAR
           ========================= */
        .calendar-wrapper {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 40px -20px rgba(102,126,234,0.25);
            border: 1px solid rgba(255,255,255,0.8);
            position: relative;
        }

        #calendar {
            font-family: 'Poppins', sans-serif;
        }

        .custom-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .custom-toolbar-left,
        .custom-toolbar-right {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

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

        .btn-custom-view:hover {
            background: #f8f9fa;
            color: #2d3748;
        }

        .btn-custom-view.active {
            background: linear-gradient(135deg,#667eea,#764ba2);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 10px rgba(102,126,234,0.3);
        }

        /* =========================
           FULLCALENDAR
           ========================= */

        .fc-toolbar.fc-header-toolbar {
            display: none !important;
        }

        .fc .fc-col-header-cell-cushion {
            color: #000 !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            padding: 10px 0 !important;
        }

        .fc .fc-daygrid-day-number,
        .fc .fc-daygrid-day-top {
            color: #2d3748 !important;
            font-weight: 500 !important;
            text-decoration: none !important;
        }

        .fc-event,
        .fc-event-dot {
            border: none !important;
            border-radius: 8px !important;
            padding: 3px 6px !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            cursor: pointer !important;
            box-shadow: 0 2px 6px rgba(102,126,234,0.3) !important;
        }

        .fc-event.status-selesai {
            background: linear-gradient(135deg,#38b2ac,#48bb78) !important;
        }

        .fc-event.status-ditolak {
            background: linear-gradient(135deg,#fc5c7d,#e53e3e) !important;
        }

        .fc-event.status-menunggu_verifikasi {
            background: linear-gradient(135deg,#3b82f6,#2563eb) !important;
        }

        .fc-day-today {
            background: rgba(102,126,234,0.08) !important;
        }

        .fc-event-title,
        .fc-event-time,
        .fc-event-title-container {
            color: #000 !important;
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

        /* =========================
           MODAL
           ========================= */
        .modal-content-modern {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 40px 80px -20px rgba(102,126,234,0.4);
        }

        .modal-header-modern {
            background: linear-gradient(135deg,#667eea,#764ba2);
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

        .modal-footer-modern {
            background: #fafbff;
            border-top: 1px solid #eef2f7;
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        /* =========================
           DETAIL SPAREPART
           ========================= */
        .sparepart-section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a2e;
            text-transform: uppercase;
            margin: 1.5rem 0 0.8rem;
        }

        .sparepart-table th,
        .sparepart-table td {
            text-align: center;
            vertical-align: middle;
        }

        .sparepart-table th {
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .sparepart-table td {
            font-size: 0.85rem;
        }

        .sparepart-table-wrapper {
            max-height: 200px;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        .sparepart-table-wrapper .sparepart-table {
            margin-bottom: 0;
        }

        .sparepart-table-wrapper .sparepart-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f8f9fa;
        }

        /* =========================
        DETAIL HASIL PENGECEKAN
        ========================= */
        .detail-section {
            margin-top: 1.5rem;
        }

        .detail-section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a2e;
            text-transform: uppercase;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hasil-pengecekan-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
        }

        .hasil-pengecekan-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 1px solid #edf2f7;
            color: #1a202c;
        }

        .hasil-nomor {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: #edf2ff;
            color: #5a67d8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .hasil-pengecekan-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .hasil-item,
        .hasil-item-full {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .hasil-label {
            font-size: 0.75rem;
            color: #718096;
            font-weight: 600;
        }

        .hasil-value {
            font-size: 0.85rem;
            color: #2d3748;
            font-weight: 500;
            word-break: break-word;
        }

        .hasil-detail-box {
            background: #fffaf0;
            border: 1px solid #feebc8;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
            display: grid;
            gap: 8px;
        }

        .gambar-bukti-pengecekan {
            width: 100%;
            max-width: 180px;
            max-height: 140px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            cursor: pointer;
        }

        .gambar-bukti-pengecekan:hover {
            opacity: 0.9;
        }

        @media (max-width: 576px) {
            .hasil-pengecekan-grid {
                grid-template-columns: 1fr;
            }
        }

        /* =========================
           STATUS
           ========================= */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1.1rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .status-badge.pending {
            background: #fefcbf;
            color: #744210;
        }

        .status-badge.menunggu_verifikasi {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.selesai {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-badge.ditolak {
            background: #fed7d7;
            color: #822727;
        }

        /* =========================
           INFORMASI PENOLAKAN
           ========================= */
        .result-section {
            margin-top: 1.5rem;
            padding: 1.2rem;
            border-radius: 14px;
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

        .result-section.ditolak h6 {
            color: #822727;
        }

        /* =========================
           TOMBOL HASIL / TOLAK
           ========================= */
        .btn-hasil-action {
            background: linear-gradient(135deg,#38b2ac,#48bb78);
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
            box-shadow: 0 4px 12px rgba(72,187,120,0.3);
        }

        .btn-hasil-action:hover {
            background: linear-gradient(135deg,#2f9e99,#38a169);
            color: white;
            transform: translateY(-1px);
        }

        .btn-tolak-action {
            background: linear-gradient(135deg,#fc5c7d,#e53e3e);
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
            box-shadow: 0 4px 12px rgba(229,62,62,0.3);
        }

        .btn-tolak-action:hover {
            background: linear-gradient(135deg,#e04a6a,#c53030);
            color: white;
            transform: translateY(-1px);
        }

        /* =========================
           FORM HASIL PENGECEKAN
           ========================= */
        .modal-hasil-pengecekan .modal-dialog {
            max-height: calc(100vh - 30px);
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .modal-hasil-pengecekan .modal-content {
            max-height: calc(100vh - 30px);
        }

        .modal-hasil-pengecekan form {
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 30px);
            min-height: 0;
        }

        .modal-hasil-pengecekan .modal-header,
        .modal-hasil-pengecekan .modal-footer {
            flex-shrink: 0;
        }

        .modal-hasil-pengecekan .modal-header {
            padding: 1rem 1.4rem;
        }

        .modal-hasil-pengecekan .modal-header h5 {
            font-size: 1rem;
        }

        .modal-hasil-pengecekan .modal-body {
            overflow-y: auto;
            overflow-x: hidden;
            min-height: 0;
            padding: 1.2rem 1.4rem;
            scrollbar-gutter: stable;
        }

        .modal-hasil-pengecekan .modal-footer {
            padding: 0.75rem 1.4rem;
            background: #fff;
            border-top: 1px solid #edf2f7;
        }

        .modal-hasil-pengecekan .form-label {
            font-size: 0.8rem;
            margin-bottom: 0.3rem;
        }

        .modal-hasil-pengecekan .form-control,
        .modal-hasil-pengecekan .form-select,
        .modal-hasil-pengecekan select {
            font-size: 0.82rem;
            padding: 0.45rem 0.7rem;
        }

        .modal-hasil-pengecekan textarea.form-control {
            min-height: 65px;
            resize: vertical;
        }

        .modal-hasil-pengecekan small {
            font-size: 0.7rem;
        }

        .modal-hasil-pengecekan .alert {
            font-size: 0.78rem;
            padding: 0.7rem 0.9rem;
            margin-bottom: 1rem;
        }

        .modal-hasil-pengecekan .mb-3 {
            margin-bottom: 0.75rem !important;
        }

        .modal-hasil-pengecekan .mb-4 {
            margin-bottom: 1rem !important;
        }

        .modal-hasil-pengecekan .btn {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }

        .hasil-sparepart-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem 1.1rem;
            margin-bottom: 1rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.04);
        }

        .hasil-sparepart-title {
            font-weight: 700;
            color: #2d3748;
            font-size: 0.9rem;
            margin-bottom: 0.8rem;
            padding-bottom: 0.6rem;
            border-bottom: 1px solid #edf2f7;
        }

        .stok-sistem-box,
        .selisih-box {
            background: #edf2f7;
            border-radius: 9px;
            padding: 0.5rem 0.7rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #2d3748;
            min-height: 37px;
            display: flex;
            align-items: center;
        }

        .selisih-box {
            font-weight: 700;
        }

        .selisih-box.normal {
            background: #f0fff4;
            color: #22543d;
        }

        .selisih-box.bermasalah {
            background: #fff5f5;
            color: #c53030;
        }

        .form-keterangan-selisih {
            background: #fffaf0;
            border: 1px solid #fbd38d;
            border-radius: 10px;
            padding: 0.75rem;
            margin-top: 0.5rem;
            margin-bottom: 0.8rem;
        }

        .file-error {
            color: #e53e3e;
            font-size: 0.72rem;
            margin-top: 5px;
        }

        /* =========================
           FOOTER
           ========================= */
        .footer-modern {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(12px);
            padding: 3rem 0 1.5rem;
            border-top: 1px solid rgba(102,126,234,0.2);
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
        }

        .footer-bottom {
            border-top: 1px solid rgba(102,126,234,0.2);
            padding-top: 1.5rem;
            margin-top: 2rem;
            color: #718096;
        }

        /* =========================
           MOBILE MENU
           ========================= */
        .btn-menu-mobile {
            width: 46px;
            height: 46px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg,#667eea,#764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(102,126,234,0.25);
        }

        .btn-menu-mobile i {
            font-size: 1.7rem;
        }

        .mobile-dropdown-menu {
            width: 100%;
            margin-top: 15px;
            padding: 12px 0 8px;
            border-top: 1px solid rgba(102,126,234,0.15);
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
            border-radius: 10px;
        }

        .mobile-menu-link i {
            width: 24px;
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

        .mobile-auth-button {
            padding: 12px 10px 6px;
        }

        /* =========================
           RESPONSIVE
           ========================= */
        @media (max-width: 992px) {
            .page-title-card {
                padding: 1.5rem;
            }

            .page-title-card h2 {
                font-size: 1.3rem;
            }

            .calendar-wrapper {
                padding: 1rem;
            }

            .custom-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .custom-toolbar-left,
            .custom-toolbar-right {
                justify-content: center;
            }

            .modal-hasil-pengecekan .modal-dialog {
                max-width: calc(100% - 30px);
            }
        }

        @media (max-width: 576px) {
            .detail-row {
                flex-direction: column;
                gap: 4px;
            }

            .detail-label {
                min-width: 0;
                width: 100%;
            }

            .page-content {
                padding-top: 2.5rem;
            }

            .modal-hasil-pengecekan .modal-dialog {
                margin: 8px;
                max-width: calc(100% - 16px);
                max-height: calc(100vh - 16px);
            }

            .modal-hasil-pengecekan .modal-content,
            .modal-hasil-pengecekan form {
                max-height: calc(100vh - 16px);
            }

            .modal-hasil-pengecekan .modal-header {
                padding: 0.85rem 1rem;
            }

            .modal-hasil-pengecekan .modal-header h5 {
                font-size: 0.88rem;
            }

            .modal-hasil-pengecekan .modal-body {
                padding: 0.9rem;
            }

            .modal-hasil-pengecekan .modal-footer {
                padding: 0.7rem 0.9rem;
            }

            .hasil-sparepart-card {
                padding: 0.8rem;
            }

            .hasil-sparepart-title {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-blur sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('tenagakerja.dashboard') }}">
            <i class="bi bi-tools"></i>
            <span>Manajemen<b>Pemeliharaan</b></span>
        </a>

        <div class="nav-menu d-none d-lg-flex">
            <a href="{{ route('tenagakerja.dashboard') }}" class="nav-link"><i class="bi bi-house"></i> Dashboard</a>
            @if(in_array(Auth::user()->jenis_tugas, ['pemeliharaan_peralatan', 'keduanya']))
                <a href="{{ route('tenagakerja.checklist.index') }}" class="nav-link"><i class="bi bi-check2-square"></i> Jadwal Pemeliharaan</a>
            @endif
            @if(in_array(Auth::user()->jenis_tugas, ['pengecekan_onderdil', 'keduanya']))
                <a href="{{ route('tenagakerja.pengecekan-onderdil.index') }}" class="nav-link active"><i class="bi bi-clipboard-check"></i> Pengecekan Sparepart</a>
            @endif
            <a href="{{ route('tenagakerja.stok-onderdil.index') }}" class="nav-link"><i class="bi bi-box-seam"></i> Stok Sparepart</a>
        </div>

        <div class="d-none d-lg-flex">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-logout-modern"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            @endauth
        </div>

        <button class="btn btn-menu-mobile d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenuTenagaKerja">
            <i class="bi bi-list"></i>
        </button>

        <div class="collapse d-lg-none w-100" id="mobileMenuTenagaKerja">
            <div class="mobile-dropdown-menu">
                <a href="{{ route('tenagakerja.dashboard') }}" class="mobile-menu-link"><i class="bi bi-house"></i> Dashboard</a>
                @if(in_array(Auth::user()->jenis_tugas, ['pemeliharaan_peralatan', 'keduanya']))
                    <a href="{{ route('tenagakerja.checklist.index') }}" class="mobile-menu-link"><i class="bi bi-check2-square"></i> Jadwal Pemeliharaan</a>
                @endif
                @if(in_array(Auth::user()->jenis_tugas, ['pengecekan_onderdil', 'keduanya']))
                    <a href="{{ route('tenagakerja.pengecekan-onderdil.index') }}" class="mobile-menu-link active"><i class="bi bi-clipboard-check"></i> Pengecekan Sparepart</a>
                @endif
                <a href="{{ route('tenagakerja.stok-onderdil.index') }}" class="mobile-menu-link"><i class="bi bi-box-seam"></i> Stok Sparepart</a>
                <div class="mobile-menu-divider"></div>

                <div class="mobile-auth-button">
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-logout-modern"><i class="bi bi-box-arrow-right"></i> Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- CONTENT --}}
<div class="page-content">
    <div class="container">
        @if(session('sukses'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('sukses') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i> Data tidak dapat diproses.</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="page-title-card">
            <div>
                <h2><i class="bi bi-clipboard-check"></i> Jadwal Pengecekan Sparepart</h2>
                <p>Jadwal pengecekan sparepart yang ditugaskan kepada Anda</p>
            </div>
            <span class="user-badge"><i class="bi bi-person-circle"></i> {{ Auth::user()->name }}</span>
        </div>

        <div class="calendar-wrapper">
            <div class="custom-toolbar">
                <div class="custom-toolbar-left">
                    <button class="btn btn-custom-view" id="btnPrev"><i class="bi bi-chevron-left"></i></button>
                    <button class="btn btn-custom-view" id="btnNext"><i class="bi bi-chevron-right"></i></button>
                    <button class="btn btn-custom-view" id="btnToday">Hari Ini</button>
                    <h4 id="calendarTitle" class="ms-3 mb-0 fw-bold" style="font-size:1.2rem; color:#2d3748;"></h4>
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

{{-- MODAL DETAIL --}}
<div class="modal fade" id="eventDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5><i class="bi bi-clipboard-check me-2"></i> Detail Jadwal Pengecekan Sparepart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body modal-body-modern" id="eventDetailBody"></div>
            <div class="modal-footer modal-footer-modern" id="eventDetailFooter"></div>
        </div>
    </div>
</div>

{{-- MODAL HASIL PENGECEKAN --}}
<div class="modal fade modal-hasil-pengecekan" id="modalHasilPengecekan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modal-content-modern">
            <form id="formHasilPengecekan" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-modern" style="background:linear-gradient(135deg,#38b2ac,#48bb78);">
                    <h5><i class="bi bi-clipboard-check me-2"></i> Isi Hasil Pengecekan Sparepart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Judul Pengecekan</label>
                        <input type="text" class="form-control" id="hasil_judul_pengecekan" readonly>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-1"></i>
                        Isi hasil pengecekan untuk setiap sparepart. Selisih dihitung otomatis dari <strong>Stok Fisik - Stok Sistem</strong>.
                    </div>
                    <div id="hasilPengecekanContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-hasil-action" id="btnKirimHasilPengecekan"><i class="bi bi-send-check"></i> Kirim Hasil Pengecekan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TOLAK --}}
<div class="modal fade" id="modalTolakPengecekan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <form id="formTolakPengecekan" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header modal-header-modern" style="background:linear-gradient(135deg,#fc5c7d,#e53e3e);">
                    <h5><i class="bi bi-x-circle me-2"></i> Tolak Pengecekan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body modal-body-modern">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Pengecekan</label>
                        <input type="text" class="form-control" id="tolak_judul_pengecekan" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="tolak_keterangan" name="keterangan_tolak" rows="3" placeholder="Contoh: Pengecekan belum dapat dilakukan karena akses gudang terkunci" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="tolak_alasan" name="alasan_penolakan" rows="3" placeholder="Jelaskan alasan penolakan tugas pengecekan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-tolak-action" id="btnKirimTolakPengecekan"><i class="bi bi-x-circle"></i> Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- FOOTER --}}
<footer class="footer-modern">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-tools" style="font-size:2rem; background:linear-gradient(135deg,#667eea,#764ba2); -webkit-background-clip:text; -webkit-text-fill-color:transparent;"></i>
                    <span class="fw-bold fs-4">Manajemen Pemeliharaan</span>
                </div>
                <p class="text-secondary mb-3">
                    Sistem informasi manajemen yang mengintegrasikan pemeliharaan peralatan dengan sparepart pada PLTMH Sangir Hulu
                </p>
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
                            <i class="bi bi-geo-alt"></i>
                            Jorong Letter W, Nagari Lubuk Gadang Tim., Kec. Sangir, Kabupaten Solok Selatan, Prov. Sumatera Barat
                        </a>
                    </li>
                    <li>
                        <i class="bi bi-envelope"></i>
                        <a href="mailto:adminpemeliharaan.pltmsh@gmail.com">adminpemeliharaan.pltmsh@gmail.com</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-6 text-md-start">
                    <i class="bi bi-c-circle me-1" style="color:#667eea;"></i>
                    2026 Manajemen Pemeliharaan. All rights reserved.
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="me-3"><i class="bi bi-shield-lock" style="color:#667eea;"></i> Secure System</span>
                    <span><i class="bi bi-arrow-repeat" style="color:#667eea;"></i> Real-time Updates</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var events = @json($events);
    var selectedPengecekan = null;
    var basePengecekanUrl = @json(url('/tenagakerja/pengecekan-onderdil'));

    function escapeHtml(value) {
        if (value === null || value === undefined) return '-';
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function statusLabel(status) {
        switch (status) {
            case 'pending': return 'Pending';
            case 'menunggu_verifikasi': return 'Menunggu Verifikasi';
            case 'selesai': return 'Selesai';
            case 'ditolak': return 'Ditolak';
            default: return status;
        }
    }

    function buatInfoKetepatanPengecekan(info) {
        var props = info.event.extendedProps;
        var status = props.status || 'pending';
        // ============================================
        // 1. DATA TANGGAL
        // ============================================
        var targetAktif = info.event.startStr.substring(0, 10);
        var targetAwal = props.tanggal_target_awal || targetAktif;

        var tanggalKirim = props.tanggal_kirim_hasil;
        var tanggalVerifikasi = props.tanggal_verifikasi;

        // ============================================
        // 2. CEK HASIL PENGECEKAN
        // ============================================
        // Mengambil hasil langsung dari detail sparepart.
        var adaHasil = (props.details || []).some(function(detail) {
            return detail.stok_fisik !== null && detail.stok_fisik !== undefined;
        });

        // ============================================
        // 3. FORMAT TANGGAL
        // ============================================
        function formatTanggal(tanggal) {
            if (!tanggal) return '-';

            var bagian = tanggal.substring(0, 10) .split('-') .map(Number);
            var objek = new Date(bagian[0], bagian[1] - 1, bagian[2]);

            return objek.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        function formatTanggalJam(tanggal) {
            if (!tanggal) return 'Belum tercatat';
            var bagian = tanggal.split(' ');
            return formatTanggal(bagian[0]) + (bagian[1] ? ', ' + bagian[1] + ' WIB' : '');
        }

        // Menghitung selisih hari berdasarkan tanggal kalender.
        function selisihHari(target, aktual) {
            if (!target || !aktual) return 0;

            var a = target.substring(0, 10).split('-').map(Number);
            var b = aktual.substring(0, 10).split('-').map(Number);

            var awal = Date.UTC(a[0], a[1] - 1, a[2]);
            var akhir = Date.UTC(b[0], b[1] - 1, b[2]);

            return Math.round((akhir - awal) / 86400000);
        }

        function baris(label, nilai) {
            return '<div class="detail-row">' + '<div class="detail-label">' + label + '</div>' + '<div class="detail-value">' + nilai + '</div>' + '</div>';
        }

        // ============================================
        // 4. KETEPATAN WAKTU DARI HELPER LARAVEL
        // ============================================
        var teksKetepatan = props.ketepatan_label || 'Informasi belum tersedia';
        var warnaKetepatan = 'text-secondary';

        if (props.ketepatan_warna === 'danger') {
            warnaKetepatan = 'text-danger';
        } else if (props.ketepatan_warna === 'success') {
            warnaKetepatan = 'text-success';
        }

        // ============================================
        // 5. PENANGANAN STATUS KHUSUS
        // ============================================
        // Penolakan tugas tidak dihitung sebagai penyelesaian.
        var pengajuanPenolakan = status === 'ditolak' || (status === 'menunggu_verifikasi' && !adaHasil && Boolean(props.alasan_penolakan));

        if (pengajuanPenolakan) {
            teksKetepatan = 'Penolakan Tugas - Tidak Dinilai';
            warnaKetepatan = 'text-secondary';
        } else if (adaHasil && !tanggalKirim && (status === 'selesai' || status === 'menunggu_verifikasi')) {
            // Pertahankan penanganan data hasil lama
            // yang belum memiliki tanggal pengiriman.
            teksKetepatan = 'Tanggal Pengiriman Belum Tercatat';
            warnaKetepatan = 'text-secondary';
        } else if (!adaHasil && status === 'menunggu_verifikasi') {
            teksKetepatan = 'Belum Ada Hasil Pengecekan';
            warnaKetepatan = 'text-secondary';
        }

        // ============================================
        // 6. RIWAYAT REVISI
        // ============================================
        var jumlahRevisi = Number(props.jumlah_revisi || 0);
        var pernahTerlambat = props.pernah_terlambat_sebelum_revisi === true;
        var hariTerlambatTercatat = Number(props.hari_terlambat_tercatat || 0);
        var daftarRiwayat = Array.isArray(props.riwayat_revisi_detail) ? props.riwayat_revisi_detail : [];

        // ============================================
        // 7. TAMPILAN INFORMASI KETEPATAN
        // ============================================
        var html = '';
        html += '<div class="result-section" style="' + 'background:#f3f6ff;' + 'border-left:4px solid #667eea;' + 'padding:16px;' + 'border-radius:10px;' + 'margin-top:16px;">';
        html += '<h6 style="color:#4a5568;font-weight:600;">' + '<i class="bi bi-clock-history me-2"></i>' + 'Ketepatan Waktu Pengecekan</h6>';
        html += baris('Target Awal', formatTanggal(targetAwal));
        html += baris('Batas Waktu Aktif', formatTanggal(targetAktif));
        html += baris('Hasil Dikirim', formatTanggalJam(tanggalKirim));
        html += baris('ACC Supervisor', formatTanggalJam(tanggalVerifikasi));

        // Ketepatan utama berdasarkan target aktif.
        html += baris('Ketepatan Waktu', '<strong class="' + warnaKetepatan + '">' + escapeHtml(teksKetepatan) + '</strong>');

        // ============================================
        // 8. INFORMASI TAMBAHAN HASIL PEKERJAAN
        // ============================================
        if (adaHasil && tanggalKirim && ['selesai', 'menunggu_verifikasi'].includes(status)) {
            var selisihAktif = selisihHari(targetAktif, tanggalKirim);

            // Pertahankan informasi lebih awal X hari.
            if (selisihAktif < 0) {
                html += baris('Informasi Pengiriman', '<span class="text-success">' + 'Dikirim ' + Math.abs(selisihAktif) + ' hari lebih awal dari target aktif' + '</span>');
            }

            // Bandingkan hasil dengan target awal jika Supervisor pernah merevisi tanggal.
            if (jumlahRevisi > 0 || targetAwal !== targetAktif) {
                var selisihAwal = selisihHari(targetAwal, tanggalKirim);
                var teksTargetAwal = '';

                if (selisihAwal > 0) {
                    teksTargetAwal = 'Terlambat ' + selisihAwal + ' Hari';
                } else if (selisihAwal < 0) {
                    teksTargetAwal = 'Lebih Awal ' + Math.abs(selisihAwal) + ' Hari';
                } else {
                    teksTargetAwal = 'Tepat Waktu';
                }

                html += baris('Terhadap Target Awal', escapeHtml(teksTargetAwal));
            }
        }

        // ============================================
        // 9. RIWAYAT KETERLAMBATAN SEBELUM REVISI
        // ============================================
        if (pernahTerlambat) {
            html += baris('Riwayat Terlambat', '<span class="text-danger">' + '<i class="bi bi-exclamation-triangle me-1"></i>' + hariTerlambatTercatat + ' hari' + ' (keterlambatan terbesar sebelum revisi).' + '</span>');
        }

        // ============================================
        // 10. JUMLAH REVISI
        // ============================================
        if (jumlahRevisi > 0) {
            html += baris('Jumlah Revisi', jumlahRevisi + ' kali');
        }
        
        // ============================================
        // RINCIAN RIWAYAT REVISI PER JADWAL
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

                html += '<div class="border rounded p-3 mb-2 bg-white">';
                html += '<div class="fw-bold mb-2">' + 'Revisi ke-' + (index + 1) + '</div>';
                html += baris('Target Sebelumnya', formatTanggal(revisi.tanggal_lama));
                html += baris('Target Baru', formatTanggal(revisi.tanggal_baru));
                html += baris('Tanggal Revisi', formatTanggalJam(revisi.direvisi_pada));
                html += baris('Keterlambatan', informasiKeterlambatan);
                html += baris('Alasan Revisi', escapeHtml(revisi.alasan_revisi || '-'));
                html += '</div>';
            });
            html += '</div>';
        }

        html += '</div>';
        return html;
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        headerToolbar: false,
        events: events,
        views: { dayGridMonth: { dayMaxEvents: 3 }, dayGridWeek: { dayMaxEvents: 4 }},

        eventClick: function(info) {
            var event = info.event;
            var props = event.extendedProps;
            var status = props.status || 'pending';
            var details = props.details || [];
            var sparepartRows = '';

            selectedPengecekan = { id: event.id, title: event.title, status: status, details: props.details || [] };

            if (details.length > 0) {
                details.forEach(function(detail, index) {
                    var stokSaatIni = detail.stok_saat_ini !== null && detail.stok_saat_ini !== undefined ? detail.stok_saat_ini : '-';
                    sparepartRows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td><strong>${escapeHtml(detail.nama_sparepart)}</strong></td>
                            <td><strong>${escapeHtml(stokSaatIni)}</strong><br><small class="text-muted">Stok saat ini</small></td>
                        </tr>
                    `;
                });
            } else {
                sparepartRows = `<tr><td colspan="3" class="text-muted">Tidak ada sparepart pada jadwal ini.</td></tr>`;
            }

            var informasiPenolakan = '';

            if (props.keterangan_tolak || props.alasan_penolakan) {
                informasiPenolakan = `
                    <div class="result-section ditolak">
                        <h6><i class="bi bi-exclamation-triangle me-2"></i>${status === 'pending' ? 'Catatan Perbaikan dari Supervisor' : 'Informasi Penolakan'}</h6>
                        <div class="detail-row"><div class="detail-label">Keterangan</div><div class="detail-value">${escapeHtml(props.keterangan_tolak)}</div></div>
                        <div class="detail-row"><div class="detail-label">Alasan Penolakan</div><div class="detail-value">${escapeHtml(props.alasan_penolakan)}</div></div>
                    </div>
                `;
            }

            var detailHasilPengecekan = '';
            var adaHasilPengecekan = details.some(function(detail) {
                return detail.stok_fisik !== null && detail.stok_fisik !== undefined;
            });

            if (adaHasilPengecekan) {
                detailHasilPengecekan += `
                    <div class="detail-section mt-4">
                        <div class="detail-section-title">
                            <i class="bi bi-clipboard-check"></i>
                            Detail Hasil Pengecekan
                        </div>
                `;

                details.forEach(function(detail, index) {
                    if (detail.stok_fisik === null || detail.stok_fisik === undefined) {
                        return;
                    }

                    var selisih = parseInt(detail.selisih ?? 0);
                    var teksSelisih = selisih > 0 ? '+' + selisih : selisih;

                    detailHasilPengecekan += `
                        <div class="hasil-pengecekan-card">
                            <div class="hasil-pengecekan-header">
                                <span class="hasil-nomor">${index + 1}</span>
                                <strong>${escapeHtml(detail.nama_sparepart || '-')}</strong>
                            </div>

                            <div class="hasil-pengecekan-grid">
                                <div class="hasil-item">
                                    <span class="hasil-label">Stok Sistem Saat Pengecekan</span>
                                    <span class="hasil-value">${detail.stok_sistem_saat_cek ?? '-'}</span>
                                </div>

                                <div class="hasil-item">
                                    <span class="hasil-label">Stok Fisik</span>
                                    <span class="hasil-value">${detail.stok_fisik ?? '-'}</span>
                                </div>

                                <div class="hasil-item">
                                    <span class="hasil-label">Selisih</span>
                                    <span class="hasil-value">${teksSelisih}</span>
                                </div>

                                <div class="hasil-item">
                                    <span class="hasil-label">Kondisi</span>
                                    <span class="hasil-value">${escapeHtml(detail.kondisi || '-')}</span>
                                </div>
                            </div>
                    `;

                    if (selisih !== 0) {
                        detailHasilPengecekan += `
                            <div class="hasil-detail-box">
                                <div class="hasil-item-full">
                                    <span class="hasil-label">Penyebab Selisih</span>
                                    <span class="hasil-value">${escapeHtml(detail.penyebab_selisih || '-')}</span>
                                </div>

                                <div class="hasil-item-full">
                                    <span class="hasil-label">Keterangan Selisih</span>
                                    <span class="hasil-value">${escapeHtml(detail.keterangan_selisih || '-')}</span>
                                </div>
                            </div>
                        `;
                    }

                    detailHasilPengecekan += `
                            <div class="hasil-item-full mt-2">
                                <span class="hasil-label">Catatan Pengecekan</span>
                                <span class="hasil-value">${escapeHtml(detail.catatan_pengecekan || '-')}</span>
                            </div>
                    `;

                    if (detail.gambar_bukti) {
                        detailHasilPengecekan += `
                            <div class="hasil-item-full mt-3">
                                <span class="hasil-label">Bukti Pengecekan</span>
                                <div class="mt-2">
                                    <img src="/${detail.gambar_bukti}" alt="Bukti Pengecekan" class="gambar-bukti-pengecekan" onclick="window.open(this.src, '_blank')">
                                </div>
                            </div>
                        `;
                    }

                    detailHasilPengecekan += `</div>`;
                });

                detailHasilPengecekan += `</div>`;
            }

            var html = `
                <div class="detail-row"><div class="detail-label">Judul Pengecekan</div><div class="detail-value"><strong>${escapeHtml(event.title)}</strong></div></div>
                <div class="detail-row"><div class="detail-label">Tanggal Pengecekan</div><div class="detail-value">${event.start ? event.start.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' }) : '-'}</div></div>
                <div class="detail-row"><div class="detail-label">Status</div><div class="detail-value"><span class="status-badge ${status}">${statusLabel(status)}</span></div></div>
                <div class="detail-row"><div class="detail-label">Keterangan Jadwal</div><div class="detail-value">${escapeHtml(props.keterangan)}</div></div>
                <div class="sparepart-section-title"><i class="bi bi-boxes me-2"></i> Daftar Sparepart yang Harus Dicek</div>
                <div class="table-responsive sparepart-table-wrapper">
                    <table class="table table-bordered sparepart-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width:70px;">No</th>
                                <th>Nama Sparepart</th>
                                <th style="width:170px;">Stok Sistem</th>
                            </tr>
                        </thead>
                        <tbody>${sparepartRows}</tbody>
                    </table>
                </div>
                ${detailHasilPengecekan}
                ${buatInfoKetepatanPengecekan(info)}
                ${informasiPenolakan}
            `;

            document.getElementById('eventDetailBody').innerHTML = html;

            var footer = '';

            if (status === 'pending') {
                footer += `<button type="button" class="btn btn-tolak-action" id="btnTolakPengecekan"><i class="bi bi-x-circle"></i> Tolak</button>`;
                footer += `<button type="button" class="btn btn-hasil-action" id="btnIsiHasilPengecekan"><i class="bi bi-clipboard-check"></i> Isi Hasil Pengecekan</button>`;
            } else if (status === 'menunggu_verifikasi') {
                footer += `<span class="status-badge menunggu_verifikasi"><i class="bi bi-hourglass-split"></i> Menunggu Verifikasi SPV</span>`;
            } else if (status === 'selesai') {
                footer += `<span class="status-badge selesai"><i class="bi bi-check-circle-fill"></i> Sudah Diselesaikan</span>`;
            } else {
                footer += `<span class="status-badge ditolak"><i class="bi bi-x-circle-fill"></i> Sudah Ditolak</span>`;
            }

            document.getElementById('eventDetailFooter').innerHTML = footer;

            if (status === 'pending') {
                document.getElementById('btnIsiHasilPengecekan').addEventListener('click', function() {
                    openHasilPengecekanModal();
                });

                document.getElementById('btnTolakPengecekan').addEventListener('click', function() {
                    openTolakPengecekanModal();
                });
            }

            new bootstrap.Modal(document.getElementById('eventDetailModal')).show();
        },

        eventDidMount: function(info) {
            var status = info.event.extendedProps.status || 'pending';
            info.el.classList.add('status-' + status);
            var titleElement = info.el.querySelector('.fc-event-title');
            if (!titleElement) return;

            if (status === 'selesai') {
                titleElement.innerHTML = '✔ ' + escapeHtml(info.event.title);
            } else if (status === 'ditolak') {
                titleElement.innerHTML = '✘ ' + escapeHtml(info.event.title);
            } else if (status === 'menunggu_verifikasi') {
                titleElement.innerHTML = '⏳ ' + escapeHtml(info.event.title);
            } else {
                titleElement.innerHTML = '⏳ ' + escapeHtml(info.event.title);
            }
        },

        datesSet: function(info) {
            var title = info.view.title;
            title = title.charAt(0).toUpperCase() + title.slice(1);

            document.getElementById('calendarTitle').innerText = title;
        }
    });

    /* =========================
       FORM HASIL PENGECEKAN
       ========================= */
    function openHasilPengecekanModal() {
        if (!selectedPengecekan) return;
        document.getElementById('formHasilPengecekan').action = basePengecekanUrl + '/' + selectedPengecekan.id + '/selesai';
        document.getElementById('hasil_judul_pengecekan').value = selectedPengecekan.title;

        var container = document.getElementById('hasilPengecekanContainer');
        container.innerHTML = '';

        selectedPengecekan.details.forEach(function(detail, index) {
            var stokSistem = detail.stok_saat_ini !== null && detail.stok_saat_ini !== undefined ? parseInt(detail.stok_saat_ini) : 0;
            var card = `
                <div class="hasil-sparepart-card" data-detail-id="${detail.id}">
                    <input type="hidden" name="detail_ids[]" value="${detail.id}">
                    <div class="hasil-sparepart-title">
                        <i class="bi bi-box-seam me-2"></i>${index + 1}. ${escapeHtml(detail.nama_sparepart)}
                    </div>
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="form-label fw-semibold">Stok Sistem</label>
                            <div class="stok-sistem-box">${stokSistem} unit</div>
                            <small class="text-muted">Stok sistem saat ini</small>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label fw-semibold" for="stok_fisik_${detail.id}">Stok Fisik <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control input-stok-fisik" id="stok_fisik_${detail.id}" name="stok_fisik[${detail.id}]" data-detail-id="${detail.id}" data-stok-sistem="${stokSistem}" placeholder="Masukkan stok fisik" required>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <label class="form-label fw-semibold">Selisih</label>
                            <div class="selisih-box" id="selisih_box_${detail.id}"><span id="selisih_${detail.id}">-</span></div>
                            <small class="text-muted">Stok Fisik - Stok Sistem</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="kondisi_${detail.id}">Kondisi Sparepart <span class="text-danger">*</span></label>
                        <select class="form-control" id="kondisi_${detail.id}" name="kondisi[${detail.id}]" required>
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                    <div class="form-keterangan-selisih d-none" id="bagian_selisih_${detail.id}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="penyebab_selisih_${detail.id}">Penyebab Selisih <span class="text-danger">*</span></label>
                            <select class="form-control" id="penyebab_selisih_${detail.id}" name="penyebab_selisih[${detail.id}]">
                                <option value="">-- Pilih Penyebab --</option>
                                <option value="Rusak">Rusak</option>
                                <option value="Hilang">Hilang</option>
                                <option value="Kesalahan Pencatatan">Kesalahan Pencatatan</option>
                                <option value="Stok Ditemukan">Stok Ditemukan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="keterangan_selisih_${detail.id}">Keterangan Selisih <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="keterangan_selisih_${detail.id}" name="keterangan_selisih[${detail.id}]" rows="2" placeholder="Jelaskan penyebab atau kondisi selisih stok..."></textarea>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="catatan_pengecekan_${detail.id}">Catatan Pengecekan <small class="text-muted">(opsional)</small></label>
                        <textarea class="form-control" id="catatan_pengecekan_${detail.id}" name="catatan_pengecekan[${detail.id}]" rows="2" placeholder="Masukkan catatan hasil pengecekan..."></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold" for="gambar_bukti_${detail.id}">Bukti Pengecekan <small class="text-muted">(opsional)</small></label>
                        <input type="file" class="form-control validasi-bukti-pengecekan" id="gambar_bukti_${detail.id}" name="gambar_bukti[${detail.id}]" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif" data-detail-id="${detail.id}">
                        <small class="text-muted d-block mt-1">Format JPG, JPEG, PNG, GIF. Maksimal 5 MB.</small>
                        <div class="file-error" id="error_gambar_bukti_${detail.id}"></div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', card);
        });

        pasangPerhitunganSelisih();
        pasangValidasiBuktiPengecekan();

        var detailModal = bootstrap.Modal.getInstance(document.getElementById('eventDetailModal'));
        if (detailModal) detailModal.hide();
        new bootstrap.Modal(document.getElementById('modalHasilPengecekan')).show();
    }

    /* =========================
       MODAL TOLAK
       ========================= */
    function openTolakPengecekanModal() {
        if (!selectedPengecekan) return;

        document.getElementById('formTolakPengecekan').action = basePengecekanUrl + '/' + selectedPengecekan.id + '/tolak';

        document.getElementById('tolak_judul_pengecekan').value = selectedPengecekan.title;
        document.getElementById('tolak_keterangan').value = '';
        document.getElementById('tolak_alasan').value = '';

        var detailModal = bootstrap.Modal.getInstance(document.getElementById('eventDetailModal'));
        if (detailModal) detailModal.hide();
        new bootstrap.Modal(document.getElementById('modalTolakPengecekan')).show();
    }

    /* =========================
       HITUNG SELISIH
       ========================= */
    function pasangPerhitunganSelisih() {
        document.querySelectorAll('.input-stok-fisik').forEach(function(input) {
            input.addEventListener('input', function() {
                var detailId = this.dataset.detailId;
                var stokSistem = parseInt(this.dataset.stokSistem) || 0;
                var stokFisik = this.value === '' ? null : parseInt(this.value);
                var selisihText = document.getElementById('selisih_' + detailId);
                var selisihBox = document.getElementById('selisih_box_' + detailId);
                var bagianSelisih = document.getElementById('bagian_selisih_' + detailId);
                var penyebab = document.getElementById('penyebab_selisih_' + detailId);
                var keterangan = document.getElementById('keterangan_selisih_' + detailId);

                if (stokFisik === null) {
                    selisihText.innerText = '-';
                    selisihBox.classList.remove('normal', 'bermasalah');
                    bagianSelisih.classList.add('d-none');
                    penyebab.removeAttribute('required');
                    keterangan.removeAttribute('required');
                    return;
                }

                var selisih = stokFisik - stokSistem;
                selisihText.innerText = selisih;

                if (selisih === 0) {
                    selisihBox.classList.remove('bermasalah');
                    selisihBox.classList.add('normal');
                    bagianSelisih.classList.add('d-none');
                    penyebab.removeAttribute('required');
                    keterangan.removeAttribute('required');
                    penyebab.value = '';
                    keterangan.value = '';
                } else {
                    selisihBox.classList.remove('normal');
                    selisihBox.classList.add('bermasalah');
                    bagianSelisih.classList.remove('d-none');
                    penyebab.setAttribute('required', 'required');
                    keterangan.setAttribute('required', 'required');
                }
            });
        });
    }

    /* =========================
       VALIDASI GAMBAR
       ========================= */
    function pasangValidasiBuktiPengecekan() {
        document.querySelectorAll('.validasi-bukti-pengecekan').forEach(function(input) {
            input.addEventListener('change', function() {
                var detailId = this.dataset.detailId;
                var errorElement = document.getElementById('error_gambar_bukti_' + detailId);
                var file = this.files[0];
                errorElement.innerHTML = '';

                if (!file) return;

                var maxSize = 5 * 1024 * 1024;
                var allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                var extension = file.name.toLowerCase().split('.').pop();

                if (!allowedExtensions.includes(extension)) {
                    errorElement.innerHTML = '<i class="bi bi-exclamation-circle"></i> Format file tidak diperbolehkan. Gunakan JPG, JPEG, PNG, atau GIF.';
                    this.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    errorElement.innerHTML = '<i class="bi bi-exclamation-circle"></i> Ukuran file terlalu besar. Maksimal 5 MB.';
                    this.value = '';
                    return;
                }
                errorElement.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> File sesuai. Siap diunggah.</span>';
            });
        });
    }

    /* =========================
       RENDER CALENDAR
       ========================= */
    calendar.render();

    document.getElementById('btnPrev').addEventListener('click', function() {
        calendar.prev();
    });

    document.getElementById('btnNext').addEventListener('click', function() {
        calendar.next();
    });

    document.getElementById('btnToday').addEventListener('click', function() {
        calendar.today();
    });

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

});
</script>

</body>
</html>
