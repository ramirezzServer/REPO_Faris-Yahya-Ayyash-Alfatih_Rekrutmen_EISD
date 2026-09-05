@extends('layouts.app')

@section('judul', 'Dashboard Operator')

@section('content')
    @if (! $kawasan)
        <div class="rounded-lg border border-garis bg-permukaan p-6 text-sm text-tinta">
            Akun Anda belum terhubung ke kawasan mana pun. Hubungi admin untuk menghubungkan akun Anda ke sebuah kawasan.
        </div>
    @else
        <div class="grid grid-cols-12 gap-4">
            @if ($periode && ! $sudahLaporHariIni)
                <a href="{{ route('operator.laporan-neraca.create') }}"
                    class="col-span-12 block rounded-lg border border-garis border-l-4 border-l-waspada bg-permukaan p-6 hover:bg-latar">
                    <p class="font-semibold text-tinta">Laporan hari ini belum dibuat</p>
                    <p class="mt-1 text-sm text-lembut">Klik untuk membuat laporan neraca kawasan Anda hari ini &rarr;</p>
                </a>
            @elseif ($periode)
                <div class="col-span-12 rounded-lg border border-garis bg-permukaan p-6">
                    <p class="font-semibold text-tinta">Laporan hari ini sudah dibuat</p>
                    <p class="mt-1 text-sm text-lembut">Terima kasih, laporan neraca untuk hari ini sudah tercatat.</p>
                </div>
            @endif

            {{-- Baris 1: kuota kawasan + tren residu kawasan --}}
            <div class="col-span-12 rounded-lg border border-garis bg-permukaan p-6 lg:col-span-4">
                <p class="mb-4 text-sm font-semibold text-lembut">Kuota Residu {{ $kawasan->kode_kawasan }}</p>

                @if (! $periode)
                    <p class="text-sm text-lembut">Kawasan ini belum memiliki periode kuota aktif. Hubungi admin.</p>
                @else
                    @php
                        $sinerkaPct = max(0, min(100, $periode->persentaseSisa()));
                        $sinerkaR = 46;
                        $sinerkaKel = 2 * pi() * $sinerkaR;
                        $sinerkaOffset = $sinerkaKel - ($sinerkaPct / 100 * $sinerkaKel);
                        $sinerkaStroke = match (true) {
                            $sinerkaPct > 40 => '#2F7A4F',
                            $sinerkaPct >= 15 => '#B07A1E',
                            default => '#B3352B',
                        };
                    @endphp
                    <div class="flex items-center gap-6">
                        <div class="relative flex h-[120px] w-[120px] shrink-0 items-center justify-center">
                            <svg width="120" height="120" viewBox="0 0 120 120" class="-rotate-90">
                                <circle cx="60" cy="60" r="{{ $sinerkaR }}" fill="none" stroke="#E7E0D4" stroke-width="12" />
                                <circle cx="60" cy="60" r="{{ $sinerkaR }}" fill="none" stroke="{{ $sinerkaStroke }}" stroke-width="12"
                                    stroke-linecap="round" stroke-dasharray="{{ $sinerkaKel }}" stroke-dashoffset="{{ $sinerkaOffset }}" />
                            </svg>
                            <span class="num absolute text-lg font-semibold text-tinta">{{ number_format($sinerkaPct, 0) }}%</span>
                        </div>
                        <dl class="space-y-1 text-sm">
                            <div class="flex justify-between gap-4"><dt class="text-lembut">Kuota</dt><dd class="num tabular-nums text-tinta">{{ number_format((float) $periode->kuota_residu_kg, 0) }} kg</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-lembut">Terpakai</dt><dd class="num tabular-nums text-tinta">{{ number_format($periode->kuotaTerpakai(), 0) }} kg</dd></div>
                            <div class="flex justify-between gap-4"><dt class="text-lembut">Sisa</dt><dd class="num tabular-nums text-tinta">{{ number_format($periode->sisaKuota(), 0) }} kg</dd></div>
                        </dl>
                    </div>
                @endif

                <div class="mt-4 flex items-center justify-between border-t border-garis pt-4 text-sm">
                    <span class="text-lembut">Tumpukan liar berstatus baru</span>
                    <span class="num font-semibold text-tinta">{{ $jumlahTumpukanBaru }}</span>
                </div>
            </div>

            <div class="col-span-12 rounded-lg border border-garis bg-permukaan p-6 lg:col-span-8">
                <p class="mb-3 text-sm font-semibold text-lembut">Tren Residu Kawasan (14 Hari)</p>
                @if ($trenResiduKawasan)
                    <x-grafik-garis :label="$trenResiduKawasan['label']" :nilai="$trenResiduKawasan['nilai']"
                        :batas="$periode?->kuota_residu_kg" satuan="kg" />
                @else
                    <p class="text-sm text-lembut">Belum ada data laporan neraca untuk kawasan ini.</p>
                @endif
            </div>

            {{-- Baris 2: tren kemandirian + tabel laporan terakhir --}}
            <div class="col-span-12 rounded-lg border border-garis bg-permukaan p-6 lg:col-span-5">
                <p class="mb-3 text-sm font-semibold text-lembut">Tren Kemandirian Kawasan</p>
                @if ($trenKemandirianKawasan)
                    <x-grafik-garis :label="$trenKemandirianKawasan['label']" :nilai="$trenKemandirianKawasan['nilai']" satuan="%" />
                @else
                    <p class="text-sm text-lembut">Belum ada data laporan neraca untuk kawasan ini.</p>
                @endif
            </div>

            <div class="col-span-12 lg:col-span-7">
                <p class="mb-2 text-sm font-semibold text-lembut">Laporan Neraca Terakhir</p>
                <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
                    <div class="max-h-[70vh] overflow-auto">
                        <table class="w-full text-sm">
                            <thead class="sticky top-0 z-10 bg-permukaan">
                                <tr class="border-b border-garis text-left text-lembut">
                                    <th class="px-4 py-2 font-medium">Tanggal</th>
                                    <th class="px-4 py-2 text-right font-medium">Timbulan (kg)</th>
                                    <th class="px-4 py-2 text-right font-medium">Residu (kg)</th>
                                    <th class="px-4 py-2 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($laporanTerakhir as $l)
                                    @php
                                        $badge = match ($l->status) {
                                            'menunggu' => 'border-garis text-lembut',
                                            'terverifikasi' => 'border-aman/40 bg-aman/10 text-aman',
                                            'ditolak' => 'border-kritis/40 bg-kritis/10 text-kritis',
                                            default => 'border-garis text-lembut',
                                        };
                                    @endphp
                                    <tr class="cursor-pointer border-b border-garis last:border-0 hover:bg-latar"
                                        onclick="window.location='{{ route('operator.laporan-neraca.show', $l) }}'">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('operator.laporan-neraca.show', $l) }}" class="font-medium text-tinta hover:underline">{{ $l->tanggal_laporan->format('d M Y') }}</a>
                                        </td>
                                        <td class="num px-4 py-3 text-right tabular-nums">{{ number_format((float) $l->timbulan_kg, 2) }}</td>
                                        <td class="num px-4 py-3 text-right tabular-nums">{{ number_format((float) $l->residu_kg, 2) }}</td>
                                        <td class="px-4 py-3"><span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $l->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-12 text-center">
                                            <p class="text-sm text-tinta">Belum ada laporan neraca.</p>
                                            <p class="mt-1 text-sm text-lembut">Laporan yang Anda buat akan muncul di sini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
