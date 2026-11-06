<button type="button" class="btn btn-action btn-edit-action" data-toggle="modal" data-target="#modalEditPengecekan{{ $jadwal->id }}">
    <i class="fas fa-edit"></i> Edit
</button>

<div class="modal fade modal-edit-pengecekan" id="modalEditPengecekan{{ $jadwal->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditPengecekanLabel{{ $jadwal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('supervisor.pengecekan-onderdil.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content" style="background:linear-gradient(90deg, #2C3E50 0%, #284F57 50%, #2F6D73 100%);">
                {{-- ================================================= --}}
                {{-- HEADER --}}
                {{-- ================================================= --}}
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalEditPengecekanLabel{{ $jadwal->id }}" style="color:white;">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Jadwal Pengecekan
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color:white;">&times;</span>
                    </button>
                </div>

                {{-- ================================================= --}}
                {{-- BODY --}}
                {{-- ================================================= --}}
                <div class="modal-body" style="color:white;">
                    {{-- ================================================= --}}
                    {{-- BARIS 1 --}}
                    {{-- JUDUL PENGECEKAN --}}
                    {{-- ================================================= --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="judul_pengecekan_edit_{{ $jadwal->id }}">
                                    Judul Pengecekan
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="judul_pengecekan" id="judul_pengecekan_edit_{{ $jadwal->id }}" class="form-control" value="{{ $jadwal->judul_pengecekan }}" placeholder="Masukkan judul pengecekan" required>
                            </div>
                        </div>
                    </div>

                    {{-- ================================================= --}}
                    {{-- BARIS 2 --}}
                    {{-- TANGGAL + TENAGA KERJA --}}
                    {{-- ================================================= --}}
                    <div class="row">
                        {{-- TANGGAL PENGECEKAN --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_pengecekan_edit_{{ $jadwal->id }}">Tanggal Pengecekan<span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pengecekan" id="tanggal_pengecekan_edit_{{ $jadwal->id }}" class="form-control" value="{{ \Carbon\Carbon::parse($jadwal->tanggal_pengecekan)->format('Y-m-d') }}" min="{{ min(\Carbon\Carbon::parse($jadwal->tanggal_pengecekan)->format('Y-m-d'), now('Asia/Jakarta')->toDateString()) }}" required>
                                @error('tanggal_pengecekan')
                                    <small class="text-warning d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        {{-- TENAGA KERJA --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tenaga_kerja_id_edit_{{ $jadwal->id }}"> Nama Tenaga Kerja<span class="text-danger">*</span></label>
                                <select name="tenaga_kerja_id" id="tenaga_kerja_id_edit_{{ $jadwal->id }}" class="form-control" required>
                                    <option value="">Pilih Tenaga Kerja</option>
                                    @foreach($tenagaKerja as $tk)
                                        <option value="{{ $tk->id }}" {{ $jadwal->tenaga_kerja_id == $tk->id ? 'selected' : '' }}> {{ $tk->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- ALASAN REVISI TANGGAL PENGECEKAN -->
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <label for="alasan_revisi_edit_{{ $jadwal->id }}" class="font-weight-bold">Alasan Perubahan Tenggat</label>
                                <textarea name="alasan_revisi" id="alasan_revisi_edit_{{ $jadwal->id }}" class="form-control w-100" rows="3" maxlength="1000" style="width: 100%;" placeholder="Masukkan alasan perubahan tanggal pengecekan...">{{ old('alasan_revisi') }}</textarea>
                                <small class="text-muted">Wajib diisi apabila tanggal pengecekan diubah. Minimal 10 karakter.</small>
                            </div>
                        </div>
                    </div>
                    {{-- ================================================= --}}
                    {{-- AMBIL ID ONDERDIL YANG SUDAH ADA --}}
                    {{-- ================================================= --}}
                    @php
                        $stokTerpilih = $jadwal->details->pluck('stok_onderdil_id')->filter()->map(function ($id) { return (int) $id; })->toArray();
                    @endphp

                    {{-- ================================================= --}}
                    {{-- BARIS 3 --}}
                    {{-- DAFTAR SPAREPART --}}
                    {{-- ================================================= --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label> Sparepart yang Harus Dicek<span class="text-danger">*</span></label>
                                {{-- PENCARIAN SPAREPART --}}
                                <input type="text" id="cariOnderdilEdit{{ $jadwal->id }}" class="form-control mb-2" placeholder="Cari nama sparepart...">
                                {{-- DAFTAR SPAREPART --}}
                                <div id="daftarOnderdilEdit{{ $jadwal->id }}" class="daftar-onderdil-edit" style="background:white; color:#2d3748; border:1px solid #ced4da; border-radius: 8px; padding: 10px; max-height: 140px; overflow-y: auto; overflow-x:auto">
                                    @forelse($stokOnderdil as $stok)
                                        <div class="custom-control custom-checkbox mb-2 item-onderdil">
                                            <input type="checkbox" class="custom-control-input" id="stok_edit_{{ $jadwal->id }}_{{ $stok->id }}" name="stok_onderdil_ids[]" value="{{ $stok->id }}" {{ in_array((int) $stok->id, $stokTerpilih) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="stok_edit_{{ $jadwal->id }}_{{ $stok->id }}">
                                                <strong class="nama-onderdil">
                                                    {{ $stok->nama_sparepart }}
                                                </strong>
                                                <span class="text-muted">
                                                    — Stok saat ini: {{ $stok->stok_terbaru }}
                                                </span>
                                            </label>
                                        </div>
                                    @empty
                                        <div class="text-muted">
                                            Belum ada data sparepart pada Manajemen Stok.
                                        </div>
                                    @endforelse
                                </div>
                                <small class="form-text-edit">
                                    Pilih minimal satu sparepart. Dapat memilih lebih dari satu sparepart dalam satu jadwal.
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- ================================================= --}}
                    {{-- BARIS 4 --}}
                    {{-- KETERANGAN --}}
                    {{-- ================================================= --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="keterangan_edit_{{ $jadwal->id }}">
                                    Keterangan
                                </label>
                                <textarea name="keterangan" id="keterangan_edit_{{ $jadwal->id }}" class="form-control" rows="3" placeholder="Masukkan keterangan (opsional)">{{ $jadwal->keterangan }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ================================================= --}}
                {{-- FOOTER --}}
                {{-- ================================================= --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ================================================= --}}
{{-- STYLE KHUSUS MODAL EDIT --}}
{{-- ================================================= --}}
<style>
    /*
    |--------------------------------------------------------------------------
    | SEMUA ISI MODAL EDIT RATA KIRI
    |--------------------------------------------------------------------------
    */
    .modal-edit-pengecekan .modal-header,
    .modal-edit-pengecekan .modal-title,
    .modal-edit-pengecekan .modal-body,
    .modal-edit-pengecekan .form-group {
        text-align: left !important;
    }

    /*
    |--------------------------------------------------------------------------
    | LABEL FORM
    |--------------------------------------------------------------------------
    */
    .modal-edit-pengecekan .form-group > label {
        display: block;
        width: 100%;
        text-align: left !important;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    /*
    |--------------------------------------------------------------------------
    | INPUT, SELECT, TEXTAREA
    |--------------------------------------------------------------------------
    */
    .modal-edit-pengecekan .form-control {
        text-align: left !important;
    }

    /*
    |--------------------------------------------------------------------------
    | DAFTAR SPAREPART
    |--------------------------------------------------------------------------
    */
    .modal-edit-pengecekan .daftar-onderdil-edit {
        text-align: left !important;
    }

    .modal-edit-pengecekan .item-onderdil {
        text-align: left !important;
    }

    .modal-edit-pengecekan .custom-control-label {
        display: block;
        text-align: left !important;
        cursor: pointer;
    }

    .modal-edit-pengecekan .nama-onderdil {
        text-align: left !important;
    }

    /*
    |--------------------------------------------------------------------------
    | TEKS BANTUAN
    |--------------------------------------------------------------------------
    */
    .modal-edit-pengecekan .form-text-edit {
        display: block;
        color: #cccccc;
        text-align: left !important;
        margin-top: 0.45rem;
    }
</style>

{{-- ================================================= --}}
{{-- PENCARIAN SPAREPART --}}
{{-- ================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputCari = document.getElementById('cariOnderdilEdit{{ $jadwal->id }}');
    const daftarOnderdil = document.getElementById('daftarOnderdilEdit{{ $jadwal->id }}');
    if (inputCari && daftarOnderdil) {
        inputCari.addEventListener('keyup', function () {
            const keyword = this.value.toLowerCase().trim();
            const items = daftarOnderdil.querySelectorAll('.item-onderdil');
            items.forEach(function (item) {
                const namaOnderdil = item
                    .querySelector('.nama-onderdil')
                    .textContent
                    .toLowerCase();
                if (namaOnderdil.includes(keyword)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
});
</script>
