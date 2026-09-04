<?php

namespace App\Http\Controllers;

use App\Models\LaporanNeraca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function publik()
    {
        $status = 'terverifikasi';

        $totalTimbulan = (float) LaporanNeraca::where('status', $status)->sum('timbulan_kg');
        $totalResidu = (float) LaporanNeraca::where('status', $status)->sum('residu_kg');

        $totalTerolah = (float) DB::table('detail_pengolahan')
            ->join('laporan_neraca', 'laporan_neraca.id', '=', 'detail_pengolahan.laporan_neraca_id')
            ->where('laporan_neraca.status', $status)
            ->sum('detail_pengolahan.tonase_kg');

        $totalEmisi = (float) DB::table('detail_pengolahan')
            ->join('laporan_neraca', 'laporan_neraca.id', '=', 'detail_pengolahan.laporan_neraca_id')
            ->where('laporan_neraca.status', $status)
            ->selectRaw('COALESCE(SUM(detail_pengolahan.tonase_kg * detail_pengolahan.faktor_emisi_saat_lapor), 0) AS total')
            ->value('total');

        $rasioKemandirian = $totalTimbulan > 0 ? $totalTerolah / $totalTimbulan * 100 : 0;

        $terpakaiSub = DB::table('laporan_neraca')
            ->selectRaw('periode_kuota_id, SUM(residu_kg) AS terpakai')
            ->where('status', $status)
            ->groupBy('periode_kuota_id');

        $daftarKawasan = DB::table('kawasan')
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

        $kontribusiJalur = DB::table('detail_pengolahan')
            ->join('laporan_neraca', 'laporan_neraca.id', '=', 'detail_pengolahan.laporan_neraca_id')
            ->join('jalur_pengolahan', 'jalur_pengolahan.id', '=', 'detail_pengolahan.jalur_pengolahan_id')
            ->where('laporan_neraca.status', $status)
            ->groupBy('jalur_pengolahan.id', 'jalur_pengolahan.nama')
            ->orderByDesc('total_tonase')
            ->get([
                'jalur_pengolahan.nama',
                DB::raw('SUM(detail_pengolahan.tonase_kg) AS total_tonase'),
            ]);

        $timbulanSub = DB::table('laporan_neraca')
            ->selectRaw('kawasan_id, SUM(timbulan_kg) AS timbulan')
            ->where('status', $status)
            ->groupBy('kawasan_id');

        $terolahSub = DB::table('detail_pengolahan')
            ->join('laporan_neraca', 'laporan_neraca.id', '=', 'detail_pengolahan.laporan_neraca_id')
            ->where('laporan_neraca.status', $status)
            ->selectRaw('laporan_neraca.kawasan_id, SUM(detail_pengolahan.tonase_kg) AS terolah')
            ->groupBy('laporan_neraca.kawasan_id');

        $peringkatKawasan = DB::table('kawasan')
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

        $adaData = LaporanNeraca::where('status', $status)->exists();

        $pembagiBatang = $totalTerolah + $totalResidu;
        $persenTerolah = $pembagiBatang > 0 ? $totalTerolah / $pembagiBatang * 100 : 0;
        $persenResidu = $pembagiBatang > 0 ? $totalResidu / $pembagiBatang * 100 : 0;
        $kontribusiMaks = (float) (optional($kontribusiJalur->first())->total_tonase ?: 0);

        return view('dashboard.publik', compact(
            'totalTimbulan',
            'totalTerolah',
            'totalResidu',
            'totalEmisi',
            'rasioKemandirian',
            'daftarKawasan',
            'kontribusiJalur',
            'peringkatKawasan',
            'adaData',
            'persenTerolah',
            'persenResidu',
            'kontribusiMaks',
        ));
    }

    public function index(Request $request)
    {
        return match ($request->user()->role) {
            'admin' => redirect()->route('dashboard.admin'),
            'operator' => redirect()->route('dashboard.operator'),
            default => redirect()->route('dashboard.warga'),
        };
    }

    public function admin()
    {
        return view('dashboard.admin', ['user' => auth()->user()]);
    }

    public function operator()
    {
        return view('dashboard.operator', ['user' => auth()->user()]);
    }

    public function warga()
    {
        return view('dashboard.warga', ['user' => auth()->user()]);
    }
}
