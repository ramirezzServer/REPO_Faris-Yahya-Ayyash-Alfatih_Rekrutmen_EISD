<?php

namespace App\Http\Controllers;

use App\Models\Kawasan;
use App\Models\LaporanNeraca;
use App\Models\LaporanTumpukan;
use App\Models\PeriodeKuota;
use App\Support\NeracaStat;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function publik()
    {
        $totalTimbulan = NeracaStat::totalTimbulan();
        $totalTerolah = NeracaStat::totalTerolah();
        $totalResidu = NeracaStat::totalResidu();
        $totalEmisi = NeracaStat::totalEmisi();
        $rasioKemandirian = NeracaStat::rasioKemandirian();
        $daftarKawasan = NeracaStat::ringkasanKawasan();
        $kontribusiJalur = NeracaStat::kontribusiJalur();
        $peringkatKawasan = NeracaStat::peringkatKawasan();
        $adaData = NeracaStat::adaLaporanTerverifikasi();
        $trenResiduKota = NeracaStat::trenResiduHarian(14);

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
            'trenResiduKota',
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
        $jumlahMenunggu = NeracaStat::jumlahLaporanMenunggu();
        $jumlahPerStatus = NeracaStat::jumlahPerStatus();

        $laporanMenunggu = LaporanNeraca::where('status', 'menunggu')
            ->with(['kawasan', 'periodeKuota'])
            ->latest('tanggal_laporan')
            ->take(5)
            ->get();

        $kawasanKritis = Kawasan::where('status_siaga', 'kritis')->orderBy('kode_kawasan')->get();

        $periodeSegeraBerakhir = PeriodeKuota::where('status', 'aktif')
            ->whereBetween('tanggal_selesai', [today(), today()->addDays(14)])
            ->with('kawasan')
            ->orderBy('tanggal_selesai')
            ->get();

        $jumlahTumpukanBaru = LaporanTumpukan::where('status', 'baru')->count();

        $trenResiduKota = NeracaStat::trenResiduHarian(14);
        $kuotaKota = (float) PeriodeKuota::where('status', 'aktif')->sum('kuota_residu_kg');
        $lajuKuota = NeracaStat::lajuPemakaianKuota();

        return view('dashboard.admin', [
            'user' => auth()->user(),
            'jumlahMenunggu' => $jumlahMenunggu,
            'jumlahPerStatus' => $jumlahPerStatus,
            'laporanMenunggu' => $laporanMenunggu,
            'kawasanKritis' => $kawasanKritis,
            'periodeSegeraBerakhir' => $periodeSegeraBerakhir,
            'jumlahTumpukanBaru' => $jumlahTumpukanBaru,
            'trenResiduKota' => $trenResiduKota,
            'kuotaKota' => $kuotaKota,
            'lajuKuota' => $lajuKuota,
        ]);
    }

    public function operator()
    {
        $user = auth()->user();
        $kawasan = $user->kawasan;
        $periode = $kawasan?->periodeAktif();

        $sudahLaporHariIni = $kawasan
            ? LaporanNeraca::where('kawasan_id', $kawasan->id)
                ->where('tanggal_laporan', today()->toDateString())
                ->exists()
            : false;

        $laporanTerakhir = $kawasan
            ? LaporanNeraca::where('kawasan_id', $kawasan->id)
                ->latest('tanggal_laporan')
                ->take(5)
                ->get()
            : collect();

        $jumlahTumpukanBaru = $kawasan
            ? LaporanTumpukan::where('kawasan_id', $kawasan->id)->where('status', 'baru')->count()
            : 0;

        $trenResiduKawasan = $kawasan ? NeracaStat::trenResiduHarian(14, $kawasan->id) : null;
        $trenKemandirianKawasan = $kawasan ? NeracaStat::trenKemandirianHarian(14, $kawasan->id) : null;

        return view('dashboard.operator', [
            'user' => $user,
            'kawasan' => $kawasan,
            'periode' => $periode,
            'sudahLaporHariIni' => $sudahLaporHariIni,
            'laporanTerakhir' => $laporanTerakhir,
            'jumlahTumpukanBaru' => $jumlahTumpukanBaru,
            'trenResiduKawasan' => $trenResiduKawasan,
            'trenKemandirianKawasan' => $trenKemandirianKawasan,
        ]);
    }

    public function warga()
    {
        $user = auth()->user();
        $kawasan = $user->kawasan;
        $periode = $kawasan?->periodeAktif();

        $hitungStatus = LaporanTumpukan::where('user_id', $user->id)
            ->selectRaw('status, COUNT(*) AS jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $jumlahPerStatusTumpukan = [
            'baru' => (int) ($hitungStatus['baru'] ?? 0),
            'diproses' => (int) ($hitungStatus['diproses'] ?? 0),
            'selesai' => (int) ($hitungStatus['selesai'] ?? 0),
        ];

        $laporanTerakhir = LaporanTumpukan::where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        $trenKemandirianKawasan = $kawasan ? NeracaStat::trenKemandirianHarian(14, $kawasan->id) : null;

        return view('dashboard.warga', [
            'user' => $user,
            'kawasan' => $kawasan,
            'periode' => $periode,
            'jumlahPerStatusTumpukan' => $jumlahPerStatusTumpukan,
            'laporanTerakhir' => $laporanTerakhir,
            'trenKemandirianKawasan' => $trenKemandirianKawasan,
        ]);
    }
}
