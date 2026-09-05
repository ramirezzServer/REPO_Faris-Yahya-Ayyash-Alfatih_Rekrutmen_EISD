@extends('layouts.app')

@section('judul', 'Dashboard Admin')

@section('content')
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
        <a href="{{ route('admin.verifikasi-laporan.index') }}"
            class="rounded-lg border border-garis bg-permukaan p-6 hover:bg-latar {{ $jumlahMenunggu > 0 ? 'border-l-4 border-l-kritis' : '' }}">
            <div class="num text-4xl font-extrabold text-tinta">{{ $jumlahMenunggu }}</div>
            <p class="mt-1 text-sm text-lembut">Laporan menunggu verifikasi</p>
        </a>

        <div class="rounded-lg border border-garis bg-permukaan p-6 lg:col-span-2">
            <p class="mb-3 text-sm font-semibold text-lembut">Kawasan per Status Siaga</p>
            <div class="grid grid-cols-3 gap-4">
                <div class="border-l-4 border-l-aman pl-3">
                    <div class="num text-2xl font-bold text-tinta">{{ $jumlahPerStatus['aman'] }}</div>
                    <div class="text-xs text-lembut">Aman</div>
                </div>
                <div class="border-l-4 border-l-waspada pl-3">
                    <div class="num text-2xl font-bold text-tinta">{{ $jumlahPerStatus['waspada'] }}</div>
                    <div class="text-xs text-lembut">Waspada</div>
                </div>
                <div class="border-l-4 border-l-kritis pl-3">
                    <div class="num text-2xl font-bold text-tinta">{{ $jumlahPerStatus['kritis'] }}</div>
                    <div class="text-xs text-lembut">Kritis</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <p class="mb-2 text-sm font-semibold text-lembut">Laporan Menunggu Terbaru</p>
        <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-garis text-left text-lembut">
                            <th class="px-4 py-2 font-medium">Kawasan</th>
                            <th class="px-4 py-2 font-medium">Tanggal</th>
                            <th class="px-4 py-2 text-right font-medium">Residu (kg)</th>
                            <th class="px-4 py-2 text-right font-medium">Sisa Kuota Periode (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporanMenunggu as $l)
                            <tr class="cursor-pointer border-b border-garis last:border-0 hover:bg-latar"
                                onclick="window.location='{{ route('admin.verifikasi-laporan.show', $l->id) }}'">
                                <td class="px-4 py-3 font-medium text-tinta">
                                    <a href="{{ route('admin.verifikasi-laporan.show', $l->id) }}" class="hover:underline">{{ $l->kawasan->kode_kawasan ?? '-' }}</a>
                                </td>
                                <td class="px-4 py-3">{{ $l->tanggal_laporan->format('d M Y') }}</td>
                                <td class="num px-4 py-3 text-right tabular-nums">{{ number_format((float) $l->residu_kg, 2) }}</td>
                                <td class="num px-4 py-3 text-right tabular-nums">{{ number_format($l->periodeKuota->sisaKuota(), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-lembut">Tidak ada laporan yang menunggu verifikasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
        <div class="rounded-lg border border-garis bg-permukaan p-6 {{ $kawasanKritis->isNotEmpty() ? 'border-l-4 border-l-kritis' : '' }}">
            <p class="mb-3 text-sm font-semibold text-lembut">Kawasan Berstatus Kritis</p>
            @forelse ($kawasanKritis as $k)
                <a href="{{ route('admin.kawasan.edit', $k) }}" class="block border-b border-garis py-2 text-sm last:border-0 hover:text-aksi">
                    <span class="font-medium text-tinta">{{ $k->kode_kawasan }}</span> — {{ $k->kelurahan }}
                </a>
            @empty
                <p class="text-sm text-lembut">Tidak ada kawasan berstatus kritis saat ini.</p>
            @endforelse
        </div>

        <div class="rounded-lg border border-garis bg-permukaan p-6">
            <p class="mb-3 text-sm font-semibold text-lembut">Periode Kuota Segera Berakhir (14 hari)</p>
            @forelse ($periodeSegeraBerakhir as $p)
                <div class="flex items-center justify-between border-b border-garis py-2 text-sm last:border-0">
                    <span class="font-medium text-tinta">{{ $p->kawasan->kode_kawasan ?? '-' }}</span>
                    <span class="text-lembut">berakhir {{ $p->tanggal_selesai->format('d M Y') }}</span>
                </div>
            @empty
                <p class="text-sm text-lembut">Tidak ada periode kuota yang segera berakhir.</p>
            @endforelse
        </div>
    </div>

    <div class="mt-6 rounded-lg border border-garis bg-permukaan p-6">
        <div class="num text-2xl font-bold text-tinta">{{ $jumlahTumpukanBaru }}</div>
        <p class="mt-1 text-sm text-lembut">Laporan tumpukan liar berstatus baru</p>
    </div>
@endsection
