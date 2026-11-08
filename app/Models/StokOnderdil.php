<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class StokOnderdil extends Model
{
    use SoftDeletes;
    protected $table = 'stok_onderdil';

    protected $fillable = [
        'nama_sparepart',
        'gambar',
        'stok_periode_sebelum',
        'periode',
        'kondisi_sekarang',
        'pemakaian',
        'stok_terbaru',
        'keterangan',
    ];

    /* Relasi ke detail pengecekan onderdil.
     * Satu stok onderdil dapat muncul pada banyak detail pengecekan. */
    public function detailPengecekan()
    {
        return $this->hasMany(DetailPengecekanOnderdil::class,'stok_onderdil_id', 'id');
    }

    /* Relasi ke riwayat pemakaian onderdil pada Jadwal Pemeliharaan.
     * Satu stok onderdil dapat digunakan dalam banyak pekerjaan pemeliharaan. */
    public function pemakaianPemeliharaan()
    {
        return $this->hasMany(PemakaianOnderdilPemeliharaan::class, 'stok_onderdil_id', 'id');
    }
}
