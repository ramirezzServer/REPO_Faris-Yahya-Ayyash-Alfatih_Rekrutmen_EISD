<?php

namespace Database\Seeders;

use App\Models\JalurPengolahan;
use App\Models\Kawasan;
use App\Models\LaporanNeraca;
use App\Models\LaporanTumpukan;
use App\Models\PeriodeKuota;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DemoLaporanSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('tumpukan');

        $kawasan = Kawasan::pluck('id', 'kode_kawasan');
        $jalurId = JalurPengolahan::pluck('id', 'nama');
        $jalurFaktor = JalurPengolahan::pluck('faktor_emisi_co2', 'nama');

        // Target agregat per kawasan:
        //   KWS-001: terolah 700 / residu 300  -> sisa 70%  -> aman
        //   KWS-002: terolah 300 / residu 700  -> sisa 30%  -> waspada
        //   KWS-003: terolah 100 / residu 900  -> sisa 10%  -> kritis
        $demo = [
            'KWS-001' => [
                'operator' => 'operator1@sinerka.test',
                'laporan' => [
                    ['2026-09-02', 500, [['Maggot BSF', 200, 'Pengolahan sisa dapur harian'], ['Komposting', 150, null]]],
                    ['2026-09-03', 500, [['Bank Sampah', 200, null], ['Daur Ulang Plastik', 150, null]]],
                ],
            ],
            'KWS-002' => [
                'operator' => 'operator2@sinerka.test',
                'laporan' => [
                    ['2026-09-02', 500, [['Maggot BSF', 100, null], ['Komposting', 50, null]]],
                    ['2026-09-03', 500, [['Bank Sampah', 100, null], ['TPS3R', 50, null]]],
                ],
            ],
            'KWS-003' => [
                'operator' => 'operator3@sinerka.test',
                'laporan' => [
                    ['2026-09-02', 500, [['Komposting', 30, null], ['Maggot BSF', 20, null]]],
                    ['2026-09-03', 500, [['Bank Sampah', 30, null], ['Pakan Ternak', 20, null]]],
                ],
            ],
        ];

        foreach ($demo as $kode => $cfg) {
            $kawasanId = $kawasan[$kode];
            $operatorId = User::where('email', $cfg['operator'])->value('id');

            $periode = PeriodeKuota::create([
                'kawasan_id' => $kawasanId,
                'tanggal_mulai' => '2026-09-01',
                'tanggal_selesai' => '2026-12-31',
                'kuota_residu_kg' => 1000,
                'status' => 'aktif',
            ]);

            foreach ($cfg['laporan'] as [$tanggal, $timbulan, $uraian]) {
                $totalDiolah = array_sum(array_column($uraian, 1));

                $laporan = LaporanNeraca::create([
                    'kawasan_id' => $kawasanId,
                    'operator_id' => $operatorId,
                    'periode_kuota_id' => $periode->id,
                    'tanggal_laporan' => $tanggal,
                    'timbulan_kg' => $timbulan,
                    'total_diolah_kg' => $totalDiolah,
                    'residu_kg' => $timbulan - $totalDiolah,
                    'status' => 'terverifikasi',
                ]);

                foreach ($uraian as [$namaJalur, $tonase, $keterangan]) {
                    $laporan->jalurPengolahan()->attach($jalurId[$namaJalur], [
                        'tonase_kg' => $tonase,
                        'faktor_emisi_saat_lapor' => $jalurFaktor[$namaJalur],
                        'keterangan' => $keterangan,
                    ]);
                }
            }

            $modelKawasan = Kawasan::find($kawasanId);
            $modelKawasan->status_siaga = $modelKawasan->hitungStatusSiaga();
            $modelKawasan->save();
        }

        $this->seedTumpukan($kawasan);
    }

    private function seedTumpukan($kawasan): void
    {
        $warga1 = User::where('email', 'warga1@sinerka.test')->value('id');
        $warga3 = User::where('email', 'warga3@sinerka.test')->value('id');

        $tumpukan = [
            [$warga1, $kawasan['KWS-001'], 'Gang belakang pos ronda RW 03, Cihapit',
                'Tumpukan kantong plastik bercampur sisa makanan menumpuk di sudut gang sejak beberapa hari dan mulai berbau.'],
            [$warga1, $kawasan['KWS-001'], 'Trotoar depan Taman Cihapit',
                'Beberapa karung sampah dibuang sembarangan di trotoar, mengganggu pejalan kaki.'],
            [$warga3, $kawasan['KWS-002'], 'Lahan kosong Jl. Sukamiskin Indah',
                'Warga membakar sampah campuran di lahan kosong, asapnya masuk ke rumah sekitar.'],
        ];

        foreach ($tumpukan as $i => [$userId, $kawasanId, $lokasi, $deskripsi]) {
            $path = 'tumpukan/demo-' . ($i + 1) . '.png';

            if (function_exists('imagecreatetruecolor')) {
                $img = imagecreatetruecolor(480, 320);
                $warna = imagecolorallocate($img, 110 + $i * 30, 135, 105);
                imagefilledrectangle($img, 0, 0, 479, 319, $warna);
                ob_start();
                imagepng($img);
                Storage::disk('public')->put($path, (string) ob_get_clean());
                imagedestroy($img);
            } else {
                $path = '';
            }

            LaporanTumpukan::create([
                'user_id' => $userId,
                'kawasan_id' => $kawasanId,
                'lokasi' => $lokasi,
                'deskripsi' => $deskripsi,
                'foto' => $path,
                'status' => 'baru',
            ]);
        }
    }
}
