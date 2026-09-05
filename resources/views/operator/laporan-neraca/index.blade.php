@extends('layouts.app')

@section('judul', 'Laporan Neraca')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-tinta">Laporan Neraca Kawasan {{ $kawasan?->kode_kawasan ?? '-' }}</h1>
            <p class="mt-1 text-sm text-lembut">Riwayat laporan neraca harian kawasan Anda.</p>
        </div>
        <a href="{{ route('operator.laporan-neraca.create') }}" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Buat Laporan</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="max-h-[70vh] overflow-auto">
            <table class="w-full text-sm">
                <thead class="sticky top-0 z-10 bg-permukaan">
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
                        <tr class="border-b border-garis last:border-0 hover:bg-latar">
                            <td class="px-4 py-3">{{ $l->tanggal_laporan->format('d M Y') }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ number_format((float) $l->timbulan_kg, 2) }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ number_format((float) $l->total_diolah_kg, 2) }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ number_format((float) $l->residu_kg, 2) }}</td>
                            <td class="px-4 py-3"><span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $l->status }}</span></td>
                            <td class="px-4 py-3">
                                <a href="{{ route('operator.laporan-neraca.show', $l) }}" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <p class="text-sm text-tinta">Belum ada laporan neraca.</p>
                                <p class="mt-1 text-sm text-lembut">Laporan yang Anda buat akan muncul di sini.</p>
                                <a href="{{ route('operator.laporan-neraca.create') }}" class="mt-4 inline-block rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Buat Laporan</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
