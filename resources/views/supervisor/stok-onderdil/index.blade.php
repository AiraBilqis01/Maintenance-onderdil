@extends('layouts.admin.app')

@section('title', 'Stok Onderdil')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        padding: 2rem 2.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h2 {
        font-weight: 600;
        margin: 0;
        font-size: 1.6rem;
        letter-spacing: 0.5px;
    }

    .page-header p {
        margin: 0.3rem 0 0 0;
        opacity: 0.8;
        font-weight: 300;
        font-size: 0.95rem;
    }

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
    .btn-print-header.excel:hover { background: rgba(72,187,120,1); }

    .btn-print-header.pdf {
        background: rgba(229,62,62,0.85);
        border-color: rgba(229,62,62,0.4);
    }
    .btn-print-header.pdf:hover { background: rgba(229,62,62,1); }

    .btn-tambah-header {
        background: #ffffff !important;
        color: #1a1a2e !important;
        border: none !important;
        border-radius: 10px;
        padding: 0.7rem 1.5rem;
        font-weight: 600 !important;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .btn-tambah-header:hover {
        background: #f1f5f9 !important;
        color: #1a1a2e !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }
    .btn-tambah-header i {
        color: #1a1a2e !important;
        margin-right: 0 !important;
    }

    /* Style Stats Card */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 992px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .stats-row { grid-template-columns: 1fr; } }

    .stat-card {
        background: white;
        border-radius: 14px;
        padding: 1.3rem 1.5rem;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .stat-icon.total { background: #ebf8ff; color: #2b6cb0; }
    .stat-icon.menipis { background: #fed7d7; color: #822727; }
    .stat-icon.aman { background: #c6f6d5; color: #22543d; }

    .stat-info h3 { margin: 0; font-size: 1.6rem; font-weight: 700; color: #1a1a2e; line-height: 1; }
    .stat-info p { margin: 0.2rem 0 0 0; color: #718096; font-size: 0.85rem; font-weight: 500; }

    /* Style Filter Card */
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem 2rem;
        border: 1px solid #eef2f7;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 1.5rem;
    }
    .filter-card h5 {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1a1a2e;
        margin-bottom: 1rem;
    }
    .filter-card h5 i { color: #2b6cb0; margin-right: 6px; }
    .filter-card label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.3rem;
    }
    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 0.9rem;
        padding: 0.5rem 0.9rem;
    }
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.55rem 1.5rem;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s;
        width: 100%;
    }
    .btn-filter:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }

    .btn-reset {
        background: #edf2f7;
        color: #4a5568;
        border: none;
        border-radius: 10px;
        padding: 0.55rem 1rem;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-reset:hover { background: #e2e8f0; color: #2d3748; }

    /* Style Tabel */
    .card-custom {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 20px rgba(0,0,0,0.06);
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
    .card-custom .card-header h3 i { color: #2b6cb0; margin-right: 6px; }

    .table-responsive-custom { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .table-custom { min-width: 1000px; margin-bottom: 0; }
    .table-custom thead th {
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eef2f7;
        color: #4a5568;
        background: #f8fafc;
        white-space: nowrap;
    }
    .table-custom tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f4f8;
        color: #2d3748;
        font-size: 0.9rem;
    }
    .table-custom tbody tr:hover { background: #f8fafc; }
    .table-custom tbody tr:last-child td { border-bottom: none; }

    .badge-custom {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.8rem;
        background: #edf2f7;
        color: #4a5568;
    }
    
    .badge-stok {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .badge-stok.bg-success { background: #c6f6d5; color: #22543d; }
    .badge-stok.bg-warning { background: #fefcbf; color: #744210; }
    .badge-stok.bg-danger { background: #fed7d7; color: #9b2c2c; }

    .img-thumbnail-custom {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eef2f7;
    }

    .btn-action {
        border-radius: 8px;
        padding: 0.35rem 0.7rem;
        font-weight: 500;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        margin: 0 2px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        color: inherit;
    }
    .btn-edit-action { background: #ebf8ff; color: #2b6cb0; }
    .btn-edit-action:hover { background: #bee3f8; color: #2b6cb0; }
    .btn-delete-action { background: #fff5f5; color: #c53030; }
    .btn-delete-action:hover { background: #fed7d7; color: #c53030; }

    .card-footer-custom {
        background: #fafbfc;
        padding: 1rem 2rem;
        border-top: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .info-data { color: #718096; font-size: 0.9rem; }
    .info-data strong { color: #2d3748; }

    .dataTables_wrapper .dataTables_length select { border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.3rem 1.5rem 0.3rem 0.8rem; }
    .dataTables_wrapper .dataTables_filter input { border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.4rem 0.8rem; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius: 8px !important; border: none !important; padding: 0.4rem 0.8rem !important; margin: 0 2px !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #1a1a2e !important; color: white !important; border: none !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #edf2f7 !important; border: none !important; }
</style>

<section class="content">
    <div class="row">
        <div class="col-12">
            
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-boxes mr-3"></i> Stok Onderdil</h2>
                    <p>Kelola dan pantau ketersediaan sparepart di gudang</p>
                </div>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <a href="{{ route('supervisor.stok-onderdil.excel', request()->query()) }}" class="btn-print-header excel">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('supervisor.stok-onderdil.pdf', request()->query()) }}" class="btn-print-header pdf">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <div class="ms-2" style="border-left: 1px solid rgba(255,255,255,0.3); padding-left: 1rem;">
                        @include('supervisor.stok-onderdil.create')
                    </div>
                </div>
            </div>

            <!-- Statistik Ringkas -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon total"><i class="fas fa-box-open"></i></div>
                    <div class="stat-info">
                        <h3>{{ $stokOnderdil->count() }}</h3>
                        <p>Total Sparepart</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon menipis"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-info">
                        <h3>{{ $stokOnderdil->where('stok_terbaru', '<', 10)->count() }}</h3>
                        <p>Stok Menipis (< 10)</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon aman"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3>{{ $stokOnderdil->where('stok_terbaru', '>=', 10)->count() }}</h3>
                        <p>Stok Aman (>= 10)</p>
                    </div>
                </div>
            </div>

            <!-- Box Filter -->
            <div class="filter-card">
                <h5><i class="fas fa-filter"></i> Filter Data</h5>
                <form action="{{ route('supervisor.stok-onderdil.index') }}" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="search">Pencarian Nama</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Ketik nama sparepart..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="kondisi_stok">Kondisi Stok</label>
                            <select name="kondisi_stok" id="kondisi_stok" class="form-select">
                                <option value="">Semua Kondisi</option>
                                <option value="menipis" {{ request('kondisi_stok') == 'menipis' ? 'selected' : '' }}>Stok Menipis (< 10)</option>
                                <option value="aman" {{ request('kondisi_stok') == 'aman' ? 'selected' : '' }}>Stok Aman (>= 10)</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn-filter">
                                <i class="fas fa-search"></i> Cari Data
                            </button>
                            @if(request('search') || request('kondisi_stok'))
                                <a href="{{ route('supervisor.stok-onderdil.index') }}" class="btn-reset" title="Reset Filter">
                                    <i class="fas fa-redo"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Data -->
            <div class="card card-custom">
                <div class="card-header">
                    <h3><i class="fas fa-list"></i> Data Stok Onderdil</h3>
                </div>

                @if (session('sukses'))
                    <div class="alert alert-custom" style="border-radius: 12px; border: none; padding: 1rem 1.5rem; margin: 1.5rem 2rem; background: #f0fff4; color: #22543d; font-weight: 500; border-left: 4px solid #48bb78;">
                        <i class="fas fa-check-circle" style="margin-right: 10px; color: #48bb78;"></i> {{ session('sukses') }}
                    </div>
                @endif

                <div class="card-body p-4">
                    <div class="table-responsive-custom">
                        <table id="example1" class="table table-custom">
                            <thead>
                                <tr>
                                    <th width="4%">No</th>
                                    <th width="8%">Gambar</th>
                                    <th width="15%">Nama Sparepart</th>
                                    <th width="10%">Stok Sebelum</th>
                                    <th width="10%">Periode</th>
                                    <th width="12%">Kondisi Sekarang</th>
                                    <th width="8%">Pemakaian</th>
                                    <th width="10%">Stok Terbaru</th>
                                    <th width="15%">Keterangan</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stokOnderdil as $item)
                                <tr>
                                    <td><span class="badge-custom">{{ $loop->iteration }}</span></td>
                                    <td>
                                        @if($item->gambar)
                                            <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama_sparepart }}" class="img-thumbnail-custom">
                                        @else
                                            <div class="img-thumbnail-custom d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $item->nama_sparepart }}</strong></td>
                                    <td>{{ number_format($item->stok_periode_sebelum) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->periode)->format('F Y') }}</td>
                                    <td>{{ ($item->kondisi_sekarang) }}</td>
                                    <td>{{ number_format($item->pemakaian) }}</td>
                                    <td>
                                        @php
                                            $stokTerbaru = $item->stok_terbaru;
                                            $badgeClass = $stokTerbaru > 10 ? 'bg-success' : ($stokTerbaru > 5 ? 'bg-warning' : 'bg-danger');
                                        @endphp
                                        <span class="badge-stok {{ $badgeClass }}">
                                            {{ number_format($stokTerbaru) }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($item->keterangan, 40) ?? '-' }}</td>
                                    <td>
                                        @include('supervisor.stok-onderdil.edit', ['stokOnderdil' => $item])
                                        @include('supervisor.stok-onderdil.delete', ['stokOnderdil' => $item])
                                    </td>                                
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted d-block mb-2"></i>
                                        <span class="text-muted">Belum ada data stok onderdil</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer-custom">
                    <div class="info-data">
                        Menampilkan <strong>{{ $stokOnderdil->count() }}</strong> data stok onderdil
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
          "pageLength": 10,
          "language": {
              "search": "Cari:",
              "lengthMenu": "Tampilkan _MENU_ data",
              "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
              "infoEmpty": "Tidak ada data",
              "infoFiltered": "(difilter dari _MAX_ total data)",
              "zeroRecords": "Data tidak ditemukan",
              "paginate": {
                  "first": "Pertama",
                  "last": "Terakhir",
                  "next": "→",
                  "previous": "←"
              }
          }
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>
@endsection