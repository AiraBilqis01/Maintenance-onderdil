
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_pemeliharaan', function (Blueprint $table) {
            $table->date('tanggal_target_awal')->nullable();
            $table->dateTime('tanggal_kirim_hasil')->nullable();
            $table->dateTime('tanggal_verifikasi')->nullable();
        });

        Schema::table('jadwal_pengecekan_onderdil', function (Blueprint $table) {
            $table->date('tanggal_target_awal')->nullable();
            $table->dateTime('tanggal_kirim_hasil')->nullable();
            $table->dateTime('tanggal_verifikasi')->nullable();
        });

        // Catat target yang saat ini tersedia sebagai baseline untuk jadwal lama.
        DB::table('jadwal_pemeliharaan')->update([
            'tanggal_target_awal' => DB::raw('tanggal_selesai')
        ]);

        DB::table('jadwal_pengecekan_onderdil')->update([
            'tanggal_target_awal' => DB::raw('tanggal_pengecekan')
        ]);
    }

    public function down(): void
    {
        Schema::table('jadwal_pemeliharaan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_target_awal', 'tanggal_kirim_hasil', 'tanggal_verifikasi']);
        });

        Schema::table('jadwal_pengecekan_onderdil', function (Blueprint $table) {
            $table->dropColumn(['tanggal_target_awal', 'tanggal_kirim_hasil', 'tanggal_verifikasi']);
        });
    }
};
