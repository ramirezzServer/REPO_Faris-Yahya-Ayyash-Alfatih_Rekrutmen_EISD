@extends('layouts.app')

@section('title', 'Laporkan Tumpukan Liar — SINERKA')

@section('content')
    <h1 class="h4 mb-4">Laporkan Tumpukan Liar</h1>

    <div class="col-md-6">
        <form method="POST" action="{{ route('warga.laporan-tumpukan.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" maxlength="255"
                    class="form-control" value="{{ old('lokasi') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control" required>{{ old('deskripsi') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label" for="foto">Foto Tumpukan (gambar, maks. 2 MB)</label>
                <input type="file" id="foto" name="foto" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Kirim Laporan</button>
            <a href="{{ route('warga.laporan-tumpukan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
@endsection
