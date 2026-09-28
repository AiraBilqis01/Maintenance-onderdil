<button type="button" class="btn btn-tambah-header" data-toggle="modal" data-target="#modalTambahTenagaKerja">
    <i class="fas fa-plus"></i> Tambah Tenaga Kerja
</button>

<div class="modal fade" id="modalTambahTenagaKerja" tabindex="-1" role="dialog" aria-labelledby="modalTambahTenagaKerjaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('supervisor.kelola-tenagakerja.store') }}" method="POST">
            @csrf
            <div class="modal-content" style="background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahTenagaKerjaLabel" style="color: white">
                        <i class="fas fa-plus-circle mr-2"></i> Tambah Tenaga Kerja
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: white;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Masukkan nama" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_telepon">No Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="no_telepon" class="form-control" placeholder="Masukkan nomor telepon" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password" required>
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