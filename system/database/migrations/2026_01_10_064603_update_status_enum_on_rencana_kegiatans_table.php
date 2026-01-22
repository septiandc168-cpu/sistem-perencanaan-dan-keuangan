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
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'selesai'])
                ->default('diajukan')
                ->change();
        });
        
        // Update existing data
        DB::table('rencana_kegiatans')
            ->where('status', 'disetujui dan akan dilaksanakan')
            ->update(['status' => 'disetujui']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->enum('status', ['diajukan', 'disetujui dan sedang berlangsung', 'selesai'])
                ->default('diajukan')
                ->change();
        });
    }
};
