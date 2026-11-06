<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatRevisiJadwal extends Model
{
    // Nama tabel yang digunakan
    protected $table = 'riwayat_revisi_jadwal';

    // Kolom yang boleh diisi melalui create()
    protected $fillable = [
        'jenis_jadwal',
        'jadwal_id',
        'tanggal_lama',
        'tanggal_baru',
        'alasan_revisi',
        'supervisor_id',
        'direvisi_pada',
        'terlambat_sebelum_revisi',
        'hari_terlambat_sebelum_revisi',
    ];

    // Konversi tipe data secara otomatis
    protected $casts = [
        'tanggal_lama' => 'date',
        'tanggal_baru' => 'date',
        'direvisi_pada' => 'datetime',
        'terlambat_sebelum_revisi' => 'boolean',
        'hari_terlambat_sebelum_revisi' => 'integer',
    ];

    // Relasi dengan akun Supervisor
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
