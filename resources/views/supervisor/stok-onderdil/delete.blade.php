<!-- Tombol Delete -->
<button type="button" class="btn btn-delete-action" data-toggle="modal" data-target="#modalDeleteStokOnderdil{{ $stokOnderdil->id }}">
    <i class="fas fa-trash"></i> Hapus
</button>

<!-- Modal Delete Stok Onderdil -->
<div class="modal fade" id="modalDeleteStokOnderdil{{ $stokOnderdil->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Hapus Stok Onderdil
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-trash-alt text-danger" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p class="mb-0" style="font-size: 1.1rem;">
                    Apakah Anda yakin ingin menghapus stok onderdil<br>
                    <strong>{{ $stokOnderdil->nama_sparepart }}</strong>?
                </p>
                <p class="text-muted mt-2" style="font-size: 0.9rem;">Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('supervisor.stok-onderdil.destroy', $stokOnderdil->id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>