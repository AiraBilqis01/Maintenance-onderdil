<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_pengecekan_onderdil', function (Blueprint $table) {
            $table->id();

            // Judul jadwal pengecekan
            $table->string('judul_pengecekan');

            // Tanggal pelaksanaan pengecekan
            $table->date('tanggal_pengecekan');

            // Tenaga kerja yang ditugaskan
            $table->foreignId('tenaga_kerja_id')
                  ->constrained('users');

            // Catatan tambahan dari supervisor
            $table->text('keterangan')->nullable();

            // Status alur pengecekan:
            // pending
            // menunggu_verifikasi
            // selesai
            // ditolak
            $table->string('status')->default('pending');

            // Digunakan jika hasil pengecekan ditolak supervisor
            $table->text('keterangan_tolak')->nullable();
            $table->text('alasan_penolakan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pengecekan_onderdil');
    }
};
