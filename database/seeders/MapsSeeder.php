<?php

namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class MapsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // create 10 sample rencana kegiatan
        for ($i = 0; $i < 10; $i++) {
            Report::create([
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
                'estimasi_anggaran' => $faker->numberBetween(1000000, 50000000),
                'lat' => $faker->latitude(-8.9, -6.5),
                'lng' => $faker->longitude(107.0, 110.0),
                'status' => $faker->randomElement(['direncanakan', 'sedang berlangsung', 'selesai']),
            ]);
        }
    }
}
