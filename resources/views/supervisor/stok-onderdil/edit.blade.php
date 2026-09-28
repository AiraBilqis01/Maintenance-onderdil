<!-- Tombol Edit -->
<button type="button" class="btn btn-edit-action" data-toggle="modal" data-target="#modalEditStokOnderdil{{ $stokOnderdil->id }}">
    <i class="fas fa-edit"></i> Edit
</button>

<!-- Modal Edit Stok Onderdil -->
<div class="modal fade" id="modalEditStokOnderdil{{ $stokOnderdil->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditStokOnderdilLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('supervisor.stok-onderdil.update', $stokOnderdil->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content" style="background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="modalEditStokOnderdilLabel">
                        <i class="fas fa-edit mr-2"></i> Edit Stok Onderdil
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: white;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_sparepart">Nama Sparepart <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_sparepart" value="{{ $stokOnderdil->nama_sparepart }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                @if($stokOnderdil->gambar)
                                    <div class="mb-2">
                                        <img src="{{ asset($stokOnderdil->gambar) }}" alt="{{ $stokOnderdil->nama_sparepart }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="gambar" accept="image/*" />
                                <small class="text-muted" style="color: #ccc !important;">Kosongkan jika tidak ingin mengubah gambar</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stok_periode_sebelum">Stok Periode Sebelum <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stok_periode_sebelum" value="{{ $stokOnderdil->stok_periode_sebelum }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="periode">Periode <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="periode" value="{{ $stokOnderdil->periode }}" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kondisi_sekarang">Kondisi Sekarang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kondisi_sekarang" value="{{ $stokOnderdil->kondisi_sekarang }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pemakaian">Pemakaian <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="pemakaian" value="{{ $stokOnderdil->pemakaian }}" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stok_terbaru">Stok Terbaru <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="stok_terbaru" value="{{ $stokOnderdil->stok_terbaru }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" name="keterangan" rows="2" placeholder="Masukkan keterangan (opsional)">{{ $stokOnderdil->keterangan }}</textarea>
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