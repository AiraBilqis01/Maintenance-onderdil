<button type="button" class="btn btn-action btn-detail-action" data-toggle="modal" data-target="#modalDetailPengecekan{{ $jadwal->id }}">
    <i class="fas fa-eye"></i> Detail
</button>

<div class="modal fade modal-detail-pengecekan" id="modalDetailPengecekan{{ $jadwal->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDetailPengecekanLabel{{ $jadwal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden; border:none;">
            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}
            <div class="modal-header" style="background:#1a1a2e; color:white; padding:1.2rem 1.5rem; border-bottom:none;">
                <h5 class="modal-title" id="modalDetailPengecekanLabel{{ $jadwal->id }}" style="font-size:1.1rem; font-weight:600; text-align:left;">
                    <i class="fas fa-clipboard-check mr-2"></i>Detail Jadwal Pengecekan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:white; opacity:0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- ================================================= --}}
            {{-- BODY --}}
            {{-- ================================================= --}}
            <div class="modal-body" style="padding:1.5rem; background:#ffffff;">
                {{-- ================================================= --}}
                {{-- SECTION 1 : INFORMASI JADWAL --}}
                {{-- ================================================= --}}
                <div class="detail-section mb-4">
                    <h6 class="detail-title">
                        <i class="fas fa-info-circle text-primary mr-2"></i>Informasi Jadwal
                    </h6>
                    <table class="table table-sm table-borderless detail-table">
                        <tbody>
                            <tr>
                                <td width="30%" class="text-muted">Judul Pengecekan</td>
                                <td width="70%" class="font-weight-bold text-dark">
                                    {{ $jadwal->judul_pengecekan }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Pengecekan</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($jadwal->tanggal_pengecekan)->format('d/m/Y') }}
                                </td>
                            </tr>
                            {{-- TANGGAL PENGIRIMAN HASIL PENGECEKAN --}}
                            <tr>
                                <td class="text-muted">Tanggal Pengiriman Hasil</td>
                                <td>
                                    @if(in_array($jadwal->status, ['menunggu_verifikasi', 'selesai']) && !empty($jadwal->tanggal_kirim_hasil))
                                        {{ \Carbon\Carbon::parse($jadwal->tanggal_kirim_hasil)->format('d F Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tenaga Kerja</td>
                                <td>
                                    {{ $jadwal->tenagaKerja->name ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td>
                                    @if($jadwal->status == 'pending')
                                        <span class="badge badge-warning px-2 py-1">
                                            <i class="fas fa-clock"></i>Pending
                                        </span>
                                    @elseif($jadwal->status == 'menunggu_verifikasi')
                                        <span class="badge badge-info px-2 py-1">
                                            <i class="fas fa-hourglass-half"></i>Menunggu Verifikasi
                                        </span>
                                    @elseif($jadwal->status == 'selesai')
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fas fa-check-circle"></i>Selesai
                                        </span>
                                    @elseif($jadwal->status == 'ditolak')
                                        <span class="badge badge-danger px-2 py-1">
                                            <i class="fas fa-times-circle"></i>Ditolak
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">
                                            {{ $jadwal->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Keterangan Jadwal</td>
                                <td>
                                    {{ $jadwal->keterangan ?? '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr style="margin:1.5rem 0; border-top:1px dashed #e2e8f0;">

                {{-- ================================================= --}}
                {{-- SECTION 2 : DETAIL SPAREPART --}}
                {{-- ================================================= --}}
                <div class="detail-section mb-4">
                    <h6 class="detail-title">
                        <i class="fas fa-boxes text-primary mr-2"></i>Detail Sparepart
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm detail-sparepart-table">
                            <thead class="thead-light">
                                <tr>
                                    <th width="4%">No</th>
                                    <th>Nama Sparepart</th>
                                    <th>Stok Sistem</th>
                                    <th>Stok Fisik</th>
                                    <th>Selisih</th>
                                    <th>Kondisi</th>
                                    <th>Penyebab Selisih</th>
                                    <th>Keterangan Selisih</th>
                                    <th>Catatan Pengecekan</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwal->details as $detail)
                                    <tr>
                                        {{-- NO --}}
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        {{-- NAMA SPAREPART --}}
                                        <td class="text-center">
                                            <strong>
                                                {{ $detail->nama_sparepart_snapshot }}
                                            </strong>
                                        </td>
                                        {{-- STOK SISTEM --}}
                                        <td class="text-center">
                                            @if(!is_null($detail->stok_sistem_saat_cek))
                                                {{-- Sudah dilakukan pengecekan --}}
                                                <strong>
                                                    {{ $detail->stok_sistem_saat_cek }}
                                                </strong><br>
                                                <small class="text-muted">Saat pengecekan</small>
                                            @elseif($detail->stokOnderdil)
                                                {{-- Belum dilakukan pengecekan --}}
                                                <strong>
                                                    {{ $detail->stokOnderdil->stok_terbaru }}
                                                </strong><br>
                                                <small class="text-muted">Stok saat ini</small>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        {{-- STOK FISIK --}}
                                        <td class="text-center">
                                            @if(!is_null($detail->stok_fisik))
                                                {{ $detail->stok_fisik }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        {{-- SELISIH --}}
                                        <td class="text-center">
                                            @if(!is_null($detail->selisih))
                                                @if($detail->selisih > 0)
                                                    <span class="text-success font-weight-bold">
                                                        +{{ $detail->selisih }}
                                                    </span>
                                                @elseif($detail->selisih < 0)
                                                    <span class="text-danger font-weight-bold">
                                                        {{ $detail->selisih }}
                                                    </span>
                                                @else
                                                    <span class="text-success font-weight-bold">0</span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        {{-- KONDISI --}}
                                        <td class="text-center">
                                            {{ $detail->kondisi ?? '-' }}
                                        </td>
                                        {{-- PENYEBAB SELISIH --}}
                                        <td class="text-center">
                                            {{ $detail->penyebab_selisih ?? '-' }}
                                        </td>
                                        {{-- KETERANGAN SELISIH --}}
                                        <td class="text-center">
                                            {{ $detail->keterangan_selisih ?? '-' }}
                                        </td>
                                        {{-- CATATAN PENGECEKAN --}}
                                        <td class="text-center">
                                            {{ $detail->catatan_pengecekan ?? '-' }}
                                        </td>
                                        {{-- BUKTI --}}
                                        <td class="text-center">
                                            @if($detail->gambar_bukti)
                                                <a href="{{ asset($detail->gambar_bukti) }}" target="_blank">
                                                    <img src="{{ asset($detail->gambar_bukti) }}" alt="Bukti Pengecekan" style="width:70px; height:70px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0;">
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">Tidak ada detail sparepart.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ================================================= --}}
                {{-- SECTION 3 : INFORMASI PENOLAKAN --}}
                {{-- ================================================= --}}
                @if(!empty($jadwal->alasan_penolakan) || !empty($jadwal->keterangan_tolak))
                    <hr style="margin:1.5rem 0; border-top:1px dashed #e2e8f0;">
                    <div class="detail-section mb-4">
                        {{-- PENOLAKAN DARI TENAGA KERJA --}}
                        @if($jadwal->status == 'menunggu_verifikasi' && $jadwal->details->whereNotNull('stok_fisik')->isEmpty())
                            <h6 class="detail-title text-danger">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Pengajuan Penolakan Tenaga Kerja
                            </h6>
                        {{-- HASIL DITOLAK SUPERVISOR --}}
                        @elseif($jadwal->status == 'pending')
                            <h6 class="detail-title text-danger">
                                <i class="fas fa-exclamation-circle mr-2"></i>Catatan Penolakan Supervisor
                            </h6>
                        {{-- PENOLAKAN SUDAH DISETUJUI --}}
                        @elseif($jadwal->status == 'ditolak')
                            <h6 class="detail-title text-danger">
                                <i class="fas fa-times-circle mr-2"></i>Detail Penolakan
                            </h6>
                        @else
                            <h6 class="detail-title text-danger">
                                <i class="fas fa-exclamation-circle mr-2"></i>Informasi Penolakan
                            </h6>
                        @endif
                        <table class="table table-sm table-borderless detail-table">
                            <tbody>
                                <tr>
                                    <td width="30%" class="text-muted">Keterangan</td>
                                    <td width="70%">
                                        {{ $jadwal->keterangan_tolak ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Alasan Penolakan</td>
                                    <td>
                                        {{ $jadwal->alasan_penolakan ?? '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- ================================================= --}}
                {{-- SECTION 4 : KETERANGAN HASIL --}}
                {{-- ================================================= --}}
                @if($jadwal->status == 'selesai')
                    <hr style="margin:1.5rem 0; border-top:1px dashed #e2e8f0;">
                    <div class="alert alert-success mb-0" style="text-align:left;">
                        <i class="fas fa-check-circle mr-1"></i>Hasil pengecekan telah diverifikasi oleh Supervisor dan data stok telah disesuaikan berdasarkan hasil pengecekan fisik.
                    </div>
                @endif

                {{-- =====================================================
                    RIWAYAT REVISI JADWAL PENGECEKAN ONDERDIL
                    ===================================================== --}}
                @php
                    /* Ambil seluruh riwayat revisi jadwal dan urutkan berdasarkan ID paling awal. */
                    $riwayatRevisiPengecekan = $jadwal->riwayatRevisi->sortBy('id')->values();

                    /* Hitung keterlambatan terbesar. Tidak menjumlahkan keterlambatan dari seluruh revisi. */
                    $keterlambatanTerbesarPengecekan = (int) ($riwayatRevisiPengecekan->max('hari_terlambat_sebelum_revisi') ?? 0);
                @endphp

                <hr style="margin: 1.5rem 0; border-top: 1px dashed #e2e8f0;">

                <div class="mt-4 border rounded p-3 riwayat-revisi-pengecekan" style="background:#f3f6ff; text-align:left;">
                    <h5 class="mb-3" style="color:#2d3748;">
                        <i class="fas fa-history mr-2"></i>Riwayat Revisi Jadwal Pengecekan
                    </h5>
                    @if($riwayatRevisiPengecekan->count() > 0)
                        {{-- RINGKASAN RIWAYAT --}}
                        <div class="mb-3">
                            <strong>Jumlah Revisi:</strong>
                            {{ $riwayatRevisiPengecekan->count() }} kali
                        </div>
                        <div class="mb-3">
                            <strong>Keterlambatan Terbesar Sebelum Revisi:</strong>
                            @if($keterlambatanTerbesarPengecekan > 0)
                                <span class="text-danger font-weight-bold">{{ $keterlambatanTerbesarPengecekan }} hari</span>
                            @else
                                <span class="text-success">Tidak ada keterlambatan</span>
                            @endif
                        </div>
                        <hr>

                        {{-- RINCIAN SETIAP REVISI --}}
                        @foreach($riwayatRevisiPengecekan as $revisi)
                            <div class="border rounded p-3 mb-3 bg-white">
                                <h6 class="font-weight-bold mb-3">
                                    <i class="fas fa-calendar-alt mr-1"></i>Revisi ke-{{ $loop->iteration }}
                                </h6>
                                <p class="mb-2">
                                    <strong>Target Sebelumnya:</strong>
                                    {{ $revisi->tanggal_lama ? \Carbon\Carbon::parse($revisi->tanggal_lama)->format('d/m/Y') : '-' }}
                                </p>
                                <p class="mb-2">
                                    <strong>Target Baru:</strong>
                                    {{ $revisi->tanggal_baru ? \Carbon\Carbon::parse($revisi->tanggal_baru)->format('d/m/Y') : '-' }}
                                </p>
                                <p class="mb-2">
                                    <strong>Tanggal Revisi:</strong>
                                    @if($revisi->direvisi_pada)
                                        {{ \Carbon\Carbon::parse($revisi->direvisi_pada)->format('d/m/Y H:i') }} WIB
                                    @else
                                        -
                                    @endif
                                </p>
                                <p class="mb-2">
                                    <strong>Keterlambatan:</strong>
                                    @if((int) $revisi->hari_terlambat_sebelum_revisi > 0)
                                        <span class="text-danger">
                                            Terlambat {{ $revisi->hari_terlambat_sebelum_revisi }} hari sebelum revisi
                                        </span>
                                    @else
                                        <span class="text-success">
                                            Tidak terlambat sebelum revisi
                                        </span>
                                    @endif
                                </p>
                                <div class="mt-2">
                                    <strong>Alasan Revisi:</strong>
                                    <p class="mb-0 mt-1">
                                        {{ $revisi->alasan_revisi ?: '-' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle mr-1"></i>Jadwal ini belum pernah mengalami revisi tanggal.
                        </p>
                    @endif
                </div>
            </div>
            {{-- ================================================= --}}
            {{-- FOOTER --}}
            {{-- ================================================= --}}
            <div class="modal-footer" style="background:#f8fafc; border-top:1px solid #e2e8f0; padding:1rem 1.5rem;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:8px; padding:0.5rem 1.5rem; font-weight:500;">
                    <i class="fas fa-times mr-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /*
    |--------------------------------------------------------------------------
    | JUDUL MODAL DAN SECTION
    |--------------------------------------------------------------------------
    */
    .modal-detail-pengecekan .modal-title,
    .modal-detail-pengecekan .detail-title {
        text-align: left !important;
    }

    /*
    |--------------------------------------------------------------------------
    | JUDUL SECTION
    |--------------------------------------------------------------------------
    */
    .modal-detail-pengecekan .detail-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a1a2e;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f1f5f9;
    }

    /*
    |--------------------------------------------------------------------------
    | INFORMASI JADWAL DAN PENOLAKAN
    | RATA KIRI
    |--------------------------------------------------------------------------
    */
    .modal-detail-pengecekan .detail-table {
        width: 100%;
    }

    .modal-detail-pengecekan .detail-table td {
        padding: 0.7rem 0.6rem;
        vertical-align: middle;
        font-size: 0.95rem;
        text-align: left !important;
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL SPAREPART
    | TETAP RATA TENGAH
    |--------------------------------------------------------------------------
    */
    .modal-detail-pengecekan .detail-sparepart-table {
        width: 100%;
        min-width: 1200px;
    }

    .modal-detail-pengecekan .detail-sparepart-table th {
        text-align: center !important;
        vertical-align: middle;
        white-space: nowrap;
        font-size: 0.8rem;
        padding: 0.7rem;
    }

    .modal-detail-pengecekan .detail-sparepart-table td {
        text-align: center !important;
        vertical-align: middle;
        font-size: 0.85rem;
        padding: 0.7rem;
    }

    /*
    |--------------------------------------------------------------------------
    | GAMBAR BUKTI
    |--------------------------------------------------------------------------
    */
    .modal-detail-pengecekan .detail-sparepart-table img {
        display: inline-block;
        margin: 0 auto;
    }

    /*
    |--------------------------------------------------------------------------
    | RIWAYAT REVISI JADWAL PENGECEKAN
    | SELURUH ISI RATA KIRI
    |--------------------------------------------------------------------------
    */
    .modal-detail-pengecekan .riwayat-revisi-pengecekan,
    .modal-detail-pengecekan .riwayat-revisi-pengecekan * {
        text-align: left !important;
    }
</style>
