<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemakaianOnderdilPemeliharaan extends Model
{
    protected $table = 'pemakaian_onderdil_pemeliharaan';

    protected $fillable = [
        'stok_onderdil_id',
        'nama_sparepart_snapshot',
        'jumlah',
    ];

    protected $casts = ['jumlah' => 'integer'];

    public function jadwalPemeliharaan(): BelongsTo
    {
        return $this->belongsTo(JadwalPemeliharaan::class);
    }

    public function stokOnderdil(): BelongsTo
    {
        return $this->belongsTo(StokOnderdil::class);
    }
}
