@extends('layouts.app')

@section('title', 'Detail Tumpukan Liar — SINERKA')

@section('content')
    @php($badge = match ($laporan->status) {
        'baru' => 'bg-secondary',
        'diproses' => 'bg-warning text-dark',
        'selesai' => 'bg-success',
        default => 'bg-secondary',
    })

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Detail Laporan Tumpukan Liar</h1>
        <a href="{{ route('tindak-lanjut.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
    </div>

    <dl class="row">
        <dt class="col-sm-3">Kawasan</dt>
        <dd class="col-sm-9">{{ $laporan->kawasan->kode_kawasan ?? '-' }} &mdash; {{ $laporan->kawasan->kelurahan ?? '' }}</dd>
        <dt class="col-sm-3">Pelapor</dt>
        <dd class="col-sm-9">{{ $laporan->user->name ?? '-' }}</dd>
        <dt class="col-sm-3">Tanggal Lapor</dt>
        <dd class="col-sm-9">{{ $laporan->created_at->format('d M Y H:i') }}</dd>
        <dt class="col-sm-3">Lokasi</dt>
        <dd class="col-sm-9">{{ $laporan->lokasi }}</dd>
        <dt class="col-sm-3">Deskripsi</dt>
        <dd class="col-sm-9">{{ $laporan->deskripsi }}</dd>
        <dt class="col-sm-3">Status</dt>
        <dd class="col-sm-9"><span class="badge {{ $badge }}">{{ $laporan->status }}</span></dd>
        @if ($laporan->catatan_tindak_lanjut)
            <dt class="col-sm-3">Catatan Tindak Lanjut</dt>
            <dd class="col-sm-9">{{ $laporan->catatan_tindak_lanjut }}</dd>
        @endif
    </dl>

    <div class="mb-4">
        <h2 class="h6">Foto</h2>
        @if ($laporan->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($laporan->foto))
            <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto tumpukan liar"
                class="img-fluid rounded border" style="max-height: 24rem;">
        @else
            <p class="text-muted">Foto tidak tersedia.</p>
        @endif
    </div>

    @if ($laporan->status !== 'selesai')
        <div class="card" style="max-width: 32rem;">
            <div class="card-body">
                <h2 class="h6 mb-3">Perbarui Status Tindak Lanjut</h2>
                <form method="POST" action="{{ route('tindak-lanjut.update-status', $laporan->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="status">Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="diproses" @selected(old('status') === 'diproses')>diproses</option>
                            <option value="selesai" @selected(old('status') === 'selesai')>selesai</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="catatan_tindak_lanjut">Catatan Tindak Lanjut (opsional)</label>
                        <textarea id="catatan_tindak_lanjut" name="catatan_tindak_lanjut" rows="2" maxlength="500"
                            class="form-control">{{ old('catatan_tindak_lanjut', $laporan->catatan_tindak_lanjut) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-success" role="alert">
            Laporan ini sudah selesai ditindaklanjuti.
        </div>
    @endif
@endsection
