@extends('layouts.app')

@section('judul', 'Laporan Neraca')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-lg font-semibold text-tinta">Laporan Neraca Kawasan {{ $kawasan?->kode_kawasan ?? '-' }}</h1>
        <a href="{{ route('operator.laporan-neraca.create') }}" class="rounded-md bg-aksi px-3 py-1.5 text-sm font-medium text-white hover:opacity-90">Buat Laporan</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Tanggal</th>
                        <th class="px-4 py-2 text-right font-medium">Timbulan (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">Total Diolah (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">Residu (kg)</th>
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporan as $l)
                        @php
                            $badge = match ($l->status) {
                                'menunggu' => 'border-garis text-lembut',
                                'terverifikasi' => 'border-aman/40 bg-aman/10 text-aman',
                                'ditolak' => 'border-kritis/40 bg-kritis/10 text-kritis',
                                default => 'border-garis text-lembut',
                            };
                        @endphp
                        <tr class="border-b border-garis last:border-0">
                            <td class="px-4 py-3">{{ $l->tanggal_laporan->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float) $l->timbulan_kg, 2) }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float) $l->total_diolah_kg, 2) }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float) $l->residu_kg, 2) }}</td>
                            <td class="px-4 py-3"><span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $l->status }}</span></td>
                            <td class="px-4 py-3">
                                <a href="{{ route('operator.laporan-neraca.show', $l) }}" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-lembut">Belum ada laporan neraca.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
