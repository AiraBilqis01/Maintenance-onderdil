@extends('layouts.admin.app')

@section('title', 'Arsip Jadwal Pemeliharaan')

@section('content')
<section class="content">
    <div class="card">
        <div class="card-header">
            <h4>Arsip Jadwal Pemeliharaan</h4>
        </div>
        <div class="card-body">
            @if(session('sukses'))
                <div class="alert alert-success">
                    {{ session('sukses') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Tenaga Kerja</th>
                            <th>Status</th>
                            <th>Pemakaian Onderdil</th>
                            <th>Tanggal Arsip</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($arsip as $item)
                            <tr>
                                <td>{{ $item->judul_pemeliharaan }}</td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>{{ ucfirst($item->status) }}</td>
                                <td>
                                    @forelse($item->pemakaianOnderdil as $pemakaian)
                                        <div>
                                            {{ $pemakaian->nama_sparepart_snapshot }}
                                            ({{ $pemakaian->jumlah }} unit)
                                        </div>
                                    @empty
                                        -
                                    @endforelse
                                </td>
                                <td>{{ $item->deleted_at->format('d/m/Y H:i') }} </td>
                                <td>
                                    <form action="{{ route('supervisor.jadwal-pemeliharaan.restore', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-success btn-sm" type="submit">Pulihkan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada jadwal yang diarsipkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection