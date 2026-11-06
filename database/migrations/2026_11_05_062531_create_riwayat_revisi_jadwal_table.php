
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_revisi_jadwal', function (Blueprint $table) {
            $table->id();

            // Jenis jadwal: pemeliharaan atau pengecekan
            $table->string('jenis_jadwal', 30);

            // ID jadwal pada tabel asal
            $table->unsignedBigInteger('jadwal_id');

            // Tenggat sebelum dan sesudah revisi
            $table->date('tanggal_lama');
            $table->date('tanggal_baru');

            // Alasan perubahan tenggat
            $table->text('alasan_revisi');

            // Supervisor yang melakukan revisi
            $table->foreignId('supervisor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Waktu revisi
            $table->dateTime('direvisi_pada');

            // Kondisi keterlambatan sebelum revisi
            $table->boolean('terlambat_sebelum_revisi')
                ->default(false);

            $table->unsignedInteger('hari_terlambat_sebelum_revisi')
                ->default(0);

            $table->timestamps();

            $table->index(['jenis_jadwal', 'jadwal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_revisi_jadwal');
    }
};
