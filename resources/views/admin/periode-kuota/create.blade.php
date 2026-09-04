@extends('layouts.app')

@section('judul', 'Tambah Periode Kuota')

@section('content')
    <h1 class="mb-6 text-lg font-semibold text-tinta">Tambah Periode Kuota</h1>

    <div class="max-w-xl">
        <form method="POST" action="{{ route('admin.periode-kuota.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1 block text-sm font-medium text-tinta" for="kawasan_id">Kawasan</label>
                <select id="kawasan_id" name="kawasan_id"
                    class="w-full rounded-md border border-garis bg-white px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                    required>
                    <option value="">-- Pilih Kawasan --</option>
                    @foreach ($kawasan as $k)
                        <option value="{{ $k->id }}" @selected(old('kawasan_id') == $k->id)>
                            {{ $k->kode_kawasan }} — {{ $k->nama_rw }}, {{ $k->kelurahan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-tinta" for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                    class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                    value="{{ old('tanggal_mulai') }}" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-tinta" for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                    class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                    value="{{ old('tanggal_selesai') }}" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-tinta" for="kuota_residu_kg">Kuota Residu (kg)</label>
                <input type="number" id="kuota_residu_kg" name="kuota_residu_kg" step="0.01" min="0"
                    class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                    value="{{ old('kuota_residu_kg') }}" required>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Simpan</button>
                <a href="{{ route('admin.periode-kuota.index') }}" class="rounded-md border border-garis px-4 py-2 text-sm font-medium text-tinta hover:bg-latar">Batal</a>
            </div>
        </form>
    </div>
@endsection
