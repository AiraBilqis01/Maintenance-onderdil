<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemakaianOnderdilPemeliharaan extends Model
{
    // Nama tabel pada database
    protected $table = 'pemakaian_onderdil_pemeliharaan';

    // Kolom yang boleh diisi melalui Eloquent
    protected $fillable = [
        'stok_onderdil_id',
        'nama_sparepart_snapshot',
        'jumlah',
    ];

    // Pastikan jumlah pemakaian berupa integer
    protected $casts = [
        'jumlah' => 'integer',
    ];

    // Relasi ke jadwal pemeliharaan
    public function jadwalPemeliharaan(): BelongsTo
    {
        return $this->belongsTo(
            JadwalPemeliharaan::class, 'jadwal_pemeliharaan_id'
        );
    }
        
    /* Relasi ke Manajemen Stok Onderdil.
    * withTrashed() memungkinkan riwayat pemakaian tetap mengakses data onderdil yang sudah diarsipkan.
    * Pengarsipan stok tidak menghapus hubungan dengan riwayat pemeliharaan. */
    public function stokOnderdil(): BelongsTo
    {
        return $this->belongsTo(StokOnderdil::class, 'stok_onderdil_id')->withTrashed();
    }
}