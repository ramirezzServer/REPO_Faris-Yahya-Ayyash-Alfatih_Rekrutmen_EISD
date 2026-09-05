@extends('layouts.app')

@section('judul', 'Dashboard Admin')

@section('content')
    <div class="grid grid-cols-12 gap-4">
        {{-- Baris 1: tren residu kota + ringkasan angka --}}
        <div class="col-span-12 rounded-lg border border-garis bg-permukaan p-6 lg:col-span-8">
            <p class="mb-3 text-sm font-semibold text-lembut">Tren Residu Kota (14 Hari)</p>
            <x-grafik-garis :label="$trenResiduKota['label']" :nilai="$trenResiduKota['nilai']" :batas="$kuotaKota" satuan="kg" />
        </div>

        <div class="col-span-12 flex flex-col gap-4 lg:col-span-4">
            <a href="{{ route('admin.verifikasi-laporan.index') }}" class="block">
                <x-kartu-angka label="Laporan menunggu verifikasi" :nilai="$jumlahMenunggu" satuan="laporan"
                    class="hover:bg-latar {{ $jumlahMenunggu > 0 ? 'border-l-4 border-l-kritis' : '' }}" />
            </a>

            <div class="rounded-lg border border-garis bg-permukaan p-6">
                <p class="mb-3 text-sm font-semibold text-lembut">Kawasan per Status Siaga</p>
                <div class="grid grid-cols-3 gap-3">
                    <div class="border-l-4 border-l-aman pl-3">
                        <div class="num text-xl font-bold text-tinta">{{ $jumlahPerStatus['aman'] }}</div>
                        <div class="text-xs text-lembut">Aman</div>
                    </div>
                    <div class="border-l-4 border-l-waspada pl-3">
                        <div class="num text-xl font-bold text-tinta">{{ $jumlahPerStatus['waspada'] }}</div>
                        <div class="text-xs text-lembut">Waspada</div>
                    </div>
                    <div class="border-l-4 border-l-kritis pl-3">
                        <div class="num text-xl font-bold text-tinta">{{ $jumlahPerStatus['kritis'] }}</div>
                        <div class="text-xs text-lembut">Kritis</div>
                    </div>
                </div>
            </div>

            <x-kartu-angka label="Laporan tumpukan liar berstatus baru" :nilai="$jumlahTumpukanBaru" satuan="laporan" />
        </div>

        {{-- Baris 2: laju pemakaian kuota + tabel laporan menunggu --}}
        <div class="col-span-12 rounded-lg border border-garis bg-permukaan p-6 lg:col-span-5">
            <p class="mb-3 text-sm font-semibold text-lembut">Laju Pemakaian Kuota per Kawasan</p>
            <x-grafik-batang :label="$lajuKuota->pluck('kode_kawasan')->all()" :terpakai="$lajuKuota->pluck('persentase_terpakai')->all()"
                :sisa="$lajuKuota->map(fn ($r) => 100 - $r->persentase_terpakai)->all()" />
        </div>

        <div class="col-span-12 lg:col-span-7">
            <p class="mb-2 text-sm font-semibold text-lembut">Laporan Menunggu Verifikasi Terbaru</p>
            <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
                <div class="max-h-[70vh] overflow-auto">
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 z-10 bg-permukaan">
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
                                    <td colspan="4" class="px-4 py-12 text-center">
                                        <p class="text-sm text-tinta">Tidak ada laporan yang menunggu verifikasi.</p>
                                        <p class="mt-1 text-sm text-lembut">Semua laporan neraca yang masuk sudah ditinjau.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Baris 3: kawasan kritis + periode segera berakhir --}}
        <div class="col-span-12 rounded-lg border border-garis bg-permukaan p-6 lg:col-span-6 {{ $kawasanKritis->isNotEmpty() ? 'border-l-4 border-l-kritis' : '' }}">
            <p class="mb-3 text-sm font-semibold text-lembut">Kawasan Berstatus Kritis</p>
            @forelse ($kawasanKritis as $k)
                <a href="{{ route('admin.kawasan.edit', $k) }}" class="block border-b border-garis py-2 text-sm last:border-0 hover:text-aksi">
                    <span class="font-medium text-tinta">{{ $k->kode_kawasan }}</span> — {{ $k->kelurahan }}
                </a>
            @empty
                <p class="text-sm text-lembut">Tidak ada kawasan berstatus kritis saat ini.</p>
            @endforelse
        </div>

        <div class="col-span-12 rounded-lg border border-garis bg-permukaan p-6 lg:col-span-6">
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
@endsection
