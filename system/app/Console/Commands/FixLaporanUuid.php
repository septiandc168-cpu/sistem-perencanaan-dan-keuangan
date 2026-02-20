<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LaporanKegiatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FixLaporanUuid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-laporan-uuid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing UUIDs in laporan_kegiatans table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for laporan kegiatan without UUID...');
        
        // Find laporan without UUID or with empty UUID
        $laporansWithoutUuid = LaporanKegiatan::where(function($query) {
            $query->whereNull('uuid')
                  ->orWhere('uuid', '');
        })->get();
        
        if ($laporansWithoutUuid->count() === 0) {
            $this->info('All laporan kegiatan have UUIDs. Nothing to fix.');
            return;
        }
        
        $this->info("Found {$laporansWithoutUuid->count()} laporan kegiatan without UUID.");
        $this->info('Fixing UUIDs...');
        
        foreach ($laporansWithoutUuid as $laporan) {
            $laporan->uuid = Str::uuid();
            $laporan->save();
            $this->line("Updated UUID for laporan ID: {$laporan->id}");
        }
        
        $this->info('UUID fix completed successfully!');
    }
}
