<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokOnderdilSeeder extends Seeder
{
    public function run(): void
    {
        $stokOnderdil = [
            [
                'nama_sparepart' => 'Oli Mesin 10W-40',
                'gambar' => 'assets/img/nailong.png',
                'stok_periode_sebelum' => 15,
                'periode' => '2026-01-01',
                'kondisi_sekarang' => 'Baik',
                'pemakaian' => 5,
                'stok_terbaru' => 15,
                'keterangan' => 'Oli mesin untuk kendaraan roda empat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_sparepart' => 'Filter Udara',
                'gambar' => 'assets/img/nailong.png',
                'stok_periode_sebelum' => 10,
                'periode' => '2026-01-01',
                'kondisi_sekarang' => 'Rusak Ringan',
                'pemakaian' => 2,
                'stok_terbaru' => 10,
                'keterangan' => 'Filter udara untuk mesin diesel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('stok_onderdil')->insert($stokOnderdil);
    }
}