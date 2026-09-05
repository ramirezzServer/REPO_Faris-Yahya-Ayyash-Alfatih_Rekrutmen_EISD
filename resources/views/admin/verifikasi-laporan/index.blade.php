@extends('layouts.app')

@section('judul', 'Verifikasi Laporan Neraca')

@section('content')
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-tinta">Verifikasi Laporan Neraca</h1>
        <p class="mt-1 text-sm text-lembut">Tinjau dan verifikasi laporan neraca yang dikirim operator kawasan.</p>
    </div>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="max-h-[70vh] overflow-auto">
            <table class="w-full text-sm">
                <thead class="sticky top-0 z-10 bg-permukaan">
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Tanggal</th>
                        <th class="px-4 py-2 font-medium">Kawasan</th>
                        <th class="px-4 py-2 font-medium">Operator</th>
                        <th class="px-4 py-2 text-right font-medium">Timbulan (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">Residu (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporan as $l)
                        <tr class="border-b border-garis last:border-0 hover:bg-latar">
                            <td class="px-4 py-3">{{ $l->tanggal_laporan->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-medium text-tinta">{{ $l->kawasan->kode_kawasan ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $l->operator->name ?? '-' }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ number_format((float) $l->timbulan_kg, 2) }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ number_format((float) $l->residu_kg, 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.verifikasi-laporan.show', $l->id) }}" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Tinjau</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <p class="text-sm text-tinta">Tidak ada laporan yang menunggu verifikasi.</p>
                                <p class="mt-1 text-sm text-lembut">Semua laporan neraca yang masuk sudah ditinjau.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
