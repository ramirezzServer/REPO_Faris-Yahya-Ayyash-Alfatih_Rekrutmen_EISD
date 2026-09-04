<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kawasan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KawasanController extends Controller
{
    public function index()
    {
        $daftar = Kawasan::withCount(['users as jumlah_warga' => fn ($q) => $q->where('role', 'warga')])
            ->orderBy('kode_kawasan')
            ->get()
            ->map(fn (Kawasan $kawasan) => [
                'model' => $kawasan,
                'status_siaga' => $kawasan->hitungStatusSiaga(),
            ]);

        return view('admin.kawasan.index', ['daftar' => $daftar]);
    }

    public function create()
    {
        return view('admin.kawasan.create');
    }

    public function store(Request $request)
    {
        Kawasan::create($this->validateData($request));

        return redirect()->route('admin.kawasan.index')->with('success', 'Kawasan berhasil ditambahkan.');
    }

    public function edit(Kawasan $kawasan)
    {
        return view('admin.kawasan.edit', compact('kawasan'));
    }

    public function update(Request $request, Kawasan $kawasan)
    {
        $kawasan->update($this->validateData($request, $kawasan->id));

        return redirect()->route('admin.kawasan.index')->with('success', 'Kawasan berhasil diperbarui.');
    }

    public function destroy(Kawasan $kawasan)
    {
        if ($kawasan->laporanNeraca()->exists() || $kawasan->periodeKuota()->exists()) {
            return back()->with('error', 'Kawasan tidak dapat dihapus karena sudah memiliki periode kuota atau laporan neraca. Data historis harus dipertahankan.');
        }

        $kawasan->delete();

        return redirect()->route('admin.kawasan.index')->with('success', 'Kawasan berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'kode_kawasan' => ['required', 'max:20', Rule::unique('kawasan', 'kode_kawasan')->ignore($ignoreId)],
            'nama_rw' => ['required', 'max:100'],
            'kelurahan' => ['required', 'max:100'],
            'kecamatan' => ['required', 'max:100'],
            'jumlah_kk' => ['required', 'integer', 'min:0'],
        ]);
    }
}
