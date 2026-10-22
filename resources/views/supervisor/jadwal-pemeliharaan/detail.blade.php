<button type="button" class="btn btn-action btn-detail-action" data-toggle="modal" data-target="#modalDetailJadwal{{ $jadwalPemeliharaan->id }}">
    <i class="fas fa-eye"></i> Detail
</button>

<div class="modal fade" id="modalDetailJadwal{{ $jadwalPemeliharaan->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none;">
            
            <div class="modal-header" style="background: #1a1a2e; color: white; padding: 1.2rem 1.5rem; border-bottom: none;">
                <h5 class="modal-title" style="font-size: 1.1rem; font-weight: 600;">
                    <i class="fas fa-clipboard-check mr-2"></i> Detail Jadwal Pemeliharaan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding: 1.5rem; background: #ffffff;">
                
                <!-- SECTION 1: INFORMASI JADWAL -->
                <div class="detail-section mb-4">
                    <h6 class="detail-title"><i class="fas fa-info-circle text-primary mr-2"></i> Informasi Jadwal</h6>
                    <table class="table table-sm table-borderless detail-table">
                        <tr>
                            <td width="35%" class="text-muted">Judul Pemeliharaan</td>
                            <td width="65%" class="font-weight-bold text-dark">{{ $jadwalPemeliharaan->judul_pemeliharaan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tenaga Kerja</td>
                            <td>{{ $jadwalPemeliharaan->user ? $jadwalPemeliharaan->user->name : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>
                                @if($jadwalPemeliharaan->status == 'selesai')
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> Selesai</span>
                                @elseif($jadwalPemeliharaan->status == 'ditolak')
                                    <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle"></i> Ditolak</span>
                                @else
                                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock"></i> Pending</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Selesai</td>
                            <td>{{ \Carbon\Carbon::parse($jadwalPemeliharaan->tanggal_selesai)->format('d F Y') }}</td>
                        </tr>
                    </table>
                </div>

                <hr style="margin: 1.5rem 0; border-top: 1px dashed #e2e8f0;">

                <!-- SECTION 2: DETAIL PERALATAN -->
                <div class="detail-section mb-4">
                    <h6 class="detail-title"><i class="fas fa-tools text-primary mr-2"></i> Detail Peralatan</h6>
                    <table class="table table-sm table-borderless detail-table">
                        <tr>
                            <td width="35%" class="text-muted">Nama Peralatan</td>
                            <td width="65%" class="font-weight-bold">{{ $jadwalPemeliharaan->nama_peralatan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Lokasi</td>
                            <td>{{ $jadwalPemeliharaan->lokasi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Quantity</td>
                            <td>{{ $jadwalPemeliharaan->quantity }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Serial Number</td>
                            <td>{{ $jadwalPemeliharaan->serial_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kapasitas</td>
                            <td>{{ $jadwalPemeliharaan->kapasitas ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Merek</td>
                            <td>{{ $jadwalPemeliharaan->merek ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tipe</td>
                            <td>{{ $jadwalPemeliharaan->tipe ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tahun Pembuatan</td>
                            <td>{{ $jadwalPemeliharaan->tahun_pembuatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Keterangan</td>
                            <td>{{ $jadwalPemeliharaan->keterangan ?? '-' }}</td>
                        </tr>
                        @if($jadwalPemeliharaan->gambar)
                        <tr>
                            <td class="text-muted">Gambar</td>
                            <td>
                                <img src="{{ asset($jadwalPemeliharaan->gambar) }}" alt="Gambar" style="max-width: 150px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>

                <!-- SECTION 3: SELESAI -->
                @if($jadwalPemeliharaan->status == 'selesai')
                <hr style="margin: 1.5rem 0; border-top: 1px dashed #e2e8f0;">
                <div class="detail-section mb-4">
                    <h6 class="detail-title text-success"><i class="fas fa-check-circle mr-2"></i> Detail Penyelesaian</h6>
                    <table class="table table-sm table-borderless detail-table">
                        <tr>
                            <td width="35%" class="text-muted">Nama Onderdil</td>
                            <td width="65%" class="font-weight-bold">{{ $jadwalPemeliharaan->nama_onderdil ?? '-' }}</td>
                        </tr>
                        
                        <!-- TAMBAHAN: JUMLAH PEMAKAIAN -->
                        <tr>
                            <td class="text-muted">Jumlah Pemakaian</td>
                            <td class="font-weight-bold text-primary">
                                @if($jadwalPemeliharaan->jumlah_pemakaian)
                                    {{ $jadwalPemeliharaan->jumlah_pemakaian }} Unit
                                @else
                                    -
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="text-muted">Tanggal Diselesaikan</td>
                            <td>{{ \Carbon\Carbon::parse($jadwalPemeliharaan->tanggal_selesai)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Detail Penyelesaian</td>
                            <td>{{ $jadwalPemeliharaan->detail_penyelesaian ?? '-' }}</td>
                        </tr>
                        
                        <!-- BUKTI PEMAKAIAN ONDERDIL -->
                        @if($jadwalPemeliharaan->bukti_pemakaian_onderdil)
                        <tr>
                            <td class="text-muted">Bukti Pemakaian Onderdil</td>
                            <td>
                                <img src="{{ asset($jadwalPemeliharaan->bukti_pemakaian_onderdil) }}" alt="Bukti Pemakaian" style="max-width: 150px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            </td>
                        </tr>
                        @endif

                        @if($jadwalPemeliharaan->gambar_bukti)
                        <tr>
                            <td class="text-muted">Bukti Penyelesaian Alat</td>
                            <td>
                                <img src="{{ asset($jadwalPemeliharaan->gambar_bukti) }}" alt="Bukti" style="max-width: 150px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
                @endif

                <!-- SECTION 4: DITOLAK -->
                @if($jadwalPemeliharaan->status == 'ditolak')
                <hr style="margin: 1.5rem 0; border-top: 1px dashed #e2e8f0;">
                <div class="detail-section mb-4">
                    <h6 class="detail-title text-danger"><i class="fas fa-times-circle mr-2"></i> Detail Penolakan</h6>
                    <table class="table table-sm table-borderless detail-table">
                        <tr>
                            <td width="35%" class="text-muted">Keterangan</td>
                            <td width="65%">{{ $jadwalPemeliharaan->keterangan_tolak ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Alasan Penolakan</td>
                            <td>{{ $jadwalPemeliharaan->alasan_penolakan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                @endif

            </div>

            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px; padding: 0.5rem 1.5rem; font-weight: 500;">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS untuk merapikan tabel detail */
    .detail-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a1a2e;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f1f5f9;
    }
    .detail-table td {
        padding: 0.5rem 0; /* Jarak antar baris yang pas */
        vertical-align: top;
        font-size: 0.95rem;
    }
</style>