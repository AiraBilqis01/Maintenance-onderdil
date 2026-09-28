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
        Schema::create('stok_onderdil', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sparepart');
            $table->string('gambar')->nullable();
            $table->integer('stok_periode_sebelum')->default(0);
            $table->date('periode');
            $table->text('kondisi_sekarang')->nullable();
            $table->integer('pemakaian')->default(0);
            $table->integer('stok_terbaru')->default(0);
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_onderdil');
    }
};