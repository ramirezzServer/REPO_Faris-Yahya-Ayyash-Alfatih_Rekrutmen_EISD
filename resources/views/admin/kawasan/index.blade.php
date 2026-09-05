@extends('layouts.app')

@section('judul', 'Kelola Kawasan')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-tinta">Kelola Kawasan</h1>
            <p class="mt-1 text-sm text-lembut">Kelola data kawasan RW, termasuk status siaga dan kuota residu tiap periode.</p>
        </div>
        <a href="{{ route('admin.kawasan.create') }}" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Tambah Kawasan</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="max-h-[70vh] overflow-auto">
            <table class="w-full text-sm">
                <thead class="sticky top-0 z-10 bg-permukaan">
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Kode</th>
                        <th class="px-4 py-2 font-medium">RW</th>
                        <th class="px-4 py-2 font-medium">Kelurahan</th>
                        <th class="px-4 py-2 font-medium">Kecamatan</th>
                        <th class="px-4 py-2 text-right font-medium">Jumlah KK</th>
                        <th class="px-4 py-2 text-right font-medium">Jumlah Warga</th>
                        <th class="px-4 py-2 font-medium">Status Siaga</th>
                        <th class="px-4 py-2 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $row)
                        @php
                            $k = $row['model'];
                        @endphp
                        @php
                            $badge = match ($row['status_siaga']) {
                                'aman' => 'border-aman/40 bg-aman/10 text-aman',
                                'waspada' => 'border-waspada/40 bg-waspada/10 text-waspada',
                                'kritis' => 'border-kritis/40 bg-kritis/10 text-kritis',
                                default => 'border-garis text-lembut',
                            };
                        @endphp
                        <tr class="border-b border-garis last:border-0 hover:bg-latar">
                            <td class="px-4 py-3 font-medium text-tinta">{{ $k->kode_kawasan }}</td>
                            <td class="px-4 py-3">{{ $k->nama_rw }}</td>
                            <td class="px-4 py-3">{{ $k->kelurahan }}</td>
                            <td class="px-4 py-3">{{ $k->kecamatan }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ $k->jumlah_kk }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ $k->jumlah_warga }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $row['status_siaga'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.kawasan.edit', $k) }}" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Edit</a>
                                    <form method="POST" action="{{ route('admin.kawasan.destroy', $k) }}"
                                        onsubmit="return sinerkaKonfirmasi(this, 'Hapus kawasan {{ $k->kode_kawasan }} — {{ $k->nama_rw }}, {{ $k->kelurahan }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-kritis/40 px-3 py-1.5 text-sm font-medium text-kritis hover:bg-kritis/10">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <p class="text-sm text-tinta">Belum ada data kawasan.</p>
                                <p class="mt-1 text-sm text-lembut">Tambahkan kawasan pertama untuk mulai mencatat neraca sampahnya.</p>
                                <a href="{{ route('admin.kawasan.create') }}" class="mt-4 inline-block rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Tambah Kawasan</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
