@extends('layouts.admin.app')

@section('title', 'Jadwal Pemeliharaan')

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

    /* Tombol Tambah dipaksa putih agar kontras */
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
    .table-responsive-custom {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table-custom {
        min-width: 1800px;
        margin-bottom: 0;
    }
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
    }
    .table-custom tbody tr:hover {
        background: #f8fafc;
    }
    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }
    .badge-custom {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.8rem;
        background: #edf2f7;
        color: #4a5568;
    }
    .img-thumbnail-custom {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eef2f7;
    }
    .badge-info { background: #ebf8ff; color: #2b6cb0; padding: 0.3rem 0.8rem; border-radius: 8px; font-weight: 500; font-size: 0.8rem; display: inline-block; }
    .badge-success { background: #c6f6d5; color: #22543d; padding: 0.3rem 0.8rem; border-radius: 8px; font-weight: 500; font-size: 0.8rem; display: inline-block; }
    .badge-pending { background: #fefcbf; color: #744210; padding: 0.3rem 0.8rem; border-radius: 8px; font-weight: 500; font-size: 0.8rem; display: inline-block; }
    .badge-selesai { background: #c6f6d5; color: #22543d; padding: 0.3rem 0.8rem; border-radius: 8px; font-weight: 500; font-size: 0.8rem; display: inline-block; }
    .badge-ditolak { background: #fed7d7; color: #822727; padding: 0.3rem 0.8rem; border-radius: 8px; font-weight: 500; font-size: 0.8rem; display: inline-block; }
    .badge-menunggu { background: #e0f2fe; color: #0284c7; padding: 0.3rem 0.8rem; border-radius: 8px; font-weight: 500; font-size: 0.8rem; display: inline-block; border: 1px solid #bae6fd; }
    
    .btn-action {
        border-radius: 8px;
        padding: 0.35rem 0.8rem;
        font-weight: 500;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        white-space: nowrap;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .btn-detail-action { background: #faf5ff; color: #6b46c1; }
    .btn-detail-action:hover { background: #e9d8fd; color: #6b46c1; }
    .btn-edit-action { background: #ebf8ff; color: #2b6cb0; }
    .btn-edit-action:hover { background: #bee3f8; color: #2b6cb0; }
    .btn-delete-action { background: #fff5f5; color: #c53030; }
    .btn-delete-action:hover { background: #fed7d7; color: #c53030; }
    .btn-verifikasi-action { background: #c6f6d5; color: #22543d; }
    .btn-verifikasi-action:hover { background: #9ae6b4; color: #22543d; }
    .btn-tolak-action { background: #fed7d7; color: #822727; }
    .btn-tolak-action:hover { background: #feb2b2; color: #822727; }
    
    .alert-custom {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.5rem;
        margin: 1.5rem 2rem;
        background: #f0fff4;
        color: #22543d;
        font-weight: 500;
        border-left: 4px solid #48bb78;
    }
    .alert-custom i {
        margin-right: 10px;
        color: #48bb78;
    }
    .card-footer-custom {
        background: #fafbfc;
        padding: 1rem 2rem;
        border-top: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .info-data { color: #718096; font-size: 0.9rem; }
    .info-data strong { color: #2d3748; }
    .dataTables_wrapper .dataTables_length select { border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.3rem 1.5rem 0.3rem 0.8rem; }
    .dataTables_wrapper .dataTables_filter input { border-radius: 8px; border: 1px solid #e2e8f0; padding: 0.4rem 0.8rem; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius: 8px !important; border: none !important; padding: 0.4rem 0.8rem !important; margin: 0 2px !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #1a1a2e !important; color: white !important; border: none !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #edf2f7 !important; border: none !important; }
    
    .modal-tolak-header {
        background: linear-gradient(135deg, #fc5c7d, #e53e3e);
        color: white;
        border: none;
        padding: 1.5rem;
    }
    .modal-tolak-header h5 { font-weight: 600; margin: 0; }
    .modal-tolak-header .btn-close { filter: brightness(0) invert(1); opacity: 0.9; }
    .modal-tolak-body { padding: 2rem; background: #fafbff; }
    .modal-tolak-footer { background: #fafbff; border-top: 1px solid #eef2f7; padding: 1rem 2rem; }
    .alasan-box {
        background: #fff5f5;
        border-left: 4px solid #e53e3e;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        color: #822727;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-12">
            
            <div class="page-header">
                <div>
                    <h2><i class="fas fa-calendar-check mr-3"></i> Jadwal Pemeliharaan</h2>
                    <p>Kelola data jadwal pemeliharaan peralatan</p>
                </div>
                
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <a href="{{ route('supervisor.jadwal-pemeliharaan.excel', request()->query()) }}" class="btn-print-header excel">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('supervisor.jadwal-pemeliharaan.pdf', request()->query()) }}" class="btn-print-header pdf">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <div class="ms-2" style="border-left: 1px solid rgba(255,255,255,0.3); padding-left: 1rem;">
                        @include('supervisor.jadwal-pemeliharaan.create')
                    </div>
                </div>
            </div>
            
            <div class="card card-custom">
                <div class="card-header">
                    <h3><i class="fas fa-list mr-2"></i> Data Jadwal Pemeliharaan</h3>
                </div>
                
                @if (session('sukses'))
                    <div class="alert alert-custom">
                        <i class="fas fa-check-circle"></i> {{ session('sukses') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" style="border-radius: 12px; margin: 1.5rem 2rem;">
                        <i class="fas fa-exclamation-triangle"></i> {{ $errors->first() }}
                    </div>
                @endif
                
                <div class="card-body p-4">
                    <div class="table-responsive-custom">
                        <table id="example1" class="table table-custom">
                            <thead>
                                <tr>
                                    <th width="4%">No</th>
                                    <th width="10%">Judul Pemeliharaan</th>
                                    <th width="8%">Tanggal Selesai</th>
                                    <th width="10%">Tenaga Kerja</th>
                                    <th width="10%">Nama Peralatan</th>
                                    <th width="7%">Lokasi</th>
                                    <th width="4%">Qty</th>
                                    <th width="8%">Serial Number</th>
                                    <th width="7%">Kapasitas</th>
                                    <th width="7%">Merek</th>
                                    <th width="7%">Tipe</th>
                                    <th width="6%">Tahun</th>
                                    <th width="6%">Gambar</th>
                                    <th width="8%">Status</th>
                                    <th width="28%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwalPemeliharaan as $item)
                                <tr>
                                    <td><span class="badge-custom">{{ $loop->iteration }}</span></td>
                                    <td><strong>{{ $item->judul_pemeliharaan }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</td>
                                    <td><strong>{{ $item->user ? $item->user->name : '-' }}</strong></td>
                                    <td><strong>{{ $item->nama_peralatan }}</strong></td>
                                    <td><span class="badge-info">{{ $item->lokasi ?? '-' }}</span></td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td><span class="badge-custom">{{ $item->serial_number ?? '-' }}</span></td>
                                    <td>{{ $item->kapasitas ?? '-' }}</td>
                                    <td>{{ $item->merek ?? '-' }}</td>
                                    <td>{{ $item->tipe ?? '-' }}</td>
                                    <td>{{ $item->tahun_pembuatan ?? '-' }}</td>
                                    <td>
                                        @if($item->gambar)
                                            <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama_peralatan }}" class="img-thumbnail-custom">
                                        @else
                                            <div class="img-thumbnail-custom d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 'menunggu_verifikasi')
                                            <span class="badge-menunggu"><i class="fas fa-hourglass-half"></i> Menunggu</span>
                                        @elseif($item->status == 'selesai')
                                            <span class="badge-selesai"><i class="fas fa-check-circle"></i> Selesai</span>
                                        @elseif($item->status == 'ditolak')
                                            <span class="badge-ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>
                                        @else
                                            <span class="badge-pending"><i class="fas fa-clock"></i> Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-row flex-nowrap align-items-center">
                                            @include('supervisor.jadwal-pemeliharaan.detail', ['jadwalPemeliharaan' => $item])
                                            @include('supervisor.jadwal-pemeliharaan.edit', ['jadwalPemeliharaan' => $item])
                                            
                                            @if($item->status == 'pending')
                                                <button type="button" class="btn btn-action btn-tolak-action" data-toggle="modal" data-target="#modalTolakSPV{{ $item->id }}" title="Batalkan Jadwal">
                                                    <i class="fas fa-times-circle"></i> Tolak
                                                </button>
                                            @endif

                                            @if($item->status == 'menunggu_verifikasi')
                                                
                                                @if($item->detail_penyelesaian)
                                                    <form action="{{ route('supervisor.jadwal-pemeliharaan.verifikasi-selesai', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin memverifikasi? Stok akan otomatis dipotong.');">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-action btn-verifikasi-action" title="Verifikasi & Potong Stok">
                                                            <i class="fas fa-check-double"></i> ACC
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-action btn-tolak-action" data-toggle="modal" data-target="#modalTolakSPV{{ $item->id }}" title="Tolak Laporan">
                                                        <i class="fas fa-times-circle"></i> Tolak
                                                    </button>
                                                @endif

                                                @if($item->alasan_penolakan)
                                                    <button type="button" class="btn btn-action btn-tolak-action" data-toggle="modal" data-target="#modalKonfirmasiTolak{{ $item->id }}" title="Lihat Alasan & ACC Tolak">
                                                        <i class="fas fa-check"></i> ACC Tolak
                                                    </button>
                                                @endif

                                            @endif

                                            @include('supervisor.jadwal-pemeliharaan.delete', ['jadwalPemeliharaan' => $item])
                                        </div>
                                    </td>                                
                                </tr>

                                @if($item->status == 'pending' || ($item->status == 'menunggu_verifikasi' && $item->detail_penyelesaian))
                                <div class="modal fade" id="modalTolakSPV{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
                                            <form action="{{ route('supervisor.jadwal-pemeliharaan.tolak-jadwal', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header modal-tolak-header">
                                                    <h5><i class="fas fa-times-circle me-2"></i> Batalkan Jadwal</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body modal-tolak-body">
                                                    <p class="mb-3">Anda akan membatalkan jadwal <strong>{{ $item->judul_pemeliharaan }}</strong> untuk tenaga kerja <strong>{{ $item->user ? $item->user->name : '-' }}</strong>.</p>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Keterangan <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="keterangan_tolak" rows="3" placeholder="Contoh: Alat sudah rusak parah" required></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="alasan_penolakan" rows="3" placeholder="Jelaskan alasan pembatalan..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer modal-tolak-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-tolak-action">
                                                        <i class="fas fa-times-circle"></i> Ya, Tolak Jadwal
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if($item->alasan_penolakan)
                                <div class="modal fade" id="modalKonfirmasiTolak{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none;">
                                            <div class="modal-header modal-tolak-header">
                                                <h5><i class="fas fa-exclamation-triangle me-2"></i> Konfirmasi Penolakan</h5>
                                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body modal-tolak-body">
                                                <p class="mb-3">Tenaga Kerja mengajukan penolakan untuk pemeliharaan <strong>{{ $item->judul_pemeliharaan }}</strong> dengan alasan sebagai berikut:</p>
                                                
                                                <div class="alasan-box">
                                                    <i class="fas fa-quote-left me-2"></i>
                                                    {{ $item->alasan_penolakan }}
                                                    <i class="fas fa-quote-right ms-2"></i>
                                                </div>

                                                <p class="text-muted small mb-0">
                                                    <strong>Keterangan Tambahan:</strong> {{ $item->keterangan_tolak ?? '-' }}
                                                </p>
                                            </div>
                                            <div class="modal-footer modal-tolak-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <form action="{{ route('supervisor.jadwal-pemeliharaan.verifikasi-tolak', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-tolak-action">
                                                        <i class="fas fa-check"></i> Ya, Setujui Penolakan
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @empty
                                <tr>
                                    <td colspan="15" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted d-block mb-2"></i>
                                        <span class="text-muted">Belum ada data jadwal pemeliharaan</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer-custom">
                    <div class="info-data">
                        Menampilkan <strong>{{ $jadwalPemeliharaan->count() }}</strong> data jadwal pemeliharaan
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