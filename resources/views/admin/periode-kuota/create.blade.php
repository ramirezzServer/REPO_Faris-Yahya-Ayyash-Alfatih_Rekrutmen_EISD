@extends('layouts.app')

@section('title', 'Tambah Periode Kuota — SINERKA')

@section('content')
    <h1 class="h4 mb-4">Tambah Periode Kuota</h1>

    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.periode-kuota.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="kawasan_id">Kawasan</label>
                <select id="kawasan_id" name="kawasan_id" class="form-select" required>
                    <option value="">-- Pilih Kawasan --</option>
                    @foreach ($kawasan as $k)
                        <option value="{{ $k->id }}" @selected(old('kawasan_id') == $k->id)>
                            {{ $k->kode_kawasan }} — {{ $k->nama_rw }}, {{ $k->kelurahan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                    class="form-control" value="{{ old('tanggal_mulai') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="tanggal_selesai">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                    class="form-control" value="{{ old('tanggal_selesai') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="kuota_residu_kg">Kuota Residu (kg)</label>
                <input type="number" id="kuota_residu_kg" name="kuota_residu_kg" step="0.01" min="0"
                    class="form-control" value="{{ old('kuota_residu_kg') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.periode-kuota.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
@endsection
