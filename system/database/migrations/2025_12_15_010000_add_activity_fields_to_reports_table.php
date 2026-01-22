<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->string('nama_kegiatan')->nullable()->after('nama_pelapor');
            $table->string('jenis_kegiatan')->nullable()->after('nama_kegiatan');
            $table->text('tujuan')->nullable()->after('deskripsi');
            $table->string('desa')->nullable()->after('lng');
            $table->date('tanggal_mulai')->nullable()->after('desa');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->string('penanggung_jawab')->nullable()->after('tanggal_selesai');
            $table->string('kelompok')->nullable()->after('penanggung_jawab');
            $table->integer('estimasi_peserta')->nullable()->after('kelompok');
            $table->decimal('estimasi_anggaran', 14, 2)->nullable()->after('estimasi_peserta');
            $table->string('dokumen')->nullable()->after('foto');
            $table->enum('status', ['direncanakan', 'sedang berlangsung', 'selesai', 'dibatalkan'])->default('direncanakan')->after('dokumen');
        });
    }

    public function down(): void
    {
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->dropColumn([
                'nama_kegiatan',
                'jenis_kegiatan',
                'tujuan',
                'desa',
                'tanggal_mulai',
                'tanggal_selesai',
                'penanggung_jawab',
                'kelompok',
                'estimasi_peserta',
                'estimasi_anggaran',
                'dokumen',
                'status'
            ]);
        });
    }
};
