<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\LaporanTumpukan;
use Illuminate\Http\Request;

class LaporanTumpukanController extends Controller
{
    public function index()
    {
        $laporan = LaporanTumpukan::where('user_id', auth()->id())
            ->with('kawasan')
            ->orderByDesc('created_at')
            ->get();

        return view('warga.laporan-tumpukan.index', compact('laporan'));
    }

    public function create()
    {
        if (! auth()->user()->kawasan) {
            return redirect()->route('warga.laporan-tumpukan.index')
                ->with('error', 'Akun Anda belum terhubung ke kawasan mana pun. Hubungi admin.');
        }

        return view('warga.laporan-tumpukan.create');
    }

    public function store(Request $request)
    {
        $kawasan = auth()->user()->kawasan;

        if (! $kawasan) {
            return redirect()->route('warga.laporan-tumpukan.index')
                ->with('error', 'Akun Anda belum terhubung ke kawasan mana pun. Hubungi admin.');
        }

        $validated = $request->validate([
            'lokasi' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'foto' => ['required', 'image', 'max:2048'],
        ]);

        $validated['foto'] = $request->file('foto')->store('tumpukan', 'public');
        $validated['user_id'] = auth()->id();
        $validated['kawasan_id'] = $kawasan->id;
        $validated['status'] = 'baru';

        LaporanTumpukan::create($validated);

        return redirect()->route('warga.laporan-tumpukan.index')
            ->with('success', 'Laporan tumpukan liar berhasil dikirim.');
    }
}
