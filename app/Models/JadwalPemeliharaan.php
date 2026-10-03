<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pemeliharaan';

    protected $fillable = [
        'judul_pemeliharaan',
        'tanggal_selesai',
        'nama_tenaga_kerja',
        'nama_peralatan',
        'lokasi',
        'quantity',
        'serial_number',
        'kapasitas',
        'merek',
        'tipe',
        'tahun_pembuatan',
        'gambar',
        'keterangan',
        'status',
        'nama_onderdil',
        'detail_penyelesaian',
        'gambar_bukti',
        'keterangan_tolak',
        'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal_selesai' => 'date',
        'tahun_pembuatan' => 'integer',
        'quantity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'nama_tenaga_kerja', 'id');
    }
}