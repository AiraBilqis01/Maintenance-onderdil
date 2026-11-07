@php
    $isArsip = $jadwalPemeliharaan->status === 'selesai';
@endphp

<!-- Tombol Hapus / Arsipkan -->
<button type="button" class="btn btn-action btn-delete-action" data-toggle="modal" data-target="#modalDeleteJadwalPemeliharaan{{ $jadwalPemeliharaan->id }}">
    <i class="fas {{ $isArsip ? 'fa-archive' : 'fa-trash' }}"></i> {{ $isArsip ? 'Arsipkan' : 'Hapus' }}
</button>

<!-- Modal Hapus / Arsipkan Jadwal Pemeliharaan -->
<div class="modal fade" id="modalDeleteJadwalPemeliharaan{{ $jadwalPemeliharaan->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);">
                <h5 class="modal-title text-white"><i class="fas fa-exclamation-triangle mr-2"></i> {{ $isArsip ? 'Arsipkan Jadwal Pemeliharaan' : 'Hapus Jadwal Pemeliharaan' }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas {{ $isArsip ? 'fa-archive' : 'fa-trash-alt' }} text-danger" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p class="mb-0" style="font-size: 1.1rem;">Apakah Anda yakin ingin {{ $isArsip ? 'mengarsipkan' : 'menghapus' }} jadwal pemeliharaan<br><strong>{{ $jadwalPemeliharaan->nama_peralatan }}</strong>?</p>
                @if($isArsip)
                    <p class="text-muted mt-2" style="font-size: 0.9rem;">Jadwal akan dipindahkan ke arsip dan dapat dipulihkan kembali. Riwayat pemakaian onderdil tetap tersimpan dan stok tidak berubah.</p>
                @else
                    <p class="text-danger mt-2" style="font-size: 0.9rem;">Data akan dihapus secara permanen dan tidak dapat dikembalikan!</p>
                @endif
            </div>
            <div class="modal-footer">
                <form action="{{ route('supervisor.jadwal-pemeliharaan.destroy', $jadwalPemeliharaan->id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="fas {{ $isArsip ? 'fa-archive' : 'fa-trash' }} mr-1"></i> {{ $isArsip ? 'Arsipkan' : 'Hapus' }}</button>
                </form>
            </div>
        </div>
    </div>
</div>