@extends('layouts.app')

@section('judul', 'Kelola Periode Kuota')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-lg font-semibold text-tinta">Kelola Periode Kuota</h1>
        <a href="{{ route('admin.periode-kuota.create') }}" class="rounded-md bg-aksi px-3 py-1.5 text-sm font-medium text-white hover:opacity-90">Tambah Periode</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Kawasan</th>
                        <th class="px-4 py-2 font-medium">Mulai</th>
                        <th class="px-4 py-2 font-medium">Selesai</th>
                        <th class="px-4 py-2 text-right font-medium">Kuota Total (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">Terpakai (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">Sisa (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">% Sisa</th>
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $row)
                        @php
                            $p = $row['model'];
                        @endphp
                        <tr class="border-b border-garis last:border-0">
                            <td class="px-4 py-3 font-medium text-tinta">{{ $p->kawasan->kode_kawasan }}</td>
                            <td class="px-4 py-3">{{ $p->tanggal_mulai->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ $p->tanggal_selesai->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format((float) $p->kuota_residu_kg, 2) }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($row['kuota_terpakai'], 2) }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($row['sisa_kuota'], 2) }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($row['persentase_sisa'], 1) }}%</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-md border border-garis px-2 py-0.5 text-xs font-medium text-tinta">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $p->status === 'aktif' ? 'bg-aksi' : 'bg-garis' }}"></span>
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($p->status === 'aktif')
                                    <form method="POST" action="{{ route('admin.periode-kuota.tutup', $p->id) }}"
                                        onsubmit="return confirm('Tutup periode kuota ini?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="rounded-md border border-waspada/40 px-3 py-1.5 text-sm font-medium text-waspada hover:bg-waspada/10">Tutup</button>
                                    </form>
                                @else
                                    <span class="text-lembut">&mdash;</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-lembut">Belum ada data periode kuota.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
