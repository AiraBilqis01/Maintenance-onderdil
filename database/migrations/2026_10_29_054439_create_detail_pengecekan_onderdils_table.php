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
        Schema::create('detail_pengecekan_onderdil', function (Blueprint $table) {
            $table->id();

            // Relasi ke jadwal pengecekan onderdil
            $table->foreignId('jadwal_pengecekan_id')
                  ->constrained('jadwal_pengecekan_onderdil')
                  ->cascadeOnDelete();

            // Relasi ke data manajemen stok
            $table->foreignId('stok_onderdil_id')
                  ->nullable()
                  ->constrained('stok_onderdil')
                  ->nullOnDelete();

            // Nama sparepart saat jadwal dibuat
            // Disimpan supaya riwayat tetap ada
            // walaupun data stok suatu saat dihapus.
            $table->string('nama_sparepart_snapshot');

            // Stok sistem saat tenaga kerja melakukan pengecekan
            $table->integer('stok_sistem_saat_cek')->nullable();

            // Jumlah stok fisik hasil pengecekan tenaga kerja
            $table->integer('stok_fisik')->nullable();

            // Selisih antara stok fisik dan stok sistem
            $table->integer('selisih')->nullable();

            // Kondisi onderdil hasil pengecekan
            $table->string('kondisi')->nullable();

            // Catatan hasil pengecekan
            $table->text('catatan_pengecekan')->nullable();

            // Bukti foto pengecekan
            $table->string('gambar_bukti')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengecekan_onderdil');
    }
};
