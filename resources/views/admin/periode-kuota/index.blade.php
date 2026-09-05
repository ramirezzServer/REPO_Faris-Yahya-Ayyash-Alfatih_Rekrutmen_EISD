@extends('layouts.app')

@section('judul', 'Kelola Periode Kuota')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-tinta">Kelola Periode Kuota</h1>
            <p class="mt-1 text-sm text-lembut">Kelola periode kuota residu tiap kawasan, termasuk menutup periode yang telah berakhir.</p>
        </div>
        <a href="{{ route('admin.periode-kuota.create') }}" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Tambah Periode</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="max-h-[70vh] overflow-auto">
            <table class="w-full text-sm">
                <thead class="sticky top-0 z-10 bg-permukaan">
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Kawasan</th>
                        <th class="px-4 py-2 font-medium">Mulai</th>
                        <th class="px-4 py-2 font-medium">Selesai</th>
                        <th class="px-4 py-2 text-right font-medium">Kuota Total (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">Terpakai (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">Sisa (kg)</th>
                        <th class="px-4 py-2 text-right font-medium">% Sisa</th>
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftar as $row)
                        @php
                            $p = $row['model'];
                        @endphp
                        <tr class="border-b border-garis last:border-0 hover:bg-latar">
                            <td class="px-4 py-3 font-medium text-tinta">{{ $p->kawasan->kode_kawasan }}</td>
                            <td class="px-4 py-3">{{ $p->tanggal_mulai->format('d M Y') }}</td>
                            <td class="px-4 py-3">{{ $p->tanggal_selesai->format('d M Y') }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ number_format((float) $p->kuota_residu_kg, 2) }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ number_format($row['kuota_terpakai'], 2) }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ number_format($row['sisa_kuota'], 2) }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ number_format($row['persentase_sisa'], 1) }}%</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 rounded-md border border-garis px-2 py-0.5 text-xs font-medium text-tinta">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $p->status === 'aktif' ? 'bg-aksi' : 'bg-garis' }}"></span>
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                @if ($p->status === 'aktif')
                                    <form method="POST" action="{{ route('admin.periode-kuota.tutup', $p->id) }}"
                                        onsubmit="return sinerkaKonfirmasi(this, 'Tutup periode kuota {{ $p->kawasan->kode_kawasan }} ({{ $p->tanggal_mulai->format('d M Y') }} – {{ $p->tanggal_selesai->format('d M Y') }})?')">
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
                            <td colspan="9" class="px-4 py-12 text-center">
                                <p class="text-sm text-tinta">Belum ada data periode kuota.</p>
                                <p class="mt-1 text-sm text-lembut">Buat periode kuota agar operator kawasan dapat mulai mencatat laporan neraca.</p>
                                <a href="{{ route('admin.periode-kuota.create') }}" class="mt-4 inline-block rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Tambah Periode</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
