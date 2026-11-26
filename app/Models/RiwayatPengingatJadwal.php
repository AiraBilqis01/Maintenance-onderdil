<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPengingatJadwal extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pengingat_jadwal';

    protected $fillable = [
        'jenis_jadwal',
        'jadwal_id',
        'user_id',
        'tanggal_target',
        'jenis_pengingat',
        'dikirim_pada',
    ];

    protected $casts = [
        'tanggal_target' => 'date',
        'dikirim_pada' => 'datetime',
    ];

    // Relasi dengan Tenaga Kerja penerima email
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}
