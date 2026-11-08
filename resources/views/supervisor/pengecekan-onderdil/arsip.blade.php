@extends('layouts.admin.app')

@section('title', 'Arsip Pengecekan Sparepart')

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-archive mr-2"></i> Arsip Pengecekan Sparepart</h4>
        </div>

        <div class="card-body">
            <a href="{{ route('supervisor.pengecekan-onderdil.index') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Pengecekan</a>
            <a href="{{ route('supervisor.pengecekan-onderdil.arsip.pdf') }}" class="btn btn-danger mb-3"><i class="fas fa-file-pdf mr-1"></i> Export PDF Arsip</a>
            <a href="{{ route('supervisor.pengecekan-onderdil.arsip.excel') }}" class="btn btn-success mb-3"><i class="fas fa-file-excel mr-1"></i> Export Excel Arsip</a>

            @if(session('sukses'))
                <div class="alert alert-success">{{ session('sukses') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Pengecekan</th>
                            <th>Tanggal Pengecekan</th>
                            <th>Tenaga Kerja</th>
                            <th>Status</th>
                            <th>Tanggal Arsip</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($arsip as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->judul_pengecekan }}</td>
                                <td>{{ $item->tanggal_pengecekan->format('d/m/Y') }}</td>
                                <td>{{ $item->tenagaKerja->name ?? '-' }}</td>
                                <td><span class="badge badge-success">{{ ucfirst($item->status) }}</span></td>
                                <td>{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @include('supervisor.pengecekan-onderdil.detail', ['jadwal' => $item])
                                    <form action="{{ route('supervisor.pengecekan-onderdil.restore', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Pulihkan jadwal ini tanpa mengubah stok?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-undo mr-1"></i> Pulihkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">Belum ada jadwal pengecekan yang diarsipkan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection