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
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            // tambah kolom baru
            $table->longText('rincian_kebutuhan')->nullable()->after('estimasi_anggaran');

            // hapus kolom lama
            $table->dropColumn('estimasi_anggaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            // kembalikan kolom lama
            $table->decimal('estimasi_anggaran', 15, 2)->nullable()->after('rincian_kebutuhan');

            // hapus kolom baru
            $table->dropColumn('rincian_kebutuhan');
        });
    }
};
