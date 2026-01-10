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
        Schema::create('laporan_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->uuid('rencana_kegiatan_id')->unique();
            $table->longText('pelaksanaan_kegiatan');
            $table->longText('hasil_kegiatan');
            $table->longText('kendala')->nullable();
            $table->longText('evaluasi')->nullable();
            $table->json('dokumentasi')->nullable();
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('rencana_kegiatan_id')
                  ->references('uuid')
                  ->on('rencana_kegiatans')
                  ->onDelete('cascade');
            
            // Index untuk optimasi
            $table->index('rencana_kegiatan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kegiatans');
    }
};
