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
        Schema::table('rencana_kegiatans', function (Blueprint $table) {});

        DB::statement("
            ALTER TABLE rencana_kegiatans
            MODIFY dokumen JSON NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('json_on_rencana_kegiatans', function (Blueprint $table) {
            //
        });

        DB::statement("
            ALTER TABLE rencana_kegiatans
            MODIFY dokumen VARCHAR(255) NULL
        ");
    }
};
