<?php

namespace Database\Seeders;

use App\Models\Bagian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BagianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bagian = ['produksi', 'pengadaan', 'keuangan', 'umum', 'k3', 'teknik', 'kantor'];

        foreach ($bagian as $item) {
            Bagian::create([
                'nama_bagian' => $item
            ]);
        }
    }
}
