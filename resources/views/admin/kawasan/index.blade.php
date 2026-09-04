@extends('layouts.app')

@section('judul', 'Kelola Kawasan')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-lg font-semibold text-tinta">Kelola Kawasan</h1>
        <a href="{{ route('admin.kawasan.create') }}" class="rounded-md bg-aksi px-3 py-1.5 text-sm font-medium text-white hover:opacity-90">Tambah Kawasan</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Kode</th>
                        <th class="px-4 py-2 font-medium">RW</th>
                        <th class="px-4 py-2 font-medium">Kelurahan</th>
                        <th class="px-4 py-2 font-medium">Kecamatan</th>
                        <th class="px-4 py-2 text-right font-medium">Jumlah KK</th>
                        <th class="px-4 py-2 text-right font-medium">Jumlah Warga</th>
                        <th class="px-4 py-2 font-medium">Status Siaga</th>
                        <th class="px-4 py-2 font-medium">Aksi</th>
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
                        <tr class="border-b border-garis last:border-0">
                            <td class="px-4 py-3 font-medium text-tinta">{{ $k->kode_kawasan }}</td>
                            <td class="px-4 py-3">{{ $k->nama_rw }}</td>
                            <td class="px-4 py-3">{{ $k->kelurahan }}</td>
                            <td class="px-4 py-3">{{ $k->kecamatan }}</td>
                            <td class="px-4 py-3 text-right">{{ $k->jumlah_kk }}</td>
                            <td class="px-4 py-3 text-right">{{ $k->jumlah_warga }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $row['status_siaga'] }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.kawasan.edit', $k) }}" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Edit</a>
                                    <form method="POST" action="{{ route('admin.kawasan.destroy', $k) }}"
                                        onsubmit="return confirm('Yakin hapus kawasan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-kritis/40 px-3 py-1.5 text-sm font-medium text-kritis hover:bg-kritis/10">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-lembut">Belum ada data kawasan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
