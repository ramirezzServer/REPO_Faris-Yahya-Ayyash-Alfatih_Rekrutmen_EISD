<?php

namespace Database\Seeders;

use App\Models\Kawasan;
use Illuminate\Database\Seeder;

class KawasanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_kawasan' => 'KWS-001',
                'nama_rw' => 'RW 03',
                'kelurahan' => 'Cihapit',
                'kecamatan' => 'Bandung Wetan',
                'jumlah_kk' => 118,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-002',
                'nama_rw' => 'RW 07',
                'kelurahan' => 'Sukamiskin',
                'kecamatan' => 'Arcamanik',
                'jumlah_kk' => 186,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-003',
                'nama_rw' => 'RW 05',
                'kelurahan' => 'Antapani Tengah',
                'kecamatan' => 'Antapani',
                'jumlah_kk' => 92,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-004',
                'nama_rw' => 'RW 02',
                'kelurahan' => 'Turangga',
                'kecamatan' => 'Lengkong',
                'jumlah_kk' => 154,
                'status_siaga' => 'aman',
            ],
        ];

        foreach ($data as $kawasan) {
            Kawasan::create($kawasan);
        }
    }
}
