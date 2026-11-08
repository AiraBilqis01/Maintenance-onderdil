<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengecekanOnderdil extends Model
{
    use HasFactory;
    /* Nama tabel yang digunakan model. */
    protected $table = 'detail_pengecekan_onderdil';

    /* Kolom yang boleh diisi melalui create() atau update(). */
    protected $fillable = [
        'jadwal_pengecekan_id',
        'stok_onderdil_id',
        'nama_sparepart_snapshot',
        'stok_sistem_saat_cek',
        'stok_fisik',
        'selisih',
        'penyebab_selisih',
        'keterangan_selisih',
        'kondisi',
        'catatan_pengecekan',
        'gambar_bukti',
    ];

    /* Relasi ke jadwal pengecekan.
     * Banyak detail pengecekan dimiliki oleh satu jadwal pengecekan. */
    public function jadwal()
    {
        return $this->belongsTo(JadwalPengecekanOnderdil::class,'jadwal_pengecekan_id', 'id');
    }

    /* Relasi ke Manajemen Stok Onderdil.
     * withTrashed() memungkinkan detail pengecekan tetap membaca data stok yang sudah diarsipkan.
     * Riwayat pengecekan dan hubungan ke onderdil tetap dipertahankan. */
    public function stokOnderdil()
    {
        return $this->belongsTo(StokOnderdil::class, 'stok_onderdil_id', 'id')->withTrashed();
    }
}
