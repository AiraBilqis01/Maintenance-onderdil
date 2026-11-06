@extends('layouts.admin.app')

@section('title', 'Pengecekan Sparepart')

@section('content')

<style>
    /* =========================================================
                        HEADER HALAMAN
       ========================================================= */
    .page-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        padding: 2rem 2.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h2 {
        font-weight: 600;
        margin: 0;
        font-size: 1.6rem;
    }

    .page-header p {
        margin: 0.3rem 0 0;
        opacity: 0.8;
        font-size: 0.95rem;
    }

    /* =========================================================
                            TOMBOL HEADER
       ========================================================= */
    .btn-print-header {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 10px;
        padding: 0.7rem 1.5rem;
        font-weight: 500;
        color: white;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        backdrop-filter: blur(10px);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-print-header:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        color: white;
    }

    .btn-print-header.excel {
        background: rgba(72,187,120,0.85);
        border-color: rgba(72,187,120,0.4);
    }

    .btn-print-header.excel:hover {
        background: rgba(72,187,120,1);
    }

    .btn-print-header.pdf {
        background: rgba(229,62,62,0.85);
        border-color: rgba(229,62,62,0.4);
    }

    .btn-print-header.pdf:hover {
        background: rgba(229,62,62,1);
    }

    .btn-tambah-header {
        background: white !important;
        color: #1a1a2e !important;
        border: none !important;
        border-radius: 10px;
        padding: 0.7rem 1.5rem;
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-tambah-header:hover {
        background: #f1f5f9 !important;
        transform: translateY(-2px);
    }

    /* =========================================================
                                FILTER
       ========================================================= */
    .filter-box {
        background: white;
        border: 1px solid #eef2f7;
        border-radius: 16px;
        padding: 1.5rem 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    }

    .filter-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 1rem;
    }

    .filter-title i {
        color: #2b6cb0;
        margin-right: 6px;
    }

    .filter-box label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.3rem;
    }

    .filter-box .form-control {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 0.9rem;
        padding: 0.5rem 0.9rem;
    }

    .filter-box .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* =========================================================
                            TOMBOL FILTER
       ========================================================= */
    .btn-filter,
    .btn-reset {
        width: 45px;
        height: 38px;
        padding: 0;
        border: none;
        border-radius: 10px;
        font-weight: 500;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-filter {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .btn-filter:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }

    .btn-reset {
        background: #edf2f7;
        color: #4a5568;
        text-decoration: none;
    }

    .btn-reset:hover {
        background: #e2e8f0;
        color: #2d3748;
        text-decoration: none;
    }

    .filter-actions {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        width: auto;
    }

    /* =========================================================
                            CARD DAN TABEL
       ========================================================= */
    .card-custom {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid #eef2f7;
    }

    .card-custom .card-header {
        background: white;
        padding: 1.2rem 2rem;
        border-bottom: 1px solid #eef2f7;
    }

    .card-custom .card-header h3 {
        color: #1a1a2e;
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
    }

    /* =========================================================
                            RESPONSIVE TABEL
       ========================================================= */
    .table-responsive-custom {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
    }

    .table-custom {
        min-width: 1450px;
        width: 100%;
        margin-bottom: 0;
    }

    /* =========================================================
                            HEADER TABEL
       ========================================================= */
    .table-custom thead th {
        padding: 1rem 1.2rem;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        background: #f8fafc;
        color: #4a5568;
        border-bottom: 2px solid #eef2f7;
        white-space: nowrap;
        vertical-align: middle;
        text-align: center;
    }

    /* =========================================================
                            ISI TABEL
       ========================================================= */
    .table-custom tbody td {
        padding: 1rem 1.2rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f4f8;
        color: #2d3748;
    }
    .table-custom tbody tr:hover {
        background: #f8fafc;
    }

    /* =========================================================
                                STATUS
       ========================================================= */
    .badge-status {
        display: inline-block;
        padding: 0.35rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.78rem;
        white-space: nowrap;
    }

    .badge-pending {
        background: #fefcbf;
        color: #744210;
    }

    .badge-menunggu {
        background: #e0f2fe;
        color: #0369a1;
    }

    .badge-selesai {
        background: #c6f6d5;
        color: #22543d;
    }

    .badge-ditolak {
        background: #fed7d7;
        color: #822727;
    }

    /* =========================================================
                                ONDERDIL
       ========================================================= */
    .badge-onderdil {
        display: inline-block;
        background: #edf2f7;
        color: #4a5568;
        padding: 0.3rem 0.6rem;
        border-radius: 7px;
        margin: 2px;
        font-size: 0.78rem;
    }

    /* =========================================================
                            TOMBOL AKSI
       ========================================================= */
    .aksi-wrapper {
        display: flex;
        flex-flow: row nowrap;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .btn-action {
        border-radius: 8px;
        padding: 0.4rem 0.75rem;
        font-weight: 500;
        font-size: 0.8rem;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        margin: 2px;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-detail-action {
        background: #faf5ff;
        color: #6b46c1;
    }

    .btn-edit-action {
        background: #ebf8ff;
        color: #2b6cb0;
    }

    .btn-delete-action {
        background: #fff5f5;
        color: #c53030;
    }

    .btn-verifikasi-action {
        background: #c6f6d5;
        color: #22543d;
    }

    .btn-tolak-action {
        background: #fed7d7;
        color: #822727;
    }

    /* =========================================================
                                ALERT
       ========================================================= */
    .alert-custom {
        border-radius: 12px;
        padding: 1rem 1.5rem;
        background: #f0fff4;
        color: #22543d;
        border-left: 4px solid #48bb78;
        margin-bottom: 1.5rem;
    }

    /* =========================================================
                            RESPONSIVE
       ========================================================= */
    @media (max-width: 767.98px) {
        .page-header {
            padding: 1.5rem;
        }

        .filter-actions {
            margin-top: 0.3rem;
        }
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-12">
            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}
            <div class="page-header">
                <div>
                    <h2>
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Pengecekan Sparepart
                    </h2>
                    <p>
                        Kelola jadwal dan hasil pengecekan stok fisik sparepart
                    </p>
                </div>
                <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                    {{-- EXPORT EXCEL --}}
                    <a href="{{ route('supervisor.pengecekan-onderdil.excel', request()->query()) }}" class="btn-print-header excel">
                        <i class="fas fa-file-excel"></i>
                        Export Excel
                    </a>
                    {{-- EXPORT PDF --}}
                    <a href="{{ route('supervisor.pengecekan-onderdil.pdf', request()->query()) }}" class="btn-print-header pdf">
                        <i class="fas fa-file-pdf"></i>
                        Export PDF
                    </a>
                    {{-- TAMBAH JADWAL --}}
                    <div style="border-left:1px solid rgba(255,255,255,0.3); padding-left:1rem;">
                        @include('supervisor.pengecekan-onderdil.create')
                    </div>
                </div>
            </div>

            {{-- ================================================= --}}
            {{-- ALERT SUCCESS --}}
            {{-- ================================================= --}}
            @if(session('sukses'))
                <div class="alert alert-custom">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('sukses') }}
                </div>
            @endif

            {{-- ================================================= --}}
            {{-- ALERT ERROR --}}
            {{-- ================================================= --}}
            @if($errors->any())
                <div class="alert alert-danger" style="border-radius:12px; margin-bottom:1.5rem;">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- ================================================= --}}
            {{-- FILTER --}}
            {{-- ================================================= --}}
            <div class="filter-box">
                <div class="filter-title">
                    <i class="fas fa-filter"></i>
                    Filter Data Pengecekan
                </div>
                <form action="{{ route('supervisor.pengecekan-onderdil.index') }}" method="GET">
                    <div class="row align-items-end">
                        {{-- STATUS --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter_status">Status</label>
                                <select name="status" id="filter_status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>
                                        Menunggu Verifikasi
                                    </option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                </select>
                            </div>
                        </div>
                        {{-- TENAGA KERJA --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter_tenaga_kerja">Tenaga Kerja</label>
                                <select name="tenaga_kerja" id="filter_tenaga_kerja" class="form-control">
                                    <option value="">Semua Tenaga Kerja</option>
                                    @foreach($tenagaKerjaFilter as $tk)
                                        <option value="{{ $tk->id }}" {{ request('tenaga_kerja') == $tk->id ? 'selected' : '' }}>
                                            {{ $tk->name }}{{ $tk->trashed() ? ' (Akun Terhapus)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- DARI TANGGAL --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter_tanggal_dari">Dari Tanggal</label>
                                <input type="date" name="tanggal_dari" id="filter_tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                            </div>
                        </div>
                        {{-- SAMPAI TANGGAL --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter_tanggal_sampai">Sampai Tanggal</label>
                                <input type="date" name="tanggal_sampai" id="filter_tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                            </div>
                        </div>
                        {{-- TOMBOL FILTER DAN RESET --}}
                        <div class="col-md-auto">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="filter-actions">
                                    <button type="submit" class="btn-filter" title="Terapkan Filter">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="{{ route('supervisor.pengecekan-onderdil.index') }}" class="btn-reset" title="Reset Filter">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ================================================= --}}
            {{-- DATA JADWAL --}}
            {{-- ================================================= --}}
            <div class="card card-custom">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-list mr-2"></i>
                        Data Jadwal Pengecekan
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive-custom">
                        <table id="example1" class="table table-custom">
                            <thead>
                                <tr>
                                    <th style="width:60px;">No</th>
                                    <th style="min-width:190px;">Judul Pengecekan</th>
                                    <th style="min-width:145px;">Target Pengecekan</th>
                                    <th style="min-width:155px;">Hasil Dikirim (ACC)</th>
                                    <th style="min-width:160px;">Tenaga Kerja</th>
                                    <th style="min-width:230px;">Nama Sparepart</th>
                                    <th style="min-width:170px;">Status</th>
                                    <th style="min-width:240px;">Ketepatan Waktu</th>
                                    <th style="min-width:220px;">Keterangan</th>
                                    <th style="min-width:340px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($jadwalPengecekan as $item)
                                    @php
                                        /* Digunakan untuk membedakan:
                                         * 1. Tenaga Kerja sudah mengirim hasil pengecekan.
                                         * 2. Tenaga Kerja mengajukan penolakan tugas. */
                                        $adaHasilPengecekan = $item->details->contains(function ($detail) {
                                            return $detail->stok_fisik !== null;
                                        });
                                    @endphp
                                    <tr>
                                        {{-- NO --}}
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        {{-- JUDUL --}}
                                        <td>
                                            <strong>
                                                {{ $item->judul_pengecekan }}
                                            </strong>
                                        </td>
                                        {{-- ================================================= --}}
                                        {{-- TARGET PENGECEKAN TERAKHIR DARI SUPERVISOR --}}
                                        {{-- ================================================= --}}
                                        <td class="text-center" data-order="{{ \Carbon\Carbon::parse($item->tanggal_pengecekan)->format('Y-m-d') }}">
                                            {{ \Carbon\Carbon::parse($item->tanggal_pengecekan)->format('d/m/Y') }}
                                        </td>
                                        {{-- ================================================= --}}
                                        {{-- TANGGAL HASIL DIKIRIM YANG SUDAH DI-ACC --}}
                                        {{-- ================================================= --}}
                                        <td class="text-center" data-order="{{ $item->status === 'selesai' && !empty($item->tanggal_kirim_hasil)
                                                ? \Carbon\Carbon::parse($item->tanggal_kirim_hasil)->format('Y-m-d') : '' }}">
                                            @if($item->status === 'selesai' && !empty($item->tanggal_kirim_hasil))
                                                {{ \Carbon\Carbon::parse($item->tanggal_kirim_hasil)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        {{-- TENAGA KERJA --}}
                                        <td class="text-center">
                                            {{ $item->tenagaKerja->name ?? '-' }}
                                        </td>
                                        {{-- ONDERDIL --}}
                                        <td class="text-center">
                                            @forelse($item->details as $detail)
                                                <span class="badge-onderdil">
                                                    {{ $detail->nama_sparepart_snapshot }}
                                                </span>
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                        {{-- STATUS --}}
                                        <td class="text-center">
                                            @if($item->status == 'pending')
                                                <span class="badge-status badge-pending">
                                                    <i class="fas fa-clock mr-1"></i> Pending
                                                </span>
                                            @elseif($item->status == 'menunggu_verifikasi')
                                                <span class="badge-status badge-menunggu">
                                                    <i class="fas fa-hourglass-half mr-1"></i> Menunggu Verifikasi
                                                </span>
                                            @elseif($item->status == 'selesai')
                                                <span class="badge-status badge-selesai">
                                                    <i class="fas fa-check-circle mr-1"></i> Selesai
                                                </span>
                                            @elseif($item->status == 'ditolak')
                                                <span class="badge-status badge-ditolak">
                                                    <i class="fas fa-times-circle mr-1"></i> Ditolak
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    {{ $item->status }}
                                                </span>
                                            @endif
                                        </td>
                                        {{-- =====================================================
                                            KETEPATAN WAKTU PENGECEKAN ONDERDIL
                                            ===================================================== --}}
                                        <td class="text-center">
                                            @php
                                                /* Hitung ketepatan waktu berdasarkan target aktif dan tanggal kirim hasil. */
                                                $ketepatan = \App\Support\KetepatanJadwal::hitung($item, 'pengecekan');

                                                /* Tentukan warna badge berdasarkan hasil. */
                                                $kelasKetepatan = match ($ketepatan['warna']) {
                                                    'danger' => 'badge-ditolak',
                                                    'success' => 'badge-selesai',
                                                    default => 'badge-menunggu',
                                                };
                                            @endphp

                                            {{-- Ketepatan waktu terhadap target aktif --}}
                                            <span class="badge-status {{ $kelasKetepatan }}">
                                                {{ $ketepatan['label'] }}
                                            </span>

                                            {{-- Keterlambatan yang pernah terjadi sebelum revisi --}}
                                            @if($ketepatan['pernah_terlambat_sebelum_revisi'])
                                                <div class="text-danger small mt-2">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Pernah terlambat {{ $ketepatan['hari_terlambat_tercatat'] }} hari sebelum revisi
                                                </div>
                                            @endif

                                            {{-- Jumlah perubahan tanggal --}}
                                            @if($ketepatan['jumlah_revisi'] > 0)
                                                <div class="text-muted small mt-1">
                                                    <i class="fas fa-history"></i>
                                                    Revisi: {{ $ketepatan['jumlah_revisi'] }} kali
                                                </div>
                                            @endif
                                        </td>
                                        {{-- KETERANGAN --}}
                                        <td class="text-center">
                                            {{ $item->keterangan ?? '-' }}
                                        </td>
                                        {{-- ================================================= --}}
                                        {{-- AKSI --}}
                                        {{-- ================================================= --}}
                                        <td class="text-center">
                                            <div class="aksi-wrapper">
                                                {{-- DETAIL --}}
                                                @include('supervisor.pengecekan-onderdil.detail', ['jadwal' => $item])
                                                {{-- ================================================= --}}
                                                {{-- EDIT --}}
                                                {{-- HANYA PENDING --}}
                                                {{-- ================================================= --}}
                                                @if($item->status == 'pending')
                                                    @include('supervisor.pengecekan-onderdil.edit', ['jadwal' => $item])
                                                @endif
                                                {{-- ================================================= --}}
                                                {{-- MENUNGGU VERIFIKASI --}}
                                                {{-- ================================================= --}}
                                                @if($item->status === 'menunggu_verifikasi')
                                                    {{-- ================================================= --}}
                                                    {{-- A. HASIL PENGECEKAN SUDAH DIKIRIM TENAGA KERJA --}}
                                                    {{-- ================================================= --}}
                                                    @if($adaHasilPengecekan)
                                                        {{-- ACC HASIL: satu-satunya aksi yang menyinkronkan stok master --}}
                                                        <form action="{{ route('supervisor.pengecekan-onderdil.verifikasi', $item->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-action btn-verifikasi-action" onclick="return confirm('Apakah Anda yakin ingin memverifikasi hasil pengecekan? Stok master akan disesuaikan dengan stok fisik, sedangkan nilai pemakaian tidak akan diubah.');"><i class="fas fa-check"></i> ACC Hasil</button>
                                                        </form>

                                                        {{-- TOLAK HASIL: stok master tidak berubah, hasil dikembalikan ke Tenaga Kerja --}}
                                                        <button type="button" class="btn btn-action btn-tolak-action" data-toggle="modal" data-target="#modalTolakHasil{{ $item->id }}"><i class="fas fa-times"></i> Tolak Hasil</button>

                                                    {{-- ================================================= --}}
                                                    {{-- B. TENAGA KERJA MENGAJUKAN PENOLAKAN TUGAS --}}
                                                    {{-- ================================================= --}}
                                                    @elseif(!empty($item->alasan_penolakan))
                                                        {{-- ACC TOLAK: hanya menyetujui penolakan tugas, stok master tidak berubah --}}
                                                        <form action="{{ route('supervisor.pengecekan-onderdil.verifikasi-tolak', $item->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="btn btn-action btn-tolak-action" onclick="return confirm('Apakah Anda yakin ingin menyetujui penolakan tugas dari Tenaga Kerja? Status akan menjadi Ditolak dan data stok tidak akan berubah.');"><i class="fas fa-check"></i> ACC Tolak</button>
                                                        </form>
                                                    @endif
                                                @endif

                                                {{-- ================================================= --}}
                                                {{-- DELETE --}}
                                                {{-- pending / ditolak / selesai --}}
                                                {{-- ================================================= --}}
                                                @if(in_array($item->status, ['pending', 'ditolak', 'selesai']))
                                                    @include('supervisor.pengecekan-onderdil.delete', ['jadwal' => $item])
                                                @endif
                                            </div>

                                            {{-- ================================================= --}}
                                            {{-- MODAL TOLAK HASIL --}}
                                            {{-- ================================================= --}}
                                            @if($item->status === 'menunggu_verifikasi' && $adaHasilPengecekan)
                                                <div class="modal fade" id="modalTolakHasil{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalTolakHasilLabel{{ $item->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <form action="{{ route('supervisor.pengecekan-onderdil.tolak-hasil', $item->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')

                                                                {{-- HEADER MODAL --}}
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title" id="modalTolakHasilLabel{{ $item->id }}"><i class="fas fa-times-circle mr-2"></i> Tolak Hasil Pengecekan</h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                </div>

                                                                {{-- BODY MODAL --}}
                                                                <div class="modal-body text-left">
                                                                    <div class="alert alert-light border">
                                                                        <strong>{{ $item->judul_pengecekan }}</strong><br>
                                                                        <small class="text-muted">Tenaga Kerja: {{ $item->tenagaKerja->name ?? '-' }}</small>
                                                                    </div>

                                                                    {{-- KETERANGAN PENOLAKAN --}}
                                                                    <div class="form-group">
                                                                        <label for="keterangan_tolak_{{ $item->id }}">Keterangan Penolakan <span class="text-danger">*</span></label>
                                                                        <textarea name="keterangan_tolak" id="keterangan_tolak_{{ $item->id }}" class="form-control" rows="3" maxlength="1000" placeholder="Contoh: Hasil pengecekan perlu dilakukan ulang." required></textarea>
                                                                    </div>

                                                                    {{-- ALASAN PENOLAKAN --}}
                                                                    <div class="form-group">
                                                                        <label for="alasan_penolakan_{{ $item->id }}">Alasan Penolakan <span class="text-danger">*</span></label>
                                                                        <textarea name="alasan_penolakan" id="alasan_penolakan_{{ $item->id }}" class="form-control" rows="3" maxlength="2000" placeholder="Jelaskan alasan hasil pengecekan ditolak..." required></textarea>
                                                                    </div>

                                                                    <div class="alert alert-warning mb-0">
                                                                        <i class="fas fa-info-circle mr-1"></i>
                                                                        Setelah hasil ditolak, status kembali menjadi <strong>Pending</strong>, hasil pengecekan sebelumnya dikosongkan, dan Tenaga Kerja harus melakukan pengecekan ulang. <strong>Data stok master tidak berubah.</strong>
                                                                    </div>
                                                                </div>

                                                                {{-- FOOTER MODAL --}}
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-danger"><i class="fas fa-times mr-1"></i> Tolak Hasil</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": false,
            "scrollX": true,
            "lengthChange": true,
            "autoWidth": false,
            "ordering": true,
            "order": [],
            "pageLength": 10,
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": [9]
                }
            ],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "zeroRecords": "Data tidak ditemukan",
                "emptyTable": "Belum ada data jadwal pengecekan sparepart",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "→",
                    "previous": "←"
                }
            }
        });
    });
</script>
@endsection
