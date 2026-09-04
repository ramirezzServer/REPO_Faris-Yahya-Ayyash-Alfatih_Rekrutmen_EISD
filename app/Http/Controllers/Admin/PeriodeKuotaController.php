<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kawasan;
use App\Models\PeriodeKuota;
use Illuminate\Http\Request;

class PeriodeKuotaController extends Controller
{
    public function index()
    {
        $daftar = PeriodeKuota::with('kawasan')
            ->orderByDesc('tanggal_mulai')
            ->get()
            ->map(fn (PeriodeKuota $periode) => [
                'model' => $periode,
                'kuota_terpakai' => $periode->kuotaTerpakai(),
                'sisa_kuota' => $periode->sisaKuota(),
                'persentase_sisa' => $periode->persentaseSisa(),
            ]);

        return view('admin.periode-kuota.index', ['daftar' => $daftar]);
    }

    public function create()
    {
        $kawasan = Kawasan::orderBy('kode_kawasan')->get();

        return view('admin.periode-kuota.create', compact('kawasan'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kawasan_id' => ['required', 'exists:kawasan,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
            'kuota_residu_kg' => ['required', 'numeric', 'gt:0'],
        ]);

        $tumpangTindih = PeriodeKuota::where('kawasan_id', $data['kawasan_id'])
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', $data['tanggal_selesai'])
            ->where('tanggal_selesai', '>=', $data['tanggal_mulai'])
            ->exists();

        if ($tumpangTindih) {
            return back()->withInput()->with(
                'error',
                'Kawasan ini sudah memiliki periode kuota aktif yang rentang tanggalnya beririsan dengan periode yang dimasukkan.'
            );
        }

        $data['status'] = 'aktif';
        PeriodeKuota::create($data);

        return redirect()->route('admin.periode-kuota.index')->with('success', 'Periode kuota berhasil dibuat.');
    }

    public function tutup(int $id)
    {
        $periode = PeriodeKuota::findOrFail($id);

        if ($periode->laporanNeraca()->where('status', 'menunggu')->exists()) {
            return back()->with('error', 'Periode tidak dapat ditutup karena masih ada laporan neraca berstatus menunggu.');
        }

        $periode->update(['status' => 'ditutup']);

        return redirect()->route('admin.periode-kuota.index')->with('success', 'Periode kuota berhasil ditutup.');
    }
}
