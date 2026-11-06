
<!-- Tombol Delete -->
<button type="button" class="btn btn-action btn-delete-action" data-toggle="modal" data-target="#modalHapusPengecekan{{ $jadwal->id }}">
    <i class="fas fa-trash"></i> Hapus
</button>

<!-- Modal Delete Jadwal Pengecekan -->
<div class="modal fade" id="modalHapusPengecekan{{ $jadwal->id }}" tabindex="-1" role="dialog" aria-labelledby="modalHapusPengecekanLabel{{ $jadwal->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header" style="background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);">
                <h5 class="modal-title text-white" id="modalHapusPengecekanLabel{{ $jadwal->id }}">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Hapus Jadwal Pengecekan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body text-center py-4">

                {{-- IKON HAPUS --}}
                <i class="fas fa-trash-alt text-danger" style="font-size: 3rem; margin-bottom: 1rem;"></i>

                {{-- KONFIRMASI --}}
                <p class="mb-3" style="font-size: 1.1rem;">
                    Apakah Anda yakin ingin menghapus jadwal pengecekan<br>
                    <strong>{{ $jadwal->judul_pengecekan }}</strong>?
                </p>

                {{-- INFORMASI JADWAL --}}
                <div class="alert alert-light border text-left">
                    <div class="mb-2">
                        <strong>Judul Pengecekan:</strong><br>
                        {{ $jadwal->judul_pengecekan }}
                    </div>
                    <div class="mb-2">
                        <strong>Tanggal Pengecekan:</strong><br>
                        {{ \Carbon\Carbon::parse($jadwal->tanggal_pengecekan)->format('d/m/Y') }}
                    </div>
                    <div class="mb-2">
                        <strong>Tenaga Kerja:</strong><br>
                        {{ $jadwal->tenagaKerja->name ?? '-' }}
                    </div>
                    <div>
                        <strong>Status:</strong><br>
                        @if($jadwal->status == 'pending')
                            Pending
                        @elseif($jadwal->status == 'menunggu_verifikasi')
                            Menunggu Verifikasi
                        @elseif($jadwal->status == 'selesai')
                            Selesai
                        @elseif($jadwal->status == 'ditolak')
                            Ditolak
                        @else
                            {{ ucfirst($jadwal->status) }}
                        @endif
                    </div>
                </div>

                {{-- DAFTAR SPAREPART --}}
                <div class="text-left mb-3">
                    <strong>Sparepart yang terdapat dalam jadwal:</strong>
                    <ul class="mt-2 mb-0 pl-4">
                        @forelse($jadwal->details as $detail)
                            <li>{{ $detail->nama_sparepart_snapshot }}</li>
                        @empty
                            <li class="text-muted">Tidak ada detail sparepart.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- PERINGATAN --}}
                <div class="alert alert-warning text-left mb-3">
                    <i class="fas fa-info-circle mr-1"></i> Data jadwal dan detail pengecekan sparepart yang terhubung akan ikut terhapus. Data utama pada Manajemen Stok tidak akan ikut terhapus.
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size: 0.9rem;">Data yang dihapus tidak dapat dikembalikan!</p>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <form action="{{ route('supervisor.pengecekan-onderdil.destroy', $jadwal->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
