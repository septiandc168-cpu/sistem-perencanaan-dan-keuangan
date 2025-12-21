<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'nama_pelapor')) {
                $table->dropColumn('nama_pelapor');
            }
            if (Schema::hasColumn('reports', 'no_hp')) {
                $table->dropColumn('no_hp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('nama_pelapor')->nullable()->after('id');
            $table->string('no_hp')->nullable()->after('nama_pelapor');
        });
    }
};
