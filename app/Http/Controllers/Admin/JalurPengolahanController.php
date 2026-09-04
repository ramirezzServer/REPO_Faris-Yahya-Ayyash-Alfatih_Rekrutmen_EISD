<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JalurPengolahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JalurPengolahanController extends Controller
{
    public function index()
    {
        $jalur = JalurPengolahan::orderBy('nama')->get();

        return view('admin.jalur-pengolahan.index', compact('jalur'));
    }

    public function create()
    {
        return view('admin.jalur-pengolahan.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['is_aktif'] = $request->boolean('is_aktif');

        if ($request->hasFile('ikon')) {
            $data['ikon'] = $request->file('ikon')->store('ikon', 'public');
        }

        JalurPengolahan::create($data);

        return redirect()->route('admin.jalur-pengolahan.index')->with('success', 'Jalur pengolahan berhasil ditambahkan.');
    }

    public function edit(JalurPengolahan $jalurPengolahan)
    {
        return view('admin.jalur-pengolahan.edit', ['jalurPengolahan' => $jalurPengolahan]);
    }

    public function update(Request $request, JalurPengolahan $jalurPengolahan)
    {
        $data = $this->validateData($request, $jalurPengolahan->id);
        $data['is_aktif'] = $request->boolean('is_aktif');

        if ($request->hasFile('ikon')) {
            if ($jalurPengolahan->ikon) {
                Storage::disk('public')->delete($jalurPengolahan->ikon);
            }

            $data['ikon'] = $request->file('ikon')->store('ikon', 'public');
        }

        $jalurPengolahan->update($data);

        return redirect()->route('admin.jalur-pengolahan.index')->with('success', 'Jalur pengolahan berhasil diperbarui.');
    }

    public function destroy(JalurPengolahan $jalurPengolahan)
    {
        if ($jalurPengolahan->ikon) {
            Storage::disk('public')->delete($jalurPengolahan->ikon);
        }

        $jalurPengolahan->delete();

        return redirect()->route('admin.jalur-pengolahan.index')->with('success', 'Jalur pengolahan berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'nama' => ['required', 'max:100', Rule::unique('jalur_pengolahan', 'nama')->ignore($ignoreId)],
            'kategori' => ['required', Rule::in(['organik', 'anorganik'])],
            'faktor_emisi_co2' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'is_aktif' => ['nullable', 'boolean'],
            'ikon' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}
