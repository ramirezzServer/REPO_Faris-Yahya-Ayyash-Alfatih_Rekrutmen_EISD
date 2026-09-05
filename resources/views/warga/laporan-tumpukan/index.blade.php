@extends('layouts.app')

@section('judul', 'Laporan Tumpukan Liar')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-tinta">Laporan Tumpukan Liar Saya</h1>
            <p class="mt-1 text-sm text-lembut">Riwayat laporan tumpukan liar yang pernah Anda kirim.</p>
        </div>
        <a href="{{ route('warga.laporan-tumpukan.create') }}" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Laporkan Tumpukan</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="max-h-[70vh] overflow-auto">
            <table class="w-full text-sm">
                <thead class="sticky top-0 z-10 bg-permukaan">
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
                        <tr class="border-b border-garis last:border-0 hover:bg-latar">
                            <td class="px-4 py-3">{{ $l->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ $l->lokasi }}</td>
                            <td class="px-4 py-3"><span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $l->status }}</span></td>
                            <td class="px-4 py-3">{{ $l->catatan_tindak_lanjut ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center">
                                <p class="text-sm text-tinta">Anda belum pernah melaporkan tumpukan liar.</p>
                                <p class="mt-1 text-sm text-lembut">Laporan yang Anda buat akan muncul di sini.</p>
                                <a href="{{ route('warga.laporan-tumpukan.create') }}" class="mt-4 inline-block rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Laporkan Tumpukan</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
