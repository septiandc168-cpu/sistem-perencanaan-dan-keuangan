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
        // Check if uuid column already exists
        if (!Schema::hasColumn('rencana_kegiatans', 'uuid')) {
            Schema::table('rencana_kegiatans', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });

            // Generate UUID for existing records
            \DB::table('rencana_kegiatans')->whereNull('uuid')->update([
                'uuid' => \DB::raw('(UUID())')
            ]);

            // Now make it unique and add index
            Schema::table('rencana_kegiatans', function (Blueprint $table) {
                $table->unique('uuid');
                $table->index('uuid');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_kegiatans', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
