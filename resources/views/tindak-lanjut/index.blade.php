@extends('layouts.app')

@section('judul', 'Tindak Lanjut Tumpukan')

@section('content')
    <h1 class="mb-4 text-lg font-semibold text-tinta">Tindak Lanjut Laporan Tumpukan Liar</h1>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Tanggal</th>
                        <th class="px-4 py-2 font-medium">Kawasan</th>
                        <th class="px-4 py-2 font-medium">Pelapor</th>
                        <th class="px-4 py-2 font-medium">Lokasi</th>
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporan as $l)
                        @php
                            $badge = match ($l->status) {
                                'baru' => 'border-garis text-lembut',
                                'diproses' => 'border-waspada/40 bg-waspada/10 text-waspada',
                                'selesai' => 'border-aman/40 bg-aman/10 text-aman',
                                default => 'border-garis text-lembut',
                            };
                        @endphp
                        <tr class="border-b border-garis last:border-0">
                            <td class="px-4 py-3">{{ $l->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ $l->kawasan->kode_kawasan ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $l->user->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $l->lokasi }}</td>
                            <td class="px-4 py-3"><span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $l->status }}</span></td>
                            <td class="px-4 py-3">
                                <a href="{{ route('tindak-lanjut.show', $l->id) }}" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Tinjau</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-lembut">Belum ada laporan tumpukan liar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
