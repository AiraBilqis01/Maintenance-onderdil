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
        Schema::table('detail_pengecekan_onderdil', function (Blueprint $table) {
            $table->string('penyebab_selisih')->nullable()->after('selisih');
            $table->text('keterangan_selisih')->nullable()->after('penyebab_selisih');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pengecekan_onderdil', function (Blueprint $table) {
            $table->dropColumn(['penyebab_selisih','keterangan_selisih',]);
        });
    }
};
