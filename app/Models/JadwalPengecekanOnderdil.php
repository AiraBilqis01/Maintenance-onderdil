<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalPengecekanOnderdil extends Model
{
    use HasFactory, SoftDeletes;
    /* Nama tabel yang digunakan oleh model. */
    protected $table = 'jadwal_pengecekan_onderdil';

    /* Kolom yang boleh diisi melalui create() atau update(). */
    protected $fillable = [
        'judul_pengecekan',
        'tanggal_pengecekan',
        'tanggal_target_awal',
        'tanggal_kirim_hasil',
        'tanggal_verifikasi',
        'tenaga_kerja_id',
        'keterangan',
        'status',
        'keterangan_tolak',
        'alasan_penolakan',
        
    ];

    /* Casting tipe data. */
    protected $casts = [
        'tanggal_pengecekan' => 'date',
        'tanggal_target_awal' => 'date',
        'tanggal_kirim_hasil' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
    ];

    /* Relasi ke tenaga kerja yang ditugaskan.
     * Satu jadwal pengecekan dimiliki oleh satu tenaga kerja. */
    public function tenagaKerja()
    {
        return $this->belongsTo(User::class,'tenaga_kerja_id','id')->withTrashed();
    }

    /* Relasi ke detail onderdil yang harus diperiksa.
     * Satu jadwal pengecekan dapat memiliki banyak onderdil. */
    public function details()
    {
        return $this->hasMany(DetailPengecekanOnderdil::class,'jadwal_pengecekan_id','id');
    }

    
    /* Relasi riwayat perubahan tenggat pada Jadwal Pengecekan Sparepart. */
    public function riwayatRevisi()
    {
        return $this->hasMany(\App\Models\RiwayatRevisiJadwal::class, 'jadwal_id')->where('jenis_jadwal', 'pengecekan')->orderBy('direvisi_pada', 'asc');
    }
}
