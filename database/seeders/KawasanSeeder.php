<?php

namespace Database\Seeders;

use App\Models\Kawasan;
use Illuminate\Database\Seeder;

class KawasanSeeder extends Seeder
{
    public function run(): void
    {
        // 12 kawasan lintas kecamatan Kota Bandung, jumlah_kk 150-350 sesuai
        // skala nyata RW (rata-rata 2,5 kg sampah per KK per hari). KWS-001
        // s.d. KWS-004 dipertahankan sama persis dengan sebelumnya (kode,
        // kelurahan, kecamatan) karena UserSeeder mengacu ke kode-kode ini
        // untuk operator/warga yang sudah ada.
        $data = [
            [
                'kode_kawasan' => 'KWS-001',
                'nama_rw' => 'RW 03',
                'kelurahan' => 'Cihapit',
                'kecamatan' => 'Bandung Wetan',
                'jumlah_kk' => 180,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-002',
                'nama_rw' => 'RW 07',
                'kelurahan' => 'Sukamiskin',
                'kecamatan' => 'Arcamanik',
                'jumlah_kk' => 240,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-003',
                'nama_rw' => 'RW 05',
                'kelurahan' => 'Antapani Tengah',
                'kecamatan' => 'Antapani',
                'jumlah_kk' => 200,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-004',
                'nama_rw' => 'RW 02',
                'kelurahan' => 'Turangga',
                'kecamatan' => 'Lengkong',
                'jumlah_kk' => 165,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-005',
                'nama_rw' => 'RW 04',
                'kelurahan' => 'Dago',
                'kecamatan' => 'Coblong',
                'jumlah_kk' => 300,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-006',
                'nama_rw' => 'RW 06',
                'kelurahan' => 'Sukagalih',
                'kecamatan' => 'Sukajadi',
                'jumlah_kk' => 220,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-007',
                'nama_rw' => 'RW 02',
                'kelurahan' => 'Arjuna',
                'kecamatan' => 'Cicendo',
                'jumlah_kk' => 190,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-008',
                'nama_rw' => 'RW 08',
                'kelurahan' => 'Balonggede',
                'kecamatan' => 'Regol',
                'jumlah_kk' => 155,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-009',
                'nama_rw' => 'RW 03',
                'kelurahan' => 'Babakan Sari',
                'kecamatan' => 'Kiaracondong',
                'jumlah_kk' => 310,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-010',
                'nama_rw' => 'RW 05',
                'kelurahan' => 'Margasari',
                'kecamatan' => 'Buahbatu',
                'jumlah_kk' => 250,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-011',
                'nama_rw' => 'RW 07',
                'kelurahan' => 'Pasirjati',
                'kecamatan' => 'Ujungberung',
                'jumlah_kk' => 175,
                'status_siaga' => 'aman',
            ],
            [
                'kode_kawasan' => 'KWS-012',
                'nama_rw' => 'RW 04',
                'kelurahan' => 'Cipadung',
                'kecamatan' => 'Cibiru',
                'jumlah_kk' => 210,
                'status_siaga' => 'aman',
            ],
        ];

        foreach ($data as $kawasan) {
            Kawasan::create($kawasan);
        }
    }
}
