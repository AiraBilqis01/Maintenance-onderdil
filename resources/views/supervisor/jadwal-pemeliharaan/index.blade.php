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
    
    .page-header .btn-tambah-header {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 10px;
        padding: 0.7rem 1.8rem;
        font-weight: 500;
        color: white;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        backdrop-filter: blur(10px);
    }
    
    .page-header .btn-tambah-header:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        color: white;
    }
    
    .page-header .btn-tambah-header i {
        margin-right: 8px;
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
        min-width: 1200px;
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
    
    .info-data {
        color: #718096;
        font-size: 0.9rem;
    }
    
    .info-data strong {
        color: #2d3748;
    }
    
    .btn-action {
        border-radius: 8px;
        padding: 0.35rem 1rem;
        font-weight: 500;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        margin: 0 2px;
        border: none;
    }
    
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-edit-action {
        background: #ebf8ff;
        color: #2b6cb0;
    }
    
    .btn-edit-action:hover {
        background: #bee3f8;
        color: #2b6cb0;
    }
    
    .btn-delete-action {
        background: #fff5f5;
        color: #c53030;
    }
    
    .btn-delete-action:hover {
        background: #fed7d7;
        color: #c53030;
    }
    
    .badge-info {
        background: #ebf8ff;
        color: #2b6cb0;
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.8rem;
    }
    
    .badge-success {
        background: #c6f6d5;
        color: #22543d;
        padding: 0.3rem 0.8rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.8rem;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.3rem 1.5rem 0.3rem 0.8rem;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.4rem 0.8rem;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        border: none !important;
        padding: 0.4rem 0.8rem !important;
        margin: 0 2px !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #1a1a2e !important;
        color: white !important;
        border: none !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #edf2f7 !important;
        border: none !important;
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
                <div>
                    @include('supervisor.jadwal-pemeliharaan.create')
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
                
                <div class="card-body p-4">
                    <div class="table-responsive-custom">
                        <table id="example1" class="table table-custom">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="12%">Judul Pemeliharaan</th>
                                    <th width="10%">Tanggal Selesai</th>
                                    <th width="12%">Tenaga Kerja</th>
                                    <th width="12%">Nama Peralatan</th>
                                    <th width="8%">Lokasi</th>
                                    <th width="5%">Qty</th>
                                    <th width="10%">Serial Number</th>
                                    <th width="8%">Kapasitas</th>
                                    <th width="8%">Merek</th>
                                    <th width="8%">Tipe</th>
                                    <th width="8%">Tahun</th>
                                    <th width="8%">Gambar</th>
                                    <th width="10%">Keterangan</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwalPemeliharaan as $item)
                                <tr>
                                    <td><span class="badge-custom">{{ $loop->iteration }}</span></td>
                                    <td><strong>{{ $item->judul_pemeliharaan }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</td>
                                    <td>
                                        <strong>{{ $item->user ? $item->user->name : '-' }}</strong>
                                    </td>
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
                                    <td>{{ $item->keterangan ?? '-' }}</td>
                                    <td>
                                        @include('supervisor.jadwal-pemeliharaan.edit', ['jadwalPemeliharaan' => $item])
                                        @include('supervisor.jadwal-pemeliharaan.delete', ['jadwalPemeliharaan' => $item])
                                    </td>                                
                                </tr>
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