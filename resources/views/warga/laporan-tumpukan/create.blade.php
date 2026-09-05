@extends('layouts.app')

@section('judul', 'Laporkan Tumpukan Liar')

@section('content')
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-tinta">Laporkan Tumpukan Liar</h1>
        <p class="mt-1 text-sm text-lembut">Sertakan lokasi, deskripsi, dan foto agar petugas dapat menindaklanjuti dengan cepat.</p>
    </div>

    <div class="max-w-xl">
        <form method="POST" action="{{ route('warga.laporan-tumpukan.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1 block text-sm font-medium text-tinta" for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" maxlength="255"
                    class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                    value="{{ old('lokasi') }}" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-tinta" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                    class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30" required>{{ old('deskripsi') }}</textarea>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-tinta" for="foto">Foto Tumpukan (gambar, maks. 2 MB)</label>
                <input type="file" id="foto" name="foto" accept="image/*" required
                    class="w-full rounded-md border border-garis px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-latar file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-tinta">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Kirim Laporan</button>
                <a href="{{ route('warga.laporan-tumpukan.index') }}" class="rounded-md border border-garis px-4 py-2 text-sm font-medium text-tinta hover:bg-latar">Batal</a>
            </div>
        </form>
    </div>
@endsection
