<button type="button" class="btn btn-tambah-header" data-toggle="modal" data-target="#modalTambahJadwalPemeliharaan">
    <i class="fas fa-plus"></i> Tambah Jadwal Pemeliharaan
</button>

<div class="modal fade" id="modalTambahJadwalPemeliharaan" tabindex="-1" role="dialog" aria-labelledby="modalTambahJadwalPemeliharaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('supervisor.jadwal-pemeliharaan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content" style="background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahJadwalPemeliharaanLabel" style="color: white">
                        <i class="fas fa-calendar-plus mr-2"></i> Tambah Jadwal Pemeliharaan
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: white;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_selesai" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_tenaga_kerja">Nama Tenaga Kerja <span class="text-danger">*</span></label>
                                <select name="nama_tenaga_kerja" class="form-control" required>
                                    <option value="">Pilih Tenaga Kerja</option>
                                    @foreach($tenagaKerja as $tk)
                                        <option value="{{ $tk->id }}">{{ $tk->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_peralatan">Nama Peralatan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_peralatan" class="form-control" placeholder="Masukkan nama peralatan" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control" placeholder="Masukkan lokasi (contoh: Ruangan Turbin)">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control" placeholder="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serial_number">Serial Number</label>
                                <input type="text" name="serial_number" class="form-control" placeholder="Masukkan serial number">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kapasitas">Kapasitas</label>
                                <input type="text" name="kapasitas" class="form-control" placeholder="Masukkan kapasitas">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="merek">Merek</label>
                                <input type="text" name="merek" class="form-control" placeholder="Masukkan merek">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipe">Tipe</label>
                                <input type="text" name="tipe" class="form-control" placeholder="Masukkan tipe">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tahun_pembuatan">Tahun Pembuatan</label>
                                <input type="number" name="tahun_pembuatan" class="form-control" placeholder="2024" min="1900" max="{{ date('Y') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*">
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