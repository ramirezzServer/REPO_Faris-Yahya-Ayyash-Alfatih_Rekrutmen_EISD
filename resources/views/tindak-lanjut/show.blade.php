@extends('layouts.app')

@section('judul', 'Detail Tumpukan Liar')

@section('content')
    @php
        $badge = match ($laporan->status) {
            'baru' => 'border-garis text-lembut',
            'diproses' => 'border-waspada/40 bg-waspada/10 text-waspada',
            'selesai' => 'border-aman/40 bg-aman/10 text-aman',
            default => 'border-garis text-lembut',
        };
    @endphp

    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-lg font-semibold text-tinta">Detail Laporan Tumpukan Liar</h1>
        <a href="{{ route('tindak-lanjut.index') }}" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Kembali</a>
    </div>

    <div class="mb-6 rounded-lg border border-garis bg-permukaan p-6">
        <dl class="grid grid-cols-1 gap-x-6 gap-y-3 sm:grid-cols-[10rem_1fr]">
            <dt class="text-sm font-medium text-lembut">Kawasan</dt>
            <dd class="text-sm text-tinta">{{ $laporan->kawasan->kode_kawasan ?? '-' }} &mdash; {{ $laporan->kawasan->kelurahan ?? '' }}</dd>
            <dt class="text-sm font-medium text-lembut">Pelapor</dt>
            <dd class="text-sm text-tinta">{{ $laporan->user->name ?? '-' }}</dd>
            <dt class="text-sm font-medium text-lembut">Tanggal Lapor</dt>
            <dd class="text-sm text-tinta">{{ $laporan->created_at->format('d M Y H:i') }}</dd>
            <dt class="text-sm font-medium text-lembut">Lokasi</dt>
            <dd class="text-sm text-tinta">{{ $laporan->lokasi }}</dd>
            <dt class="text-sm font-medium text-lembut">Deskripsi</dt>
            <dd class="text-sm text-tinta">{{ $laporan->deskripsi }}</dd>
            <dt class="text-sm font-medium text-lembut">Status</dt>
            <dd class="text-sm text-tinta"><span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $laporan->status }}</span></dd>
            @if ($laporan->catatan_tindak_lanjut)
                <dt class="text-sm font-medium text-lembut">Catatan Tindak Lanjut</dt>
                <dd class="text-sm text-tinta">{{ $laporan->catatan_tindak_lanjut }}</dd>
            @endif
        </dl>
    </div>

    <div class="mb-6">
        <h2 class="mb-2 text-sm font-semibold text-lembut">Foto</h2>
        @if ($laporan->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($laporan->foto))
            <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto tumpukan liar"
                class="max-h-96 rounded-lg border border-garis">
        @else
            <p class="text-sm text-lembut">Foto tidak tersedia.</p>
        @endif
    </div>

    @if ($laporan->status !== 'selesai')
        <div class="max-w-lg rounded-lg border border-garis bg-permukaan p-6">
            <h2 class="mb-3 text-sm font-semibold text-lembut">Perbarui Status Tindak Lanjut</h2>
            <form method="POST" action="{{ route('tindak-lanjut.update-status', $laporan->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1 block text-sm font-medium text-tinta" for="status">Status</label>
                    <select id="status" name="status"
                        class="w-full rounded-md border border-garis bg-white px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30" required>
                        <option value="diproses" @selected(old('status') === 'diproses')>diproses</option>
                        <option value="selesai" @selected(old('status') === 'selesai')>selesai</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-tinta" for="catatan_tindak_lanjut">Catatan Tindak Lanjut (opsional)</label>
                    <textarea id="catatan_tindak_lanjut" name="catatan_tindak_lanjut" rows="2" maxlength="500"
                        class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30">{{ old('catatan_tindak_lanjut', $laporan->catatan_tindak_lanjut) }}</textarea>
                </div>

                <button type="submit" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Simpan</button>
            </form>
        </div>
    @else
        <div class="rounded-md border border-aman/40 bg-aman/10 p-4 text-sm text-tinta" role="alert">
            Laporan ini sudah selesai ditindaklanjuti.
        </div>
    @endif
@endsection
