<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanNeraca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiLaporanController extends Controller
{
    public function index()
    {
        $laporan = LaporanNeraca::where('status', 'menunggu')
            ->with(['kawasan', 'operator'])
            ->orderByDesc('tanggal_laporan')
            ->orderByDesc('id')
            ->get();

        return view('admin.verifikasi-laporan.index', compact('laporan'));
    }

    public function show(int $id)
    {
        $laporan = LaporanNeraca::with(['kawasan', 'operator', 'jalurPengolahan', 'periodeKuota'])
            ->findOrFail($id);

        $sisaKuota = $laporan->periodeKuota->sisaKuota();

        return view('admin.verifikasi-laporan.show', compact('laporan', 'sisaKuota'));
    }

    public function verify(int $id)
    {
        $laporan = LaporanNeraca::findOrFail($id);

        $berhasil = DB::transaction(function () use ($laporan) {
            if ($laporan->verifikasi()) {
                $kawasan = $laporan->kawasan;
                $kawasan->status_siaga = $kawasan->hitungStatusSiaga();
                $kawasan->save();

                return true;
            }

            $laporan->update([
                'status' => 'ditolak',
                'catatan' => 'Residu laporan melampaui sisa kuota periode. Verifikasi ditolak otomatis.',
            ]);

            $kawasan = $laporan->kawasan;
            $kawasan->status_siaga = 'kritis';
            $kawasan->save();

            return false;
        });

        if ($berhasil) {
            return redirect()->route('admin.verifikasi-laporan.index')
                ->with('success', 'Laporan neraca terverifikasi. Status siaga kawasan diperbarui.');
        }

        return redirect()->route('admin.verifikasi-laporan.index')
            ->with('error', 'Verifikasi gagal: residu laporan melampaui sisa kuota periode. Laporan ditolak dan kawasan ditandai KRITIS.');
    }

    public function reject(Request $request, int $id)
    {
        $validated = $request->validate([
            'alasan' => ['required', 'string', 'max:500'],
        ]);

        LaporanNeraca::findOrFail($id)->tolak($validated['alasan']);

        return redirect()->route('admin.verifikasi-laporan.index')
            ->with('success', 'Laporan neraca ditolak.');
    }
}
