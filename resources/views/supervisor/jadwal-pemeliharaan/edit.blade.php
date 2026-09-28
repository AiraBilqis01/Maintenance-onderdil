<!-- Tombol Edit -->
<button type="button" class="btn btn-edit-action" data-toggle="modal" data-target="#modalEditJadwalPemeliharaan{{ $jadwalPemeliharaan->id }}">
    <i class="fas fa-edit"></i> Edit
</button>

<!-- Modal Edit Jadwal Pemeliharaan -->
<div class="modal fade" id="modalEditJadwalPemeliharaan{{ $jadwalPemeliharaan->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditJadwalPemeliharaanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('supervisor.jadwal-pemeliharaan.update', $jadwalPemeliharaan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content" style="background: linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="modalEditJadwalPemeliharaanLabel">
                        <i class="fas fa-edit mr-2"></i> Edit Jadwal Pemeliharaan
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="color: white;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_selesai" value="{{ $jadwalPemeliharaan->tanggal_selesai }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_tenaga_kerja">Nama Tenaga Kerja <span class="text-danger">*</span></label>
                                <select name="nama_tenaga_kerja" class="form-control" required>
                                    <option value="">Pilih Tenaga Kerja</option>
                                    @foreach($tenagaKerja as $tk)
                                        <option value="{{ $tk->id }}" {{ $jadwalPemeliharaan->nama_tenaga_kerja == $tk->id ? 'selected' : '' }}>
                                            {{ $tk->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_peralatan">Nama Peralatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_peralatan" value="{{ $jadwalPemeliharaan->nama_peralatan }}" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lokasi">Lokasi</label>
                                <input type="text" class="form-control" name="lokasi" value="{{ $jadwalPemeliharaan->lokasi }}" placeholder="Masukkan lokasi" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="quantity" value="{{ $jadwalPemeliharaan->quantity }}" min="1" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serial_number">Serial Number</label>
                                <input type="text" class="form-control" name="serial_number" value="{{ $jadwalPemeliharaan->serial_number }}" placeholder="Masukkan serial number" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kapasitas">Kapasitas</label>
                                <input type="text" class="form-control" name="kapasitas" value="{{ $jadwalPemeliharaan->kapasitas }}" placeholder="Masukkan kapasitas" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="merek">Merek</label>
                                <input type="text" class="form-control" name="merek" value="{{ $jadwalPemeliharaan->merek }}" placeholder="Masukkan merek" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipe">Tipe</label>
                                <input type="text" class="form-control" name="tipe" value="{{ $jadwalPemeliharaan->tipe }}" placeholder="Masukkan tipe" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tahun_pembuatan">Tahun Pembuatan</label>
                                <input type="number" class="form-control" name="tahun_pembuatan" value="{{ $jadwalPemeliharaan->tahun_pembuatan }}" placeholder="2024" min="1900" max="{{ date('Y') }}" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                @if($jadwalPemeliharaan->gambar)
                                    <div class="mb-2">
                                        <img src="{{ asset($jadwalPemeliharaan->gambar) }}" alt="{{ $jadwalPemeliharaan->nama_peralatan }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="gambar" accept="image/*" />
                                <small class="text-muted" style="color: #ccc !important;">Kosongkan jika tidak ingin mengubah gambar</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" name="keterangan" rows="2" placeholder="Masukkan keterangan (opsional)">{{ $jadwalPemeliharaan->keterangan }}</textarea>
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