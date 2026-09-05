<?php

namespace App\Support;

use App\Models\Kawasan;
use App\Models\LaporanNeraca;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Satu sumber perhitungan angka agregat neraca sampah kota.
 *
 * Semua method statis, tanpa cache — dihitung langsung dari basis data
 * setiap dipanggil. Dipakai oleh DashboardController::publik() (landing
 * page publik) DAN DashboardController::admin() (dashboard admin), supaya
 * tidak mungkin ada angka yang berbeda antar halaman.
 */
class NeracaStat
{
    public static function totalTimbulan(string $status = 'terverifikasi'): float
    {
        return (float) LaporanNeraca::where('status', $status)->sum('timbulan_kg');
    }

    public static function totalResidu(string $status = 'terverifikasi'): float
    {
        return (float) LaporanNeraca::where('status', $status)->sum('residu_kg');
    }

    public static function totalTerolah(string $status = 'terverifikasi'): float
    {
        return (float) DB::table('detail_pengolahan')
            ->join('laporan_neraca', 'laporan_neraca.id', '=', 'detail_pengolahan.laporan_neraca_id')
            ->where('laporan_neraca.status', $status)
            ->sum('detail_pengolahan.tonase_kg');
    }

    public static function totalEmisi(string $status = 'terverifikasi'): float
    {
        return (float) DB::table('detail_pengolahan')
            ->join('laporan_neraca', 'laporan_neraca.id', '=', 'detail_pengolahan.laporan_neraca_id')
            ->where('laporan_neraca.status', $status)
            ->selectRaw('COALESCE(SUM(detail_pengolahan.tonase_kg * detail_pengolahan.faktor_emisi_saat_lapor), 0) AS total')
            ->value('total');
    }

    public static function rasioKemandirian(string $status = 'terverifikasi'): float
    {
        $timbulan = self::totalTimbulan($status);
        $terolah = self::totalTerolah($status);

        return $timbulan > 0 ? $terolah / $timbulan * 100 : 0;
    }

    public static function ringkasanKawasan(string $status = 'terverifikasi'): Collection
    {
        $terpakaiSub = DB::table('laporan_neraca')
            ->selectRaw('periode_kuota_id, SUM(residu_kg) AS terpakai')
            ->where('status', $status)
            ->groupBy('periode_kuota_id');

        return DB::table('kawasan')
            ->leftJoin('periode_kuota', function ($join) {
                $join->on('periode_kuota.kawasan_id', '=', 'kawasan.id')
                    ->where('periode_kuota.status', '=', 'aktif');
            })
            ->leftJoinSub($terpakaiSub, 'rk', 'rk.periode_kuota_id', '=', 'periode_kuota.id')
            ->select([
                'kawasan.kode_kawasan',
                'kawasan.kelurahan',
                'kawasan.status_siaga',
                'periode_kuota.kuota_residu_kg',
                DB::raw('(periode_kuota.kuota_residu_kg - COALESCE(rk.terpakai, 0)) AS sisa_kuota'),
                DB::raw('CASE WHEN periode_kuota.kuota_residu_kg > 0
                        THEN (periode_kuota.kuota_residu_kg - COALESCE(rk.terpakai, 0)) / periode_kuota.kuota_residu_kg * 100
                        ELSE NULL END AS persentase_sisa'),
            ])
            ->orderByRaw("FIELD(kawasan.status_siaga, 'kritis', 'waspada', 'aman')")
            ->orderBy('kawasan.kode_kawasan')
            ->get();
    }

    /**
     * @return array{aman: int, waspada: int, kritis: int}
     */
    public static function jumlahPerStatus(): array
    {
        $hitung = Kawasan::selectRaw('status_siaga, COUNT(*) AS jumlah')
            ->groupBy('status_siaga')
            ->pluck('jumlah', 'status_siaga');

        return [
            'aman' => (int) ($hitung['aman'] ?? 0),
            'waspada' => (int) ($hitung['waspada'] ?? 0),
            'kritis' => (int) ($hitung['kritis'] ?? 0),
        ];
    }

    public static function kontribusiJalur(string $status = 'terverifikasi'): Collection
    {
        return DB::table('detail_pengolahan')
            ->join('laporan_neraca', 'laporan_neraca.id', '=', 'detail_pengolahan.laporan_neraca_id')
            ->join('jalur_pengolahan', 'jalur_pengolahan.id', '=', 'detail_pengolahan.jalur_pengolahan_id')
            ->where('laporan_neraca.status', $status)
            ->groupBy('jalur_pengolahan.id', 'jalur_pengolahan.nama')
            ->orderByDesc('total_tonase')
            ->get([
                'jalur_pengolahan.nama',
                DB::raw('SUM(detail_pengolahan.tonase_kg) AS total_tonase'),
            ]);
    }

    public static function peringkatKawasan(string $status = 'terverifikasi'): Collection
    {
        $timbulanSub = DB::table('laporan_neraca')
            ->selectRaw('kawasan_id, SUM(timbulan_kg) AS timbulan')
            ->where('status', $status)
            ->groupBy('kawasan_id');

        $terolahSub = DB::table('detail_pengolahan')
            ->join('laporan_neraca', 'laporan_neraca.id', '=', 'detail_pengolahan.laporan_neraca_id')
            ->where('laporan_neraca.status', $status)
            ->selectRaw('laporan_neraca.kawasan_id, SUM(detail_pengolahan.tonase_kg) AS terolah')
            ->groupBy('laporan_neraca.kawasan_id');

        return DB::table('kawasan')
            ->joinSub($timbulanSub, 't', 't.kawasan_id', '=', 'kawasan.id')
            ->leftJoinSub($terolahSub, 'o', 'o.kawasan_id', '=', 'kawasan.id')
            ->where('t.timbulan', '>', 0)
            ->orderByDesc('rasio')
            ->get([
                'kawasan.kode_kawasan',
                'kawasan.kelurahan',
                't.timbulan',
                DB::raw('COALESCE(o.terolah, 0) AS terolah'),
                DB::raw('COALESCE(o.terolah, 0) / t.timbulan * 100 AS rasio'),
            ]);
    }

    public static function adaLaporanTerverifikasi(string $status = 'terverifikasi'): bool
    {
        return LaporanNeraca::where('status', $status)->exists();
    }

    public static function jumlahLaporanMenunggu(): int
    {
        return LaporanNeraca::where('status', 'menunggu')->count();
    }
}
