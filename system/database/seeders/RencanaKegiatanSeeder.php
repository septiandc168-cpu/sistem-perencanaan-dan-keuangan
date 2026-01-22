<?php

namespace Database\Seeders;

use App\Models\RencanaKegiatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class RencanaKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // create 10 sample rencana kegiatan
        for ($i = 0; $i < 10; $i++) {
            RencanaKegiatan::create([
                'nama_kegiatan' => $faker->sentence(3),
                'jenis_kegiatan' => $faker->randomElement(['Sosialisasi', 'Pembersihan', 'Penanaman', 'Pelatihan']),
                'deskripsi' => $faker->paragraph(),
                'tujuan' => $faker->sentence(),
                'desa' => $faker->randomElement(['Desa A', 'Desa B', 'Desa C', 'Desa D']),
                'tanggal_mulai' => $faker->dateTimeBetween('-1 month', '+2 months')->format('Y-m-d'),
                'tanggal_selesai' => $faker->dateTimeBetween('+2 days', '+3 months')->format('Y-m-d'),
                'penanggung_jawab' => $faker->name(),
                'kelompok' => $faker->randomElement(['Masyarakat', 'Pelajar', 'Pemerintah Desa']),
                'estimasi_peserta' => $faker->numberBetween(10, 200),
                'rincian_kebutuhan' =>
"• Konsumsi kegiatan (" . $faker->numberBetween(40, 80) . " paket) = Rp" .
number_format($faker->numberBetween(800000, 2000000), 0, ',', '.') . "<br>" .

"• ATK (kertas, pulpen, map) = Rp" .
number_format($faker->numberBetween(300000, 600000), 0, ',', '.') . "<br>" .

"• Spanduk kegiatan = Rp" .
number_format($faker->numberBetween(150000, 300000), 0, ',', '.') . "<br>" .

"• Sewa perlengkapan = Rp" .
number_format($faker->numberBetween(700000, 1500000), 0, ',', '.') . "<br><br>" .

"<strong>Total perkiraan anggaran:</strong> Rp" .
number_format($faker->numberBetween(2000000, 4000000), 0, ',', '.'),
                'lat' => $faker->latitude(-8.9, -6.5),
                'lng' => $faker->longitude(107.0, 110.0),
                'status' => $faker->randomElement(['diajukan', 'disetujui dan akan dilaksanakan', 'ditolak', 'selesai']),
            ]);
        }
    }
}
