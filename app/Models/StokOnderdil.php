<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokOnderdil extends Model
{
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
}