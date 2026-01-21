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
        // 1. Lepas foreign key user_id (ke tabel users)
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        // 2. Drop tabel pegawais
        Schema::dropIfExists('pegawais');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Buat ulang tabel pegawais
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_pegawai');
            $table->string('nik');
            $table->string('alamat');
            $table->integer('umur');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }
};
