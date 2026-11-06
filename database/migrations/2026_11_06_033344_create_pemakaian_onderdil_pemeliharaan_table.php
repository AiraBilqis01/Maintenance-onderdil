<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemakaian_onderdil_pemeliharaan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jadwal_pemeliharaan_id')
                ->constrained('jadwal_pemeliharaan')
                ->restrictOnDelete();

            $table->foreignId('stok_onderdil_id')
                ->nullable()
                ->constrained('stok_onderdil')
                ->nullOnDelete();

            $table->string('nama_sparepart_snapshot');

            $table->unsignedInteger('jumlah');

            $table->timestamps();

            $table->unique(
                ['jadwal_pemeliharaan_id', 'stok_onderdil_id'],
                'pemeliharaan_sparepart_unik'
            );
        });

        // Memindahkan salinan pemakaian sparepart lama
        // tanpa melakukan pengurangan stok kembali.
        DB::table('jadwal_pemeliharaan')
            ->whereNotNull('nama_onderdil')
            ->whereNotNull('jumlah_pemakaian')
            ->orderBy('id')
            ->chunkById(200, function ($jadwals) {
                foreach ($jadwals as $jadwal) {
                    if (trim((string) $jadwal->nama_onderdil) === '' || (int) $jadwal->jumlah_pemakaian < 1) {
                        continue;
                    }

                    $stokIds = DB::table('stok_onderdil')
                        ->where('nama_sparepart', $jadwal->nama_onderdil)
                        ->limit(2)
                        ->pluck('id');

                    DB::table('pemakaian_onderdil_pemeliharaan')
                        ->insert([
                            'jadwal_pemeliharaan_id' => $jadwal->id,
                            'stok_onderdil_id' => $stokIds->count() === 1 ? $stokIds->first() : null,
                            'nama_sparepart_snapshot' => $jadwal->nama_onderdil,
                            'jumlah' => (int) $jadwal->jumlah_pemakaian,
                            'created_at' => $jadwal->created_at,
                            'updated_at' => $jadwal->updated_at,
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemakaian_onderdil_pemeliharaan');
    }
};