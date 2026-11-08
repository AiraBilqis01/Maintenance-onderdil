{{-- =====================================================
    TOMBOL HAPUS / ARSIPKAN STOK ONDERDIL
===================================================== --}}
<button type="button" class="btn btn-delete-action" data-toggle="modal" data-target="#modalDeleteStokOnderdil{{ $stokOnderdil->id }}">
    <i class="fas fa-archive mr-1"></i> Hapus / Arsipkan
</button>

{{-- =====================================================
    MODAL KONFIRMASI HAPUS / ARSIPKAN
===================================================== --}}
<div class="modal fade" id="modalDeleteStokOnderdil{{ $stokOnderdil->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDeleteStokOnderdilLabel{{ $stokOnderdil->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            {{-- HEADER --}}
            <div class="modal-header" style="background: linear-gradient( 90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100% );">
                <h5 class="modal-title text-white" id="modalDeleteStokOnderdilLabel{{ $stokOnderdil->id }}">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Hapus / Arsipkan Sparepart
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body py-4">
                <div class="text-center mb-4">
                    <i class="fas fa-box-open text-warning" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <p class="mb-2" style="font-size: 1.05rem;">
                        Apakah Anda yakin ingin memproses sparepart berikut?
                    </p>
                    <h5 class="font-weight-bold">
                        {{ $stokOnderdil->nama_sparepart }}
                    </h5>
                </div>

                {{-- INFORMASI ATURAN PENGHAPUSAN --}}
                <div class="alert alert-light border text-left"
                    style="font-size: 0.88rem; line-height: 1.7;">
                    <p class="font-weight-bold mb-2">
                        Sistem akan menentukan tindakan secara otomatis:
                    </p>
                    <div class="mb-2">
                        <i class="fas fa-trash-alt text-danger mr-2"></i>
                        <strong>Hapus Permanen</strong>
                        <div class="text-muted ml-4">
                            Jika sparepart belum pernah terkait dengan
                            jadwal pemeliharaan atau pengecekan.
                        </div>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-archive text-primary mr-2"></i>
                        <strong>Arsipkan</strong>
                        <div class="text-muted ml-4">
                            Jika sparepart hanya memiliki riwayat
                            pekerjaan yang sudah selesai.
                            Data tetap tersimpan dan dapat dipulihkan.
                        </div>
                    </div>
                    <div>
                        <i class="fas fa-ban text-warning mr-2"></i>
                        <strong>Penghapusan Ditolak</strong>
                        <div class="text-muted ml-4">
                            Jika sparepart masih terkait dengan jadwal yang belum selesai.
                        </div>
                    </div>
                </div>

                {{-- PERINGATAN --}}
                <div class="alert alert-warning mb-0" style="font-size: 0.85rem;">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Perhatian:</strong>
                    Pastikan data stok dan persediaan fisik sudah
                    diperiksa sebelum melanjutkan.
                    Sparepart yang diarsipkan tidak akan tampil
                    dalam daftar Manajemen Stok aktif.
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer d-block">
                <form action="{{ route('supervisor.stok-onderdil.destroy', $stokOnderdil->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    {{-- KONFIRMASI KHUSUS DATA SALAH INPUT --}}
                    <div class="border rounded p-3 mb-3" style="background: #fff8f0;">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="konfirmasiHapusSalahInput{{ $stokOnderdil->id }}" name="konfirmasi_hapus_salah_input" value="1">
                            <label class="form-check-label" for="konfirmasiHapusSalahInput{{ $stokOnderdil->id }}" style="font-size: 0.88rem; line-height: 1.6; cursor: pointer;">
                                <strong>
                                    Konfirmasi penghapusan permanen data salah input
                                </strong>
                                <span class="d-block text-muted mt-1">
                                    Saya memastikan bahwa data sparepart ini
                                    merupakan kesalahan input, bukan catatan
                                    persediaan fisik yang sebenarnya, dan
                                    belum pernah digunakan dalam pekerjaan
                                    pemeliharaan maupun pengecekan.
                                </span>
                            </label>
                        </div>
                        <small class="d-block text-muted mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Centang hanya jika ingin menghapus permanen
                            data yang salah input. Tidak perlu dicentang
                            untuk pengarsipan data yang memiliki riwayat.
                        </small>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="d-flex justify-content-end flex-wrap" style="gap: 8px;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-check mr-1"></i> Ya, Lanjutkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>