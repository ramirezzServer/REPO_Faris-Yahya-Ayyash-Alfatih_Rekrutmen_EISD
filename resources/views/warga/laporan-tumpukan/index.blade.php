@extends('layouts.app')

@section('judul', 'Laporan Tumpukan Liar')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-lg font-semibold text-tinta">Laporan Tumpukan Liar Saya</h1>
        <a href="{{ route('warga.laporan-tumpukan.create') }}" class="rounded-md bg-aksi px-3 py-1.5 text-sm font-medium text-white hover:opacity-90">Laporkan Tumpukan</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Tanggal</th>
                        <th class="px-4 py-2 font-medium">Lokasi</th>
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 font-medium">Catatan Tindak Lanjut</th>
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
                            <td class="px-4 py-3">{{ $l->lokasi }}</td>
                            <td class="px-4 py-3"><span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $l->status }}</span></td>
                            <td class="px-4 py-3">{{ $l->catatan_tindak_lanjut ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-lembut">Anda belum pernah melaporkan tumpukan liar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
