<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE rencana_kegiatans 
            MODIFY status ENUM(
                'diajukan',
                'disetujui dan sedang berlangsung',
                'selesai'
            ) NOT NULL DEFAULT 'diajukan'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE rencana_kegiatans 
            MODIFY status ENUM(
                'direncanakan',
                'sedang berlangsung',
                'selesai'
            ) NOT NULL DEFAULT 'direncanakan'
        ");
    }
};
