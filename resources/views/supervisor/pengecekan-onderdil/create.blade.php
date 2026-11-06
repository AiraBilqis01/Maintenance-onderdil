<button type="button" class="btn btn-tambah-header" data-toggle="modal" data-target="#modalTambahPengecekanOnderdil">
    <i class="fas fa-plus"></i> Tambah Jadwal Pengecekan
</button>

<div class="modal fade" id="modalTambahPengecekanOnderdil" tabindex="-1" role="dialog" aria-labelledby="modalTambahPengecekanOnderdilLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('supervisor.pengecekan-onderdil.store') }}" method="POST">
            @csrf
            <div class="modal-content" style="background: linear-gradient( 90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100% );">
                {{-- HEADER --}}
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahPengecekanOnderdilLabel" style="color: white;">
                        <i class="fas fa-clipboard-check mr-2"></i> Tambah Jadwal Pengecekan
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white;">&times;</span>
                    </button>
                </div>
                {{-- BODY --}}
                <div class="modal-body" style="color: white;">
                    {{-- BARIS 1 --}}
                    {{-- Judul Pengecekan --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="judul_pengecekan"> Judul Pengecekan
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="judul_pengecekan" id="judul_pengecekan" class="form-control" value="{{ old('judul_pengecekan') }}" placeholder="Masukkan judul pengecekan" required>
                            </div>
                        </div>
                    </div>
                    {{-- BARIS 2 --}}
                    {{-- Tanggal + Tenaga Kerja --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_pengecekan"> Tanggal Pengecekan<span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pengecekan" id="tanggal_pengecekan" class="form-control" value="{{ old('tanggal_pengecekan') }}" min="{{ now('Asia/Jakarta')->toDateString() }}" required>
                                @error('tanggal_pengecekan')
                                    <small class="text-warning d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tenaga_kerja_id"> Nama Tenaga Kerja
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="tenaga_kerja_id" id="tenaga_kerja_id" class="form-control" required>
                                    <option value=""> Pilih Tenaga Kerja</option>
                                    @foreach($tenagaKerja as $tk)
                                        <option value="{{ $tk->id }}"{{ old('tenaga_kerja_id') == $tk->id ? 'selected' : '' }}>{{ $tk->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- BARIS 3 --}}
                    {{-- Daftar Onderdil --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Sparepart yang Harus Dicek
                                    <span class="text-danger">*</span>
                                </label>
                                {{-- Pencarian Onderdil --}}
                                <input type="text" id="cariOnderdilTambah" class="form-control mb-2" placeholder="Cari nama sparepart...">
                                {{-- Daftar Onderdil --}}
                                <div id="daftarOnderdilTambah" style=" background: white; color: #2d3748; border:1px solid #ced4da; border-radius: 8px; padding: 10px; max-height: 140px; overflow-y: auto; overflow-x:auto">
                                    @forelse($stokOnderdil as $stok)
                                        <div class="custom-control custom-checkbox mb-2 item-onderdil">
                                            <input type="checkbox" class="custom-control-input" id="stok_tambah_{{ $stok->id }}" name="stok_onderdil_ids[]" value="{{ $stok->id }}" {{ in_array( $stok->id, old('stok_onderdil_ids', [])) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="stok_tambah_{{ $stok->id }}">
                                                <strong class="nama-onderdil">{{ $stok->nama_sparepart }}</strong>
                                                <span class="text-muted"> — Stok saat ini: {{ $stok->stok_terbaru }}</span>
                                            </label>
                                        </div>
                                    @empty
                                        <div class="text-muted"> Belum ada data sparepart pada Manajemen Stok.</div>
                                    @endforelse
                                </div>
                                <small style="color: #ccc;">Pilih minimal satu sparepart. Dapat memilih lebih dari satu sparepart dalam satu jadwal.</small>
                            </div>
                        </div>
                    </div>
                    {{-- BARIS 4 --}}
                    {{-- Keterangan --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="keterangan"> Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="3"  placeholder="Masukkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- FOOTER --}}
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputCari = document.getElementById('cariOnderdilTambah');
    if (!inputCari) {
        return;
    }
    inputCari.addEventListener('input', function () {
        const keyword = this.value .toLowerCase() .trim();
        const items = document.querySelectorAll('#daftarOnderdilTambah .item-onderdil');

        items.forEach(function (item) {
            const elemenNama = item.querySelector('.nama-onderdil');
            if (!elemenNama) {
                return;
            }
            const nama = elemenNama.textContent .toLowerCase() .trim();
            if (nama.includes(keyword)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
