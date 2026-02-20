<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing records with UUID
        \DB::table('laporan_kegiatans')->whereNull('uuid')->update([
            'uuid' => \DB::raw('(UUID())')
        ]);
        
        // Also update any empty UUID strings
        \DB::table('laporan_kegiatans')->where('uuid', '')->update([
            'uuid' => \DB::raw('(UUID())')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse as this is just updating data
    }
};
