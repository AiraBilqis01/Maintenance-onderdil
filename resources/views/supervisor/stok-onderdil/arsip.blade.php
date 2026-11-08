@extends('layouts.admin.app')

@section('title', 'Arsip Manajemen Stok Onderdil')

@section('content')
<style>
    .arsip-stok-header {
        background: linear-gradient(
            135deg,
            #1a1a2e 0%,
            #16213e 50%,
            #0f3460 100%
        );
        color: white;
        padding: 25px 30px;
        border-radius: 14px;
        margin-bottom: 24px;
    }

    .arsip-stok-header h3 {
        color: white;
        margin: 0;
        font-size: 22px;
        font-weight: 600;
    }

    .arsip-stok-header p {
        margin: 8px 0 0;
        color: rgba(255, 255, 255, 0.85);
        font-size: 14px;
    }

    .arsip-stok-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
    }

    .arsip-stok-table {
        width: 100%;
        white-space: nowrap;
    }

    .arsip-stok-table thead th {
        background: #f1f5f9;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        vertical-align: middle;
        text-align: center;
        padding: 14px 12px;
    }

    .arsip-stok-table tbody td {
        vertical-align: middle;
        font-size: 13px;
        padding: 12px;
    }

    .arsip-stok-image {
        width: 55px;
        height: 55px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .arsip-stok-note {
        font-size: 12px;
        color: #64748b;
        white-space: normal;
        min-width: 180px;
    }

    .arsip-stok-actions {
        white-space: nowrap;
    }
</style>

<section class="content">
    <div class="container-fluid">

        {{-- HEADER HALAMAN --}}
        <div class="arsip-stok-header">
            <h3>
                <i class="fas fa-archive mr-2"></i> Arsip Manajemen Stok Onderdil
            </h3>
            <p>
                Daftar onderdil yang telah diarsipkan.
                Data dan riwayatnya tetap dipertahankan
                serta dapat dipulihkan kembali.
            </p>
        </div>

        {{-- TOMBOL KEMBALI --}}
        <div class="mb-3">
            <a href="{{ route('supervisor.stok-onderdil.index') }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left mr-1"></i>
                Kembali ke Manajemen Stok
            </a>
        </div>

        {{-- NOTIFIKASI BERHASIL --}}
        @if(session('sukses'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('sukses') }}
            </div>
        @endif

        {{-- NOTIFIKASI KESALAHAN --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- INFORMASI ARSIP --}}
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            Onderdil yang diarsipkan tidak ditampilkan
            dalam daftar stok aktif. Pemulihan hanya
            mengaktifkan kembali data tanpa mengubah
            jumlah stok secara otomatis.
        </div>

        {{-- TABEL ARSIP --}}
        <div class="card arsip-stok-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-boxes mr-2"></i> Data Arsip Sparepart
                </h4>
                <span class="badge badge-secondary">
                    {{ $arsip->count() }} Data
                </span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped arsip-stok-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Sparepart</th>
                                <th>Stok Terakhir</th>
                                <th>Pemakaian</th>
                                <th>Kondisi</th>
                                <th>Keterangan</th>
                                <th>Tanggal Arsip</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($arsip as $item)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>

                                    {{-- GAMBAR --}}
                                    <td class="text-center">
                                        @if($item->gambar)
                                            <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama_sparepart }}" class="arsip-stok-image">
                                        @else
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        @endif
                                    </td>

                                    {{-- NAMA SPAREPART --}}
                                    <td>
                                        <strong>
                                            {{ $item->nama_sparepart }}
                                        </strong>
                                    </td>

                                    {{-- STOK TERAKHIR --}}
                                    <td class="text-center">
                                        {{ number_format($item->stok_terbaru) }} Unit
                                    </td>

                                    {{-- PEMAKAIAN --}}
                                    <td class="text-center">
                                        {{ number_format($item->pemakaian) }} Unit
                                    </td>

                                    {{-- KONDISI --}}
                                    <td class="text-center">
                                        {{ $item->kondisi_sekarang ?? '-' }}
                                    </td>

                                    {{-- KETERANGAN --}}
                                    <td>
                                        <div class="arsip-stok-note">
                                            {{ $item->keterangan ?: '-' }}
                                        </div>
                                    </td>

                                    {{-- TANGGAL ARSIP --}}
                                    <td class="text-center">
                                        {{ $item->deleted_at->format('d/m/Y H:i') }}
                                    </td>

                                    {{-- TOMBOL PULIHKAN --}}
                                    <td class="text-center arsip-stok-actions">
                                        <form action="{{ route('supervisor.stok-onderdil.restore', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin memulihkan sparepart ini ke Manajemen Stok? Pastikan jumlah stok masih sesuai dengan kondisi fisik.');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-undo mr-1"></i> Pulihkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x d-block mb-2"></i> Belum ada data onderdil yang diarsipkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
