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
        // 1. Hapus foreign key di tabel pegawais
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropForeign(['bagian_id']);
        });

        // 2. (Opsional) Hapus kolom bagian_id
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn('bagian_id');
        });

        // 3. Baru hapus tabel bagians
        Schema::dropIfExists('bagians');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Buat ulang tabel bagians
        Schema::create('bagians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bagian');
            $table->timestamps();
        });

        // Tambahkan kembali kolom & foreign key
        Schema::table('pegawais', function (Blueprint $table) {
            $table->foreignId('bagian_id')
                ->nullable()
                ->constrained('bagians')
                ->nullOnDelete();
        });
    }
};
