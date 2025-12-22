<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            if (Schema::hasColumn('rencana_kegiatans', 'nama_pelapor')) {
                $table->dropColumn('nama_pelapor');
            }
            if (Schema::hasColumn('rencana_kegiatans', 'no_hp')) {
                $table->dropColumn('no_hp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->string('nama_pelapor')->nullable()->after('id');
            $table->string('no_hp')->nullable()->after('nama_pelapor');
        });
    }
};
