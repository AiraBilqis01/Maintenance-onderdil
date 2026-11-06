<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /* Mengubah kolom quantity agar menerima NULL. */
    public function up(): void
    {
        Schema::table('jadwal_pemeliharaan', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->change();
        });
    }

    /* Mengembalikan perubahan jika diperlukan. */
    public function down(): void
    {
        // Cegah rollback jika sudah ada data NULL.
        if (DB::table('jadwal_pemeliharaan')
            ->whereNull('quantity')->exists()) {
            throw new \RuntimeException(
                'Rollback dibatalkan: masih ada quantity bernilai NULL.'
            );
        }

        Schema::table('jadwal_pemeliharaan', function (Blueprint $table) {
            $table->integer('quantity')->nullable(false)->change();
        });
    }
};
