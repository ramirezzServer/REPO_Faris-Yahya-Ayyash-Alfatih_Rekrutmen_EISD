<?php

namespace App\Http\Controllers;

use App\Models\LaporanTumpukan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TindakLanjutTumpukanController extends Controller
{
    public function index()
    {
        $query = LaporanTumpukan::with(['kawasan', 'user'])->orderByDesc('created_at');

        if (auth()->user()->role === 'operator') {
            $query->where('kawasan_id', auth()->user()->kawasan?->id);
        }

        return view('tindak-lanjut.index', ['laporan' => $query->get()]);
    }

    public function show(int $id)
    {
        $laporan = LaporanTumpukan::with(['kawasan', 'user'])->findOrFail($id);

        $this->pastikanAksesOperator($laporan);

        return view('tindak-lanjut.show', compact('laporan'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $laporan = LaporanTumpukan::findOrFail($id);

        $this->pastikanAksesOperator($laporan);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['diproses', 'selesai'])],
            'catatan_tindak_lanjut' => ['nullable', 'string', 'max:500'],
        ]);

        if ($laporan->status === 'selesai') {
            return back()->with('error', 'Laporan ini sudah berstatus selesai dan tidak dapat diubah lagi.');
        }

        $laporan->update($validated);

        return redirect()->route('tindak-lanjut.show', $laporan->id)
            ->with('success', 'Status tindak lanjut berhasil diperbarui.');
    }

    private function pastikanAksesOperator(LaporanTumpukan $laporan): void
    {
        if (auth()->user()->role === 'operator') {
            abort_if($laporan->kawasan_id !== auth()->user()->kawasan?->id, 403);
        }
    }
}
