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
            // MySQL tidak bisa langsung ubah TEXT ke JSON tanpa raw SQL
        });

        DB::statement("
            ALTER TABLE rencana_kegiatans
            MODIFY foto JSON NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            // rollback ke TEXT
        });

        DB::statement("
            ALTER TABLE rencana_kegiatans
            MODIFY foto TEXT NULL
        ");
    }
};
