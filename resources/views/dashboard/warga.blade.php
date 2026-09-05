@extends('layouts.app')

@section('judul', 'Dashboard Warga')

@section('content')
    @if (! $kawasan)
        <div class="rounded-lg border border-garis bg-permukaan p-6 text-sm text-tinta">
            Akun Anda belum terhubung ke kawasan mana pun. Hubungi admin untuk menghubungkan akun Anda ke sebuah kawasan.
        </div>
    @else
        <div class="grid grid-cols-12 gap-4">
            {{-- Baris 1: status siaga + tren kemandirian --}}
            <div class="col-span-12 rounded-lg border border-garis bg-permukaan p-6 lg:col-span-4">
                <p class="mb-4 text-sm font-semibold text-lembut">Status Kesiagaan {{ $kawasan->kode_kawasan }} — {{ $kawasan->kelurahan }}</p>

                @if (! $periode)
                    <p class="text-sm text-lembut">Kawasan Anda belum memiliki periode kuota aktif.</p>
                @else
                    @php
                        $sinerkaPct = max(0, min(100, $periode->persentaseSisa()));
                        $sinerkaR = 46;
                        $sinerkaKel = 2 * pi() * $sinerkaR;
                        $sinerkaOffset = $sinerkaKel - ($sinerkaPct / 100 * $sinerkaKel);
                        $sinerkaWarna = match ($kawasan->status_siaga) {
                            'aman' => ['#2F7A4F', 'border-aman/40 bg-aman/10 text-aman'],
                            'waspada' => ['#B07A1E', 'border-waspada/40 bg-waspada/10 text-waspada'],
                            'kritis' => ['#B3352B', 'border-kritis/40 bg-kritis/10 text-kritis'],
                            default => ['#6E675C', 'border-garis text-lembut'],
                        };
                    @endphp
                    <div class="flex items-center gap-6">
                        <div class="relative flex h-[120px] w-[120px] shrink-0 items-center justify-center">
                            <svg width="120" height="120" viewBox="0 0 120 120" class="-rotate-90">
                                <circle cx="60" cy="60" r="{{ $sinerkaR }}" fill="none" stroke="#E7E0D4" stroke-width="12" />
                                <circle cx="60" cy="60" r="{{ $sinerkaR }}" fill="none" stroke="{{ $sinerkaWarna[0] }}" stroke-width="12"
                                    stroke-linecap="round" stroke-dasharray="{{ $sinerkaKel }}" stroke-dashoffset="{{ $sinerkaOffset }}" />
                            </svg>
                            <span class="num absolute text-lg font-semibold text-tinta">{{ number_format($sinerkaPct, 0) }}%</span>
                        </div>
                        <div>
                            <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $sinerkaWarna[1] }}">{{ ucfirst($kawasan->status_siaga) }}</span>
                            <p class="mt-2 text-sm text-lembut">Sisa kuota residu: <span class="num tabular-nums text-tinta">{{ number_format($periode->sisaKuota(), 0) }} kg</span> dari {{ number_format((float) $periode->kuota_residu_kg, 0) }} kg</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-span-12 rounded-lg border border-garis bg-permukaan p-6 lg:col-span-8">
                <p class="mb-3 text-sm font-semibold text-lembut">Tren Kemandirian Kawasan</p>
                @if ($trenKemandirianKawasan)
                    <x-grafik-garis :label="$trenKemandirianKawasan['label']" :nilai="$trenKemandirianKawasan['nilai']" satuan="%" />
                @else
                    <p class="text-sm text-lembut">Belum ada data laporan neraca untuk kawasan ini.</p>
                @endif
            </div>

            {{-- Baris 2: ringkasan laporan saya + tabel laporan terakhir --}}
            <div class="col-span-12 grid grid-cols-3 gap-4 lg:col-span-4 lg:grid-cols-1">
                <div class="rounded-lg border border-garis bg-permukaan p-6 border-l-4 border-l-garis">
                    <div class="num text-2xl font-bold text-tinta">{{ $jumlahPerStatusTumpukan['baru'] }}</div>
                    <div class="text-xs text-lembut">Baru</div>
                </div>
                <div class="rounded-lg border border-garis bg-permukaan p-6 border-l-4 border-l-waspada">
                    <div class="num text-2xl font-bold text-tinta">{{ $jumlahPerStatusTumpukan['diproses'] }}</div>
                    <div class="text-xs text-lembut">Diproses</div>
                </div>
                <div class="rounded-lg border border-garis bg-permukaan p-6 border-l-4 border-l-aman">
                    <div class="num text-2xl font-bold text-tinta">{{ $jumlahPerStatusTumpukan['selesai'] }}</div>
                    <div class="text-xs text-lembut">Selesai</div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-8">
                <p class="mb-2 text-sm font-semibold text-lembut">Laporan Tumpukan Liar Terakhir</p>
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
                                @forelse ($laporanTerakhir as $l)
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
                                        <td colspan="4" class="px-4 py-12 text-center">
                                            <p class="text-sm text-tinta">Anda belum pernah melaporkan tumpukan liar.</p>
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
