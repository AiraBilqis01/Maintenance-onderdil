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
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pemeliharaan');
    }
};