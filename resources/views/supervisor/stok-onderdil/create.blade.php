<button type="button" class="btn btn-tambah-header" data-toggle="modal" data-target="#modalTambahStokOnderdil">
    <i class="fas fa-plus"></i> Tambah Stok Onderdil
</button>

<div class="modal fade" id="modalTambahStokOnderdil" tabindex="-1" role="dialog" aria-labelledby="modalTambahStokOnderdilLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('supervisor.stok-onderdil.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content" style="background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahStokOnderdilLabel" style="color: white">
                        <i class="fas fa-plus-circle mr-2"></i> Tambah Stok Onderdil
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: white;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_sparepart">Nama Sparepart <span class="text-danger">*</span></label>
                                <input type="text" name="nama_sparepart" class="form-control" placeholder="Masukkan nama sparepart" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stok_periode_sebelum">Stok Periode Sebelum <span class="text-danger">*</span></label>
                                <input type="number" name="stok_periode_sebelum" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="periode">Periode <span class="text-danger">*</span></label>
                                <input type="date" name="periode" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kondisi_sekarang">Kondisi Sekarang <span class="text-danger">*</span></label>
                                <input type="text" name="kondisi_sekarang" class="form-control" placeholder="Masukkan kondisi" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pemakaian">Pemakaian <span class="text-danger">*</span></label>
                                <input type="number" name="pemakaian" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stok_terbaru">Stok Terbaru <span class="text-danger">*</span></label>
                                <input type="number" name="stok_terbaru" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Masukkan keterangan (opsional)"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>