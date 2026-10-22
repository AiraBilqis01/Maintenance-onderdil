@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
<style>
    /* Import Font Google yang lebih modern */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .dashboard-container {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Styling Dasar Card */
    .stat-card {
        border-radius: 24px;
        border: none;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 1;
        background: #ffffff;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
    }
    
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1);
    }

    /* Shape Dekoratif di Belakang */
    .stat-card::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        z-index: -1;
        transition: all 0.4s ease;
    }
    .stat-card:hover::before {
        transform: scale(1.2);
    }

    .stat-card-body {
        padding: 1.8rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-info h6 {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
        color: #64748b;
    }

    .stat-info h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0;
        line-height: 1.2;
        color: #1e293b;
    }

    .stat-icon {
        width: 65px;
        height: 65px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(10deg);
    }

    /* TEMA WARNA (Lebih Cerah & Modern) */
    /* Tenaga Kerja (Biru) */
    .card-blue .stat-icon { background: #e0f2fe; color: #0284c7; }
    .card-blue .card-footer-btn { background: #f0f9ff; color: #0284c7; }
    .card-blue:hover .card-footer-btn { background: #0284c7; color: white; }

    /* Pemeliharaan (Ungu) */
    .card-purple .stat-icon { background: #f3e8ff; color: #9333ea; }
    .card-purple .card-footer-btn { background: #faf5ff; color: #9333ea; }
    .card-purple:hover .card-footer-btn { background: #9333ea; color: white; }

    /* Sparepart (Hijau) */
    .card-green .stat-icon { background: #dcfce7; color: #16a34a; }
    .card-green .card-footer-btn { background: #f0fdf4; color: #16a34a; }
    .card-green:hover .card-footer-btn { background: #16a34a; color: white; }

    /* Peringatan (Merah) */
    .card-red .stat-icon { background: #fee2e2; color: #dc2626; }
    .card-red .card-footer-btn { background: #fef2f2; color: #dc2626; }
    .card-red:hover .card-footer-btn { background: #dc2626; color: white; }

    /* Tombol Link di dalam Card */
    .card-footer-btn {
        display: block;
        width: 100%;
        text-align: center;
        padding: 1rem;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border-radius: 0 0 24px 24px;
        border-top: 1px solid rgba(0,0,0,0.03);
    }
    
    .card-footer-btn:hover {
        text-decoration: none;
    }

    /* Styling Tabel Bawah */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    .table-modern th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #e2e8f0;
    }
    .table-modern td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.95rem;
    }
    .table-modern tbody tr {
        transition: all 0.2s ease;
    }
    .table-modern tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
    }

    .badge-soft-danger {
        background: #fee2e2;
        color: #b91c1c;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
    }
</style>

<div class="dashboard-container py-3">
    
    <!-- Bagian 4 Card Atas -->
    <div class="row">
        <!-- Card 1: Tenaga Kerja -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card card-blue">
                <div class="stat-card-body">
                    <div class="stat-info">
                        <h6>Total Tenaga Kerja</h6>
                        <h2 data-count="{{ $totalTenagaKerja }}">0</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <a href="{{ route('supervisor.kelola-tenagakerja.index') }}" class="card-footer-btn">
                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Card 2: Pemeliharaan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card card-purple">
                <div class="stat-card-body">
                    <div class="stat-info">
                        <h6>Total Jadwal</h6>
                        <h2 data-count="{{ $totalLaporan }}">0</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <a href="{{ route('supervisor.jadwal-pemeliharaan.index') }}" class="card-footer-btn">
                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Card 3: Stok Onderdil -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card card-green">
                <div class="stat-card-body">
                    <div class="stat-info">
                        <h6>Jenis Sparepart</h6>
                        <h2 data-count="{{ $totalSparepart }}">0</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
                <a href="{{ route('supervisor.stok-onderdil.index') }}" class="card-footer-btn">
                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Card 4: Peringatan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card card-red">
                <div class="stat-card-body">
                    <div class="stat-info">
                        <h6>Stok Menipis (< 10)</h6>
                        <h2 data-count="{{ $totalStokMenipis }}">0</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                <a href="{{ route('supervisor.stok-onderdil.index') }}" class="card-footer-btn">
                    Cek Stok <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Bagian Tabel Bawah -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card" style="border-radius: 24px; border: none; box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05); overflow: hidden;">
                <div class="card-header bg-white" style="padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color: #1e293b;">
                            <i class="fas fa-bell text-danger me-2"></i> Daftar Sparepart Perlu Dibeli
                        </h5>
                        <span class="badge badge-soft-danger">{{ $totalStokMenipis }} Item</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-modern mb-0">
                            <thead>
                                <tr>
                                    <th class="pl-4">Nama Sparepart</th>
                                    <th>Sisa Stok</th>
                                    <th>Kondisi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stokMenipis as $stok)
                                <tr>
                                    <td class="pl-4 fw-bold text-dark">{{ $stok->nama_sparepart }}</td>
                                    <td>
                                        <span class="badge-soft-danger">
                                            {{ $stok->stok_terbaru }} Unit
                                        </span>
                                    </td>
                                    <td>{{ $stok->kondisi_sekarang }}</td>
                                    <td class="text-muted">{{ $stok->keterangan ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div style="width: 80px; height: 80px; background: #dcfce7; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2.5rem;">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">Semua Stok Aman!</h6>
                                        <p class="text-muted mb-0">Tidak ada sparepart yang perlu dibeli saat ini.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const counters = document.querySelectorAll('.stat-info h2');
        const speed = 200;

        counters.forEach(counter => {
            const animate = () => {
                const value = +counter.getAttribute('data-count');
                const data = +counter.innerText;
                
                const time = value / speed;
                if (data < value) {
                    counter.innerText = Math.ceil(data + time);
                    setTimeout(animate, 1);
                } else {
                    counter.innerText = value;
                }
            }
            animate();
        });
    });
</script>
@endsection