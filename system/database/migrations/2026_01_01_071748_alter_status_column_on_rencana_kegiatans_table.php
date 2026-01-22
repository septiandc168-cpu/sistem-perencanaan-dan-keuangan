<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * STEP 1
         * Longgarkan kolom status jadi STRING sementara
         * (agar bisa update data lama tanpa error ENUM)
         */
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->string('status')->change();
        });

        /**
         * STEP 2
         * Samakan data lama dengan ENUM baru
         */
        DB::table('rencana_kegiatans')
            ->where('status', 'disetujui dan sedang berlangsung')
            ->update([
                'status' => 'disetujui dan akan dilaksanakan'
            ]);

        /**
         * STEP 3
         * Ubah kembali kolom status menjadi ENUM baru
         */
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->enum('status', [
                'diajukan',
                'disetujui dan akan dilaksanakan',
                'ditolak',
                'selesai',
            ])->default('diajukan')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /**
         * Rollback ke ENUM lama
         */
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->enum('status', [
                'diajukan',
                'disetujui dan sedang berlangsung',
                'selesai',
            ])->default('diajukan')->change();
        });

        /**
         * Kembalikan data ke status lama
         */
        DB::table('rencana_kegiatans')
            ->where('status', 'disetujui dan akan dilaksanakan')
            ->update([
                'status' => 'disetujui dan sedang berlangsung'
            ]);
    }
};
