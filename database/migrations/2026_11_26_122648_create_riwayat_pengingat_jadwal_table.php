<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /* Run the migrations. */
    public function up(): void
    {
        Schema::create('riwayat_pengingat_jadwal', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_jadwal', ['pemeliharaan', 'pengecekan']);
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal_target');
            $table->enum('jenis_pengingat', ['h_1', 'hari_h']);
            $table->timestamp('dikirim_pada')->nullable();
            $table->timestamps();

            // Mencegah pencatatan pengingat yang sama lebih dari sekali
            $table->unique([
                'jenis_jadwal',
                'jadwal_id',
                'user_id',
                'tanggal_target',
                'jenis_pengingat'
            ], 'unique_pengingat_jadwal');
        });
    }

    /* Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pengingat_jadwal');
    }
};
