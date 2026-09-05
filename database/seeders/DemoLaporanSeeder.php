<?php

namespace Database\Seeders;

use App\Models\JalurPengolahan;
use App\Models\Kawasan;
use App\Models\LaporanNeraca;
use App\Models\LaporanTumpukan;
use App\Models\PeriodeKuota;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Data demo berskala mendekati nyata Kota Bandung: timbulan sampah kota
 * sekitar 1.500 ton/hari dengan kuota kirim ke TPA Sarimukti sekitar
 * 980 ton/hari, diturunkan ke tingkat RW (150-350 KK, ~2,5 kg/KK/hari).
 *
 * 12 kawasan, masing-masing diberi 30 hari laporan neraca harian dan satu
 * periode kuota aktif. Tiga kelompok tren rasio olahan (membaik/stagnan/
 * memburuk) dipakai supaya grafik tren kota punya bentuk yang bervariasi.
 */
class DemoLaporanSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('tumpukan');

        $hariIni = Carbon::today();

        $daftarKawasan = Kawasan::orderBy('kode_kawasan')->get()->keyBy('kode_kawasan');
        $jalurId = JalurPengolahan::pluck('id', 'nama');
        $jalurFaktor = JalurPengolahan::pluck('faktor_emisi_co2', 'nama');

        // Kawasan yang jendela laporannya sengaja berakhir KEMARIN (bukan
        // hari ini), dengan 3 hari terakhirnya berstatus menunggu. 8 kawasan
        // lain melapor sampai HARI INI, supaya grafik tren kota tetap penuh
        // sampai ujung kanan (tidak seolah-olah datanya berhenti/rusak),
        // sementara operator dari 4 kawasan ini tetap bisa memperagakan
        // banner "belum lapor hari ini" di dashboard operator.
        $kawasanMenunggu = ['KWS-001', 'KWS-002', 'KWS-003', 'KWS-007'];

        // Konfigurasi tiap kawasan: kelompok tren rasio olahan sepanjang 30
        // hari, dan pengali kuota (K) pada rumus
        // kuota_residu_kg = jumlah_kk x 2,5 x 30 x K, dibulatkan ke ratusan.
        //
        // K mewakili PENILAIAN AWAL PERIODE atas kepadatan & kelengkapan
        // infrastruktur pengolahan kawasan (K besar = kepadatan tinggi/
        // pengolahan masih terbatas -> kuota diberi longgar; K kecil =
        // fasilitas dinilai sudah memadai -> kuota diberi ketat) — BUKAN
        // penghargaan/hukuman atas kinerja. Yang benar-benar menentukan
        // status_siaga akhir (lewat hitungStatusSiaga()) adalah bagaimana
        // kinerja kawasan itu berubah sepanjang periode (kelompok tren),
        // relatif terhadap alokasi awal tsb. Tabel K ini adalah titik awal,
        // bukan jaminan pasti — sebaran akhir diverifikasi setelah seeding.
        $konfigKawasan = [
            'KWS-001' => ['tren' => 'membaik', 'k' => 1.05, 'operator' => 'operator1@sinerka.test'],
            'KWS-002' => ['tren' => 'membaik', 'k' => 1.05, 'operator' => 'operator2@sinerka.test'],
            'KWS-003' => ['tren' => 'stagnan', 'k' => 0.85, 'operator' => 'operator3@sinerka.test'],
            'KWS-004' => ['tren' => 'memburuk', 'k' => 0.65, 'operator' => null],
            'KWS-005' => ['tren' => 'membaik', 'k' => 1.05, 'operator' => null],
            'KWS-006' => ['tren' => 'membaik', 'k' => 1.05, 'operator' => null],
            'KWS-007' => ['tren' => 'stagnan', 'k' => 0.85, 'operator' => null],
            'KWS-008' => ['tren' => 'stagnan', 'k' => 0.85, 'operator' => null],
            'KWS-009' => ['tren' => 'memburuk', 'k' => 0.65, 'operator' => null],
            'KWS-010' => ['tren' => 'stagnan', 'k' => 1.10, 'operator' => null],
            'KWS-011' => ['tren' => 'memburuk', 'k' => 0.65, 'operator' => null],
            'KWS-012' => ['tren' => 'memburuk', 'k' => 0.85, 'operator' => null],
        ];

        // Alasan tekstual per kawasan (dipisah dari konfigurasi numerik di
        // atas supaya tabel K tetap ringkas dibaca), dipakai sebagai
        // komentar inline saat membuat periode kuota masing-masing kawasan.
        $alasanKuota = [
            'membaik' => 'Kepadatan tinggi & pengolahan masih terbatas di awal periode -> kuota diberi longgar. '
                . 'Kinerja pengolahan justru membaik dari sekitar 30%% ke 65%% sepanjang periode, sehingga '
                . 'pemakaian kuota tetap terkendali -> berakhir aman.',
            'stagnan' => 'Kondisi awal menengah, infrastruktur pengolahan dinilai cukup memadai -> kuota '
                . 'diberi menengah. Kinerja pengolahan stagnan di kisaran 40%% sepanjang periode, sehingga '
                . 'pemakaian kuota mendekati batas -> berakhir waspada.',
            'memburuk' => 'Fasilitas pengolahan dinilai sudah memadai di awal periode -> kuota diberi ketat. '
                . 'Kinerja pengolahan justru menurun dari sekitar 55%% ke 25%% sepanjang periode, sehingga kuota '
                . 'terpakai jauh lebih cepat dari perkiraan -> berakhir kritis (atau waspada bila margin '
                . 'kuotanya masih menengah, lihat kawasan dengan k=0.85 di kelompok ini).',
        ];

        // --- Operator & warga: akun lama (dari UserSeeder) dipakai ulang,
        // sisanya dibuat baru di sini karena UserSeeder tidak boleh disentuh. ---
        $operatorLama = [
            'KWS-001' => 'operator1@sinerka.test',
            'KWS-002' => 'operator2@sinerka.test',
            'KWS-003' => 'operator3@sinerka.test',
        ];
        $wargaLama = [
            'KWS-001' => 'warga1@sinerka.test',
            'KWS-002' => 'warga3@sinerka.test',
            'KWS-004' => 'warga2@sinerka.test',
        ];

        $operatorId = [];
        $wargaId = [];

        foreach ($konfigKawasan as $kode => $cfg) {
            $kawasan = $daftarKawasan[$kode];
            $nomor = (int) substr($kode, 4);

            $operatorId[$kode] = isset($operatorLama[$kode])
                ? User::where('email', $operatorLama[$kode])->value('id')
                : User::create([
                    'name' => "Operator {$kawasan->kelurahan}",
                    'email' => sprintf('operator.kws%03d@sinerka.test', $nomor),
                    'no_hp' => sprintf('0813%08d', $nomor),
                    'role' => 'operator',
                    'kawasan_id' => $kawasan->id,
                    'password' => Hash::make('password'),
                ])->id;

            $wargaId[$kode] = isset($wargaLama[$kode])
                ? User::where('email', $wargaLama[$kode])->value('id')
                : User::create([
                    'name' => "Warga {$kawasan->kelurahan}",
                    'email' => sprintf('warga.kws%03d@sinerka.test', $nomor),
                    'no_hp' => sprintf('0814%08d', $nomor),
                    'role' => 'warga',
                    'kawasan_id' => $kawasan->id,
                    'password' => Hash::make('password'),
                ])->id;
        }

        // --- Periode kuota + 30 hari laporan neraca per kawasan. ---
        foreach ($konfigKawasan as $kode => $cfg) {
            $kawasan = $daftarKawasan[$kode];
            $offset = in_array($kode, $kawasanMenunggu, true) ? 1 : 0;

            $periode = PeriodeKuota::create([
                'kawasan_id' => $kawasan->id,
                'tanggal_mulai' => $hariIni->copy()->subDays(30)->toDateString(),
                'tanggal_selesai' => $hariIni->copy()->addDays(30)->toDateString(),
                'kuota_residu_kg' => round($kawasan->jumlah_kk * 2.5 * 30 * $cfg['k'] / 100) * 100,
                'status' => 'aktif',
                // Lihat komentar $alasanKuota di atas run() untuk rasionalisasi K.
            ]);

            for ($hari = 1; $hari <= 30; $hari++) {
                $tanggal = $hariIni->copy()->subDays($offset + 30 - $hari);

                $timbulan = $this->hitungTimbulan($kawasan->jumlah_kk, $tanggal);
                $rasio = $this->hitungRasio($cfg['tren'], $hari);
                $totalDiolah = round($timbulan * $rasio, 2);
                $residu = round($timbulan - $totalDiolah, 2);

                $status = 'terverifikasi';
                $catatanLaporan = null;

                // 3 hari terakhir untuk 4 kawasan yang jendelanya berakhir
                // kemarin -> antrean verifikasi admin tidak kosong.
                if ($offset === 1 && $hari >= 28) {
                    $status = 'menunggu';
                }

                // 2 laporan ditolak, menggantikan (bukan menambah) slot hari
                // yang sudah ada, supaya tidak melanggar unique(kawasan_id,
                // tanggal_laporan).
                if (($kode === 'KWS-005' && $hari === 10) || ($kode === 'KWS-008' && $hari === 18)) {
                    $status = 'ditolak';
                    $catatanLaporan = 'Data tonase tidak sesuai bukti timbang, mohon diperbaiki dan lapor ulang.';
                }

                $laporan = LaporanNeraca::create([
                    'kawasan_id' => $kawasan->id,
                    'operator_id' => $operatorId[$kode],
                    'periode_kuota_id' => $periode->id,
                    'tanggal_laporan' => $tanggal->toDateString(),
                    'timbulan_kg' => $timbulan,
                    'total_diolah_kg' => $totalDiolah,
                    'residu_kg' => $residu,
                    'status' => $status,
                    'catatan' => $catatanLaporan,
                ]);

                foreach ($this->hitungUraian($cfg['tren'], $totalDiolah) as $namaJalur => $tonase) {
                    $laporan->jalurPengolahan()->attach($jalurId[$namaJalur], [
                        'tonase_kg' => $tonase,
                        'faktor_emisi_saat_lapor' => $jalurFaktor[$namaJalur],
                        'keterangan' => null,
                    ]);
                }
            }

            $kawasan->status_siaga = $kawasan->hitungStatusSiaga();
            $kawasan->save();
        }

        $this->seedTumpukan($wargaId, $daftarKawasan->pluck('id', 'kode_kawasan')->all());
    }

    /**
     * Rasio total_diolah_kg/timbulan_kg pada hari ke-$hari (1..30) dari 30
     * hari sebuah kawasan, sesuai kelompok trennya.
     */
    private function hitungRasio(string $tren, int $hari): float
    {
        $progres = ($hari - 1) / 29; // 0 di hari pertama, 1 di hari ke-30

        $rasio = match ($tren) {
            'membaik' => 0.30 + 0.35 * $progres + $this->acak(-0.03, 0.03),
            'stagnan' => 0.40 + $this->acak(-0.05, 0.05),
            'memburuk' => 0.55 - 0.30 * $progres + $this->acak(-0.03, 0.03),
        };

        return max(0.05, min(0.95, $rasio));
    }

    /**
     * Timbulan harian = jumlah_kk x 2,5 kg x variasi acak 0,85-1,15, dengan
     * akhir pekan sedikit lebih tinggi (+5% s.d. +10% tambahan).
     */
    private function hitungTimbulan(int $jumlahKk, Carbon $tanggal): float
    {
        $timbulan = $jumlahKk * 2.5 * $this->acak(0.85, 1.15);

        if ($tanggal->isWeekend()) {
            $timbulan *= $this->acak(1.05, 1.10);
        }

        return round($timbulan, 2);
    }

    /**
     * Proporsi dasar tonase per jalur pengolahan, per kelompok tren. Maggot
     * & komposting dominan di kawasan membaik; kawasan memburuk hanya
     * memakai jalur yang lebih sederhana (bank sampah dominan).
     *
     * @return array<string, float>
     */
    private function proporsiJalur(string $tren): array
    {
        return match ($tren) {
            'membaik' => [
                'Maggot BSF' => 0.35,
                'Komposting' => 0.30,
                'Bank Sampah' => 0.15,
                'Pakan Ternak' => 0.10,
                'TPS3R' => 0.05,
                'Daur Ulang Plastik' => 0.05,
            ],
            'stagnan' => [
                'Maggot BSF' => 0.20,
                'Komposting' => 0.20,
                'Bank Sampah' => 0.25,
                'Pakan Ternak' => 0.15,
                'TPS3R' => 0.10,
                'Daur Ulang Plastik' => 0.10,
            ],
            'memburuk' => [
                'Bank Sampah' => 0.60,
                'Komposting' => 0.25,
                'Maggot BSF' => 0.15,
            ],
        };
    }

    /**
     * Sebar $totalDiolah ke jalur-jalur pengolahan kelompok tren $tren
     * (bobot dasar + goyangan acak, jalur bertonase <1kg dibuang). Sisa
     * pembulatan/pembuangan dilimpahkan ke jalur bertonase terbesar supaya
     * total uraian selalu persis sama dengan total_diolah_kg (konsisten
     * dengan NeracaStat::totalTerolah() yang menjumlahkan dari sini).
     *
     * @return array<string, float>
     */
    private function hitungUraian(string $tren, float $totalDiolah): array
    {
        $bobot = [];

        foreach ($this->proporsiJalur($tren) as $nama => $bobotDasar) {
            $bobot[$nama] = $bobotDasar * $this->acak(0.8, 1.2);
        }

        $totalBobot = array_sum($bobot);
        $uraian = [];

        foreach ($bobot as $nama => $w) {
            $tonase = round($totalDiolah * $w / $totalBobot, 2);

            if ($tonase >= 1) {
                $uraian[$nama] = $tonase;
            }
        }

        if ($uraian === []) {
            return [];
        }

        arsort($uraian);
        $namaTerbesar = array_key_first($uraian);
        $selisih = round($totalDiolah - array_sum($uraian), 2);
        $uraian[$namaTerbesar] = round($uraian[$namaTerbesar] + $selisih, 2);

        return $uraian;
    }

    private function acak(float $min, float $max): float
    {
        return $min + mt_rand(0, 1000000) / 1000000 * ($max - $min);
    }

    /**
     * 12 laporan tumpukan liar berstatus beragam, tersebar di 8 kawasan,
     * memakai campuran warga lama & baru.
     *
     * @param  array<string, int>  $wargaId  kode_kawasan => user id
     * @param  array<string, int>  $kawasanId  kode_kawasan => kawasan id
     */
    private function seedTumpukan(array $wargaId, array $kawasanId): void
    {
        $tumpukan = [
            [$wargaId['KWS-001'], $kawasanId['KWS-001'], 'Gang belakang pos ronda RW 03, Cihapit',
                'Tumpukan kantong plastik bercampur sisa makanan menumpuk di sudut gang sejak beberapa hari dan mulai berbau.',
                'baru', null],
            [$wargaId['KWS-001'], $kawasanId['KWS-001'], 'Trotoar depan Taman Cihapit',
                'Beberapa karung sampah dibuang sembarangan di trotoar, mengganggu pejalan kaki.',
                'diproses', 'Sedang dikoordinasikan dengan RW setempat.'],
            [$wargaId['KWS-003'], $kawasanId['KWS-003'], 'Saluran air belakang Pasar Antapani',
                'Sampah menyumbat saluran air kecil dan berpotensi menyebabkan genangan saat hujan.',
                'selesai', 'Sudah dibersihkan oleh petugas kebersihan RW.'],
            [$wargaId['KWS-004'], $kawasanId['KWS-004'], 'Sudut parkir Jl. Turangga Timur',
                'Tumpukan puing dan sampah rumah tangga dibiarkan menumpuk di sudut area parkir umum.',
                'baru', null],
            [$wargaId['KWS-004'], $kawasanId['KWS-004'], 'Bantaran got Jl. Turangga Selatan',
                'Sampah plastik menumpuk di bantaran got, berisiko menyumbat aliran air.',
                'diproses', 'Sedang dikoordinasikan dengan RW setempat.'],
            [$wargaId['KWS-005'], $kawasanId['KWS-005'], 'Tikungan Jl. Dago Pojok dekat warung',
                'Sampah wisatawan dan pedagang menumpuk di tikungan jalan, mengganggu pemandangan dan lalu lintas.',
                'baru', null],
            [$wargaId['KWS-007'], $kawasanId['KWS-007'], 'Trotoar Jl. Arjuna Utara',
                'Tumpukan sampah rumah tangga dibiarkan berhari-hari di pinggir trotoar.',
                'selesai', 'Sudah dibersihkan oleh petugas kebersihan RW.'],
            [$wargaId['KWS-009'], $kawasanId['KWS-009'], 'Lahan kosong belakang Terminal Kiaracondong',
                'Sampah campuran dibuang liar di lahan kosong dekat terminal, mulai dibakar warga sekitar.',
                'baru', null],
            [$wargaId['KWS-009'], $kawasanId['KWS-009'], 'Pinggir rel Jl. Babakan Sari Indah',
                'Tumpukan sampah menumpuk di sepanjang pinggir rel kereta, berpotensi bahaya kebakaran.',
                'diproses', 'Sedang dikoordinasikan dengan RW setempat.'],
            [$wargaId['KWS-010'], $kawasanId['KWS-010'], 'Sekitar kolam retensi Margasari',
                'Sampah plastik mengapung di kolam retensi, berisiko menyumbat saluran drainase kawasan.',
                'selesai', 'Sudah dibersihkan oleh petugas kebersihan RW.'],
            [$wargaId['KWS-012'], $kawasanId['KWS-012'], 'Jalan masuk Komplek Cipadung Permai',
                'Tumpukan sampah menghalangi jalan masuk komplek, dikeluhkan warga dan tamu.',
                'diproses', 'Sedang dikoordinasikan dengan RW setempat.'],
            [$wargaId['KWS-012'], $kawasanId['KWS-012'], 'Gang sempit dekat Masjid Cipadung',
                'Sampah rumah tangga menumpuk di gang sempit dekat masjid, mengganggu jemaah yang lewat.',
                'baru', null],
        ];

        foreach ($tumpukan as $i => [$userId, $kawasanIdItem, $lokasi, $deskripsi, $status, $catatan]) {
            $path = 'tumpukan/demo-' . ($i + 1) . '.png';

            if (function_exists('imagecreatetruecolor')) {
                $img = imagecreatetruecolor(480, 320);
                $warna = imagecolorallocate($img, 90 + ($i * 13) % 140, 120 + ($i * 7) % 100, 100 + ($i * 11) % 120);
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
                'kawasan_id' => $kawasanIdItem,
                'lokasi' => $lokasi,
                'deskripsi' => $deskripsi,
                'foto' => $path,
                'status' => $status,
                'catatan_tindak_lanjut' => $catatan,
            ]);
        }
    }
}
