<button type="button" class="btn btn-action btn-detail-action" data-toggle="modal" data-target="#modalDetailJadwal{{ $jadwalPemeliharaan->id }}">
    <i class="fas fa-eye"></i> Detail
</button>

<div class="modal fade" id="modalDetailJadwal{{ $jadwalPemeliharaan->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle mr-2"></i> Detail Jadwal Pemeliharaan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: white;">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <h6 class="detail-section-title dark">
                    <i class="fas fa-clipboard-list mr-2"></i> Informasi Jadwal
                </h6>
                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-label">Judul Pemeliharaan</div>
                        <div class="detail-value"><strong>{{ $jadwalPemeliharaan->judul_pemeliharaan }}</strong></div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Tenaga Kerja</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->user ? $jadwalPemeliharaan->user->name : '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            @if($jadwalPemeliharaan->status == 'selesai')
                                <span class="badge-selesai"><i class="fas fa-check-circle"></i> Selesai</span>
                            @elseif($jadwalPemeliharaan->status == 'ditolak')
                                <span class="badge-ditolak"><i class="fas fa-times-circle"></i> Ditolak</span>
                            @else
                                <span class="badge-pending"><i class="fas fa-clock"></i> Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Tanggal Selesai</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($jadwalPemeliharaan->tanggal_selesai)->format('d/m/Y') }}</div>
                    </div>
                </div>

                <h6 class="detail-section-title dark mt-4">
                    <i class="fas fa-tools mr-2"></i> Detail Peralatan
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">Nama Peralatan</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->nama_peralatan }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Lokasi</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->lokasi ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Quantity</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->quantity }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Serial Number</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->serial_number ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Kapasitas</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->kapasitas ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Merek</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->merek ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Tipe</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->tipe ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Tahun Pembuatan</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->tahun_pembuatan ?? '-' }}</div>
                    </div>
                    <div class="col-md-12">
                        <div class="detail-label">Keterangan</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->keterangan ?? '-' }}</div>
                    </div>
                    @if($jadwalPemeliharaan->gambar)
                    <div class="col-md-12">
                        <div class="detail-label">Gambar</div>
                        <div class="detail-value">
                            <img src="{{ asset($jadwalPemeliharaan->gambar) }}" alt="Gambar" style="max-width: 200px; border-radius: 12px;">
                        </div>
                    </div>
                    @endif
                </div>

                @if($jadwalPemeliharaan->status == 'selesai')
                <h6 class="detail-section-title green mt-4">
                    <i class="fas fa-check-circle mr-2"></i> Detail Penyelesaian
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-label">Nama Onderdil</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->nama_onderdil ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Tanggal Selesai</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($jadwalPemeliharaan->tanggal_selesai)->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-md-12">
                        <div class="detail-label">Detail Penyelesaian</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->detail_penyelesaian ?? '-' }}</div>
                    </div>
                    @if($jadwalPemeliharaan->gambar_bukti)
                    <div class="col-md-12">
                        <div class="detail-label">Gambar Bukti Penyelesaian</div>
                        <div class="detail-value">
                            <img src="{{ asset($jadwalPemeliharaan->gambar_bukti) }}" alt="Bukti" style="max-width: 200px; border-radius: 12px;">
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                @if($jadwalPemeliharaan->status == 'ditolak')
                <h6 class="detail-section-title red mt-4">
                    <i class="fas fa-times-circle mr-2"></i> Detail Penolakan
                </h6>
                <div class="row">
                    <div class="col-md-12">
                        <div class="detail-label">Keterangan</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->keterangan_tolak ?? '-' }}</div>
                    </div>
                    <div class="col-md-12">
                        <div class="detail-label">Alasan Penolakan</div>
                        <div class="detail-value">{{ $jadwalPemeliharaan->alasan_penolakan ?? '-' }}</div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>