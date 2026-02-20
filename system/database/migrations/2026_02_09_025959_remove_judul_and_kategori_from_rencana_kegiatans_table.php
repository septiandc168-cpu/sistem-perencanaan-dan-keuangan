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
            $table->dropColumn('judul');
            $table->dropColumn('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->string('judul')->nullable();
            $table->string('kategori')->nullable();
        });
    }
};
