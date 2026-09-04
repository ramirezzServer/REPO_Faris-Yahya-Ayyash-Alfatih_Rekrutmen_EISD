<?php

namespace Database\Seeders;

use App\Models\JalurPengolahan;
use Illuminate\Database\Seeder;

class JalurPengolahanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Maggot BSF',
                'kategori' => 'organik',
                'faktor_emisi_co2' => 0.35,
                'deskripsi' => 'Pengolahan sampah organik dengan larva Black Soldier Fly menjadi maggot dan kasgot.',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Komposting',
                'kategori' => 'organik',
                'faktor_emisi_co2' => 0.25,
                'deskripsi' => 'Penguraian sampah organik menjadi kompos melalui proses aerobik.',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Bank Sampah',
                'kategori' => 'anorganik',
                'faktor_emisi_co2' => 0.80,
                'deskripsi' => 'Pemilahan dan penimbangan sampah anorganik bernilai ekonomi untuk disetorkan.',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Pakan Ternak',
                'kategori' => 'organik',
                'faktor_emisi_co2' => 0.45,
                'deskripsi' => 'Pemanfaatan sisa sayur dan makanan sebagai pakan ternak.',
                'is_aktif' => true,
            ],
            [
                'nama' => 'TPS3R',
                'kategori' => 'anorganik',
                'faktor_emisi_co2' => 1.20,
                'deskripsi' => 'Tempat Pengolahan Sampah Reduce-Reuse-Recycle tingkat kawasan.',
                'is_aktif' => true,
            ],
            [
                'nama' => 'Daur Ulang Plastik',
                'kategori' => 'anorganik',
                'faktor_emisi_co2' => 1.50,
                'deskripsi' => 'Pencacahan dan pelelehan plastik menjadi bahan baku produk baru.',
                'is_aktif' => true,
            ],
        ];

        foreach ($data as $jalur) {
            JalurPengolahan::create($jalur);
        }
    }
}
