<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalPemeliharaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadwal_pemeliharaan';

    protected $fillable = [
        'judul_pemeliharaan',
        'tanggal_selesai',
        'tanggal_target_awal',
        'tanggal_kirim_hasil',
        'tanggal_verifikasi',
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
        'jumlah_pemakaian',
        'detail_penyelesaian',
        'gambar_bukti',
        'bukti_pemakaian_onderdil',
        'keterangan_tolak',
        'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal_selesai' => 'date',
        'tahun_pembuatan' => 'integer',
        'tanggal_target_awal' => 'date',
        'tanggal_kirim_hasil' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'quantity' => 'integer',
        'jumlah_pemakaian' => 'integer',
    ];

    /* Relasi satu jadwal pemeliharaan dengan banyak pemakaian sparepart. */
    public function pemakaianOnderdil()
    {
        return $this->hasMany(
            PemakaianOnderdilPemeliharaan::class, 'jadwal_pemeliharaan_id', 'id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nama_tenaga_kerja', 'id')->withTrashed();
    }
    
    /* Relasi riwayat perubahan tenggat pemeliharaan. */
    public function riwayatRevisi()
    {
        return $this->hasMany(\App\Models\RiwayatRevisiJadwal::class, 'jadwal_id')->where('jenis_jadwal', 'pemeliharaan')->orderBy('direvisi_pada', 'asc');
    }
}
