<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\JalurPengolahan;
use App\Models\LaporanNeraca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanNeracaController extends Controller
{
    public function index()
    {
        $kawasan = auth()->user()->kawasan;

        $laporan = LaporanNeraca::where('kawasan_id', $kawasan?->id)
            ->with('periodeKuota')
            ->orderByDesc('tanggal_laporan')
            ->orderByDesc('id')
            ->get();

        return view('operator.laporan-neraca.index', compact('kawasan', 'laporan'));
    }

    public function create()
    {
        $kawasan = auth()->user()->kawasan;

        if (! $kawasan) {
            return redirect()->route('operator.laporan-neraca.index')
                ->with('error', 'Akun Anda belum terhubung ke kawasan mana pun.');
        }

        $periode = $kawasan->periodeAktif();

        if (! $periode) {
            return redirect()->route('operator.laporan-neraca.index')
                ->with('error', 'Kawasan Anda belum memiliki periode kuota aktif. Hubungi admin.');
        }

        $sisaKuota = $periode->sisaKuota();
        $persentaseSisa = $periode->persentaseSisa();
        $kuotaMenipis = $persentaseSisa < 40;
        $jalurList = JalurPengolahan::where('is_aktif', true)->orderBy('nama')->get();

        return view('operator.laporan-neraca.create', compact(
            'kawasan',
            'periode',
            'sisaKuota',
            'persentaseSisa',
            'kuotaMenipis',
            'jalurList'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_laporan' => ['required', 'date', 'before_or_equal:today'],
            'timbulan_kg' => ['required', 'numeric', 'gt:0'],
            'uraian' => ['required', 'array', 'min:1'],
            'uraian.*.jalur_pengolahan_id' => ['required', 'distinct', 'exists:jalur_pengolahan,id'],
            'uraian.*.tonase_kg' => ['required', 'numeric', 'gt:0'],
            'uraian.*.keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $kawasan = auth()->user()->kawasan;

        if (! $kawasan) {
            return redirect()->route('operator.laporan-neraca.index')
                ->with('error', 'Akun Anda belum terhubung ke kawasan mana pun.');
        }

        $timbulan = (float) $validated['timbulan_kg'];
        $totalTonase = collect($validated['uraian'])->sum(fn ($baris) => (float) $baris['tonase_kg']);

        // Aturan bisnis 1 — periode ditutup.
        $periode = $kawasan->periodeAktif();
        if (! $periode) {
            return back()->withInput()
                ->with('error', 'Periode kuota kawasan sudah ditutup. Laporan tidak dapat disimpan.');
        }

        // Aturan bisnis 2 — laporan ganda.
        $sudahAda = LaporanNeraca::where('kawasan_id', $kawasan->id)
            ->where('tanggal_laporan', $validated['tanggal_laporan'])
            ->exists();
        if ($sudahAda) {
            return back()->withInput()
                ->with('error', 'Laporan neraca untuk tanggal tersebut sudah ada.');
        }

        // Aturan bisnis 3 — neraca tidak wajar.
        if ($totalTonase > $timbulan) {
            return back()->withInput()->with(
                'error',
                'Total tonase olahan (' . $totalTonase . ' kg) tidak boleh melebihi timbulan (' . $timbulan . ' kg).'
            );
        }

        DB::transaction(function () use ($validated, $kawasan, $periode, $timbulan, $totalTonase) {
            $laporan = LaporanNeraca::create([
                'kawasan_id' => $kawasan->id,
                'operator_id' => auth()->id(),
                'periode_kuota_id' => $periode->id,
                'tanggal_laporan' => $validated['tanggal_laporan'],
                'timbulan_kg' => $timbulan,
                'total_diolah_kg' => $totalTonase,
                'residu_kg' => $timbulan - $totalTonase,
                'status' => 'menunggu',
            ]);

            foreach ($validated['uraian'] as $baris) {
                $jalur = JalurPengolahan::findOrFail($baris['jalur_pengolahan_id']);

                $laporan->jalurPengolahan()->attach($jalur->id, [
                    'tonase_kg' => $baris['tonase_kg'],
                    'faktor_emisi_saat_lapor' => $jalur->faktor_emisi_co2,
                    'keterangan' => $baris['keterangan'] ?? null,
                ]);
            }
        });

        return redirect()->route('operator.laporan-neraca.index')
            ->with('success', 'Laporan neraca berhasil dicatat dan menunggu verifikasi admin.');
    }

    public function show(LaporanNeraca $laporanNeraca)
    {
        abort_if($laporanNeraca->kawasan_id !== auth()->user()->kawasan?->id, 403);

        $laporanNeraca->load('jalurPengolahan', 'periodeKuota', 'operator');

        return view('operator.laporan-neraca.show', ['laporan' => $laporanNeraca]);
    }
}
