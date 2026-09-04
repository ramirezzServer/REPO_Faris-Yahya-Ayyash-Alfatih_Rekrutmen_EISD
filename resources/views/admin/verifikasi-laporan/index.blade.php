@extends('layouts.app')

@section('judul', 'Verifikasi Laporan Neraca')

@section('content')
    <h1 class="mb-4 text-lg font-semibold text-tinta">Verifikasi Laporan Neraca</h1>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Tanggal</th>
                        <th class="px-4 py-2 font-medium">Kawasan</th>
                        <th class="px-4 py-2 font-medium">Operator</th>
                        <th class="px-4 py-2 text-right font-medium">Timbulan (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">Residu (kg)</th>
                        <th class="px-4 py-2 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporan as $l)
                        <tr class="border-b border-garis last:border-0">
                            <td class="px-4 py-3">{{ $l->tanggal_laporan->format('d M Y') }}</td>
                            <td class="px-4 py-3 font-medium text-tinta">{{ $l->kawasan->kode_kawasan ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $l->operator->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float) $l->timbulan_kg, 2) }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float) $l->residu_kg, 2) }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.verifikasi-laporan.show', $l->id) }}" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Tinjau</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-lembut">Tidak ada laporan yang menunggu verifikasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
