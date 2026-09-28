<!-- Tombol Edit -->
<button type="button" class="btn btn-edit-action" data-toggle="modal" data-target="#modalEditTenagaKerja{{ $tenagaKerja->id }}">
    <i class="fas fa-edit"></i> Edit
</button>

<!-- Modal Edit Tenaga Kerja -->
<div class="modal fade" id="modalEditTenagaKerja{{ $tenagaKerja->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditTenagaKerjaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('supervisor.kelola-tenagakerja.update', $tenagaKerja->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content" style="background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="modalEditTenagaKerjaLabel">
                        <i class="fas fa-edit mr-2"></i> Edit Tenaga Kerja
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: white;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Tenaga Kerja <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ $tenagaKerja->name }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_telepon">No Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="no_telepon" value="{{ $tenagaKerja->no_telepon }}" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Tenaga Kerja <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ $tenagaKerja->email }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Ubah Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah" />
                                <small class="text-muted" style="color: #ccc !important;">Kosongkan jika tidak ingin mengubah password</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>