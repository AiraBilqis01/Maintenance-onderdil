<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pemeliharaan', function (Blueprint $table) {
            $table->id();
            $table->string('judul_pemeliharaan');
            $table->date('tanggal_selesai');
            $table->string('nama_tenaga_kerja');
            $table->string('nama_peralatan');
            $table->string('lokasi')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('serial_number')->nullable();
            $table->string('kapasitas')->nullable();
            $table->string('merek')->nullable();
            $table->string('tipe')->nullable();
            $table->year('tahun_pembuatan')->nullable();
            $table->string('gambar')->nullable();
            $table->text('keterangan')->nullable();

            // Kolom baru untuk penyelesaian
            $table->string('status')->default('pending');
            $table->string('nama_onderdil')->nullable();
            $table->text('detail_penyelesaian')->nullable();
            $table->string('gambar_bukti')->nullable();
            $table->text('keterangan_tolak')->nullable();
            $table->text('alasan_penolakan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pemeliharaan');
    }
};