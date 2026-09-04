@extends('layouts.app')

@section('judul', 'Detail Laporan Neraca')

@section('content')
    @php
        $badge = match ($laporan->status) {
            'menunggu' => 'border-garis text-lembut',
            'terverifikasi' => 'border-aman/40 bg-aman/10 text-aman',
            'ditolak' => 'border-kritis/40 bg-kritis/10 text-kritis',
            default => 'border-garis text-lembut',
        };
    @endphp

    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-lg font-semibold text-tinta">Laporan Neraca {{ $laporan->tanggal_laporan->format('d M Y') }}</h1>
        <a href="{{ route('operator.laporan-neraca.index') }}" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Kembali</a>
    </div>

    <div class="mb-6 rounded-lg border border-garis bg-permukaan p-6">
        <dl class="grid grid-cols-1 gap-x-6 gap-y-3 sm:grid-cols-[10rem_1fr]">
            <dt class="text-sm font-medium text-lembut">Kawasan</dt>
            <dd class="text-sm text-tinta">{{ $laporan->kawasan->kode_kawasan ?? '-' }}</dd>
            <dt class="text-sm font-medium text-lembut">Operator</dt>
            <dd class="text-sm text-tinta">{{ $laporan->operator->name ?? '-' }}</dd>
            <dt class="text-sm font-medium text-lembut">Periode</dt>
            <dd class="text-sm text-tinta">
                {{ $laporan->periodeKuota->tanggal_mulai->format('d M Y') }} &ndash;
                {{ $laporan->periodeKuota->tanggal_selesai->format('d M Y') }}
            </dd>
            <dt class="text-sm font-medium text-lembut">Status</dt>
            <dd class="text-sm text-tinta"><span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $laporan->status }}</span></dd>
            <dt class="text-sm font-medium text-lembut">Timbulan</dt>
            <dd class="text-sm text-tinta">{{ number_format((float) $laporan->timbulan_kg, 2) }} kg</dd>
            <dt class="text-sm font-medium text-lembut">Total Diolah</dt>
            <dd class="text-sm text-tinta">{{ number_format((float) $laporan->total_diolah_kg, 2) }} kg</dd>
            <dt class="text-sm font-medium text-lembut">Residu</dt>
            <dd class="text-sm text-tinta">{{ number_format((float) $laporan->residu_kg, 2) }} kg</dd>
            @if ($laporan->catatan)
                <dt class="text-sm font-medium text-lembut">Catatan</dt>
                <dd class="text-sm text-tinta">{{ $laporan->catatan }}</dd>
            @endif
        </dl>
    </div>

    <h2 class="mb-2 text-sm font-semibold text-lembut">Uraian Tonase per Jalur</h2>
    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Jalur Pengolahan</th>
                        <th class="px-4 py-2 text-right font-medium">Tonase (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">Faktor Emisi CO<sub>2</sub> (saat lapor)</th>
                        <th class="px-4 py-2 font-medium">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan->jalurPengolahan as $jalur)
                        <tr class="border-b border-garis last:border-0">
                            <td class="px-4 py-3">{{ $jalur->nama }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float) $jalur->pivot->tonase_kg, 2) }}</td>
                            <td class="px-4 py-3 text-right">{{ $jalur->pivot->faktor_emisi_saat_lapor }}</td>
                            <td class="px-4 py-3">{{ $jalur->pivot->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
