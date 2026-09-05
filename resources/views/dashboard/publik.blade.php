@extends('layouts.publik')

@section('title', 'Neraca Sampah Kota Bandung')

@section('content')
    {{-- Hero --}}
    <section class="flex min-h-screen flex-col justify-center px-6 pb-16 pt-28 lg:px-8">
        <div class="mx-auto grid w-full max-w-6xl gap-12 lg:grid-cols-[1.3fr_1fr] lg:items-center">
            <div>
                <h1 class="text-[40px] font-extrabold leading-[1.05] text-tinta sm:text-[52px] lg:text-[60px]">
                    TPA Sarimukti diproyeksikan penuh <span class="text-aksi">Oktober 2026</span>.
                </h1>

                @if (! $adaData)
                    <p class="mt-6 max-w-[70ch] text-lembut">
                        Belum ada laporan neraca yang terverifikasi, sehingga neraca sampah kota belum dapat ditampilkan.
                    </p>
                @else
                    <p class="mt-6 max-w-[70ch] text-lembut">
                        Pengiriman residu sampah dari tiap kawasan di Kota Bandung dibatasi kuota agar masa pakai
                        TPA dapat diperpanjang. SINERKA mencatat neraca sampah tiap kawasan secara terbuka
                        untuk publik.
                    </p>
                @endif

                <div class="mt-8 flex items-center gap-2 text-sm text-lembut">
                    <span class="inline-flex h-2 w-2 rounded-full bg-kritis"></span>
                    <span><strong id="sinerka-hari-tersisa" class="num text-tinta">&mdash;</strong> hari menuju 1 Oktober 2026</span>
                </div>
            </div>

            <div class="rounded-lg border border-garis bg-permukaan p-8">
                <div id="sinerka-counter" class="num text-[44px] font-extrabold leading-none text-aksi sm:text-[56px]"
                    data-target="{{ (int) round($totalTerolah) }}">0</div>
                <p class="mt-3 text-sm text-lembut">kilogram sampah berhasil ditahan dari TPA</p>
            </div>
        </div>
    </section>

    @if ($adaData)
        {{-- Batang neraca --}}
        <section class="mx-auto max-w-6xl px-6 py-16 lg:px-8">
            <div class="mb-3 flex flex-wrap items-baseline justify-between gap-2">
                <h2 class="text-2xl font-bold">Batang Neraca Sampah Kota</h2>
                <span class="text-sm text-lembut">Total timbulan: <strong class="num text-tinta">{{ number_format($totalTimbulan, 0) }} kg</strong></span>
            </div>
            <div class="flex h-32 w-full overflow-hidden rounded-lg bg-sejuk" role="group" aria-label="Batang neraca sampah kota">
                <div class="flex items-center justify-center overflow-hidden bg-aksi px-2 text-center text-xs font-medium text-white sm:text-sm"
                    style="width: {{ $persenTerolah }}%">
                    <span class="num">Terolah {{ number_format($totalTerolah, 0) }} kg ({{ number_format($persenTerolah, 1) }}%)</span>
                </div>
                <div class="flex items-center justify-center overflow-hidden bg-pasir px-2 text-center text-xs font-medium text-tinta sm:text-sm"
                    style="width: {{ $persenResidu }}%">
                    <span class="num">Residu {{ number_format($totalResidu, 0) }} kg ({{ number_format($persenResidu, 1) }}%)</span>
                </div>
            </div>
            <p class="mt-3 text-sm text-lembut">
                Timbulan dikurangi tonase yang terolah menghasilkan residu &mdash; residu inilah yang dikirim ke TPA
                dan memotong kuota tiap kawasan.
            </p>
        </section>

        {{-- Tren residu kota --}}
        <section class="mx-auto max-w-6xl px-6 py-16 lg:px-8">
            <h2 class="mb-6 text-2xl font-bold">Tren Residu Kota (14 Hari Terakhir)</h2>
            <div class="rounded-lg border border-garis bg-permukaan p-6">
                <x-grafik-garis :label="$trenResiduKota['label']" :nilai="$trenResiduKota['nilai']" satuan="kg" />
            </div>
        </section>

        {{-- Ringkasan --}}
        <section class="mx-auto max-w-6xl px-6 py-16 lg:px-8">
            <h2 class="mb-6 text-2xl font-bold">Ringkasan Kota</h2>
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-5">
                <div>
                    <div class="num text-2xl font-bold text-tinta">{{ number_format($totalTimbulan, 0) }} <span class="text-sm font-normal text-lembut">kg</span></div>
                    <div class="mt-1 text-sm text-lembut">Total Timbulan</div>
                </div>
                <div>
                    <div class="num text-2xl font-bold text-tinta">{{ number_format($totalTerolah, 0) }} <span class="text-sm font-normal text-lembut">kg</span></div>
                    <div class="mt-1 text-sm text-lembut">Total Terolah</div>
                </div>
                <div>
                    <div class="num text-2xl font-bold text-tinta">{{ number_format($totalResidu, 0) }} <span class="text-sm font-normal text-lembut">kg</span></div>
                    <div class="mt-1 text-sm text-lembut">Total Residu</div>
                </div>
                <div>
                    <div class="num text-2xl font-bold text-tinta">{{ number_format($totalEmisi, 1) }} <span class="text-sm font-normal text-lembut">kg CO<sub>2</sub>e</span></div>
                    <div class="mt-1 text-sm text-lembut">Estimasi Emisi CO<sub>2</sub> Dihindari</div>
                </div>
                <div>
                    <div class="num text-2xl font-bold text-tinta">{{ number_format($rasioKemandirian, 1) }}%</div>
                    <div class="mt-1 text-sm text-lembut">Rasio Kemandirian Kota</div>
                </div>
            </div>
        </section>

        {{-- Grid kartu kawasan --}}
        <section class="mx-auto max-w-6xl px-6 py-16 lg:px-8">
            <h2 class="mb-6 text-2xl font-bold">Status Siaga Kawasan</h2>

            @php
                $sinerkaJumlahStatus = [
                    'semua' => $daftarKawasan->count(),
                    'aman' => $daftarKawasan->where('status_siaga', 'aman')->count(),
                    'waspada' => $daftarKawasan->where('status_siaga', 'waspada')->count(),
                    'kritis' => $daftarKawasan->where('status_siaga', 'kritis')->count(),
                ];
            @endphp

            <div class="mb-6 flex flex-wrap gap-2">
                <button type="button" data-filter="semua" aria-pressed="true"
                    class="sinerka-filter-btn rounded-md border border-tinta bg-tinta px-3 py-1.5 text-sm font-medium text-white">
                    Semua <span class="num">({{ $sinerkaJumlahStatus['semua'] }})</span>
                </button>
                <button type="button" data-filter="aman" aria-pressed="false"
                    class="sinerka-filter-btn rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-permukaan">
                    Aman <span class="num">({{ $sinerkaJumlahStatus['aman'] }})</span>
                </button>
                <button type="button" data-filter="waspada" aria-pressed="false"
                    class="sinerka-filter-btn rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-permukaan">
                    Waspada <span class="num">({{ $sinerkaJumlahStatus['waspada'] }})</span>
                </button>
                <button type="button" data-filter="kritis" aria-pressed="false"
                    class="sinerka-filter-btn rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-permukaan">
                    Kritis <span class="num">({{ $sinerkaJumlahStatus['kritis'] }})</span>
                </button>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                @foreach ($daftarKawasan as $k)
                    @php
                        $sinerkaWarnaBadge = match ($k->status_siaga) {
                            'aman' => 'border-aman/40 bg-aman/10 text-aman',
                            'waspada' => 'border-waspada/40 bg-waspada/10 text-waspada',
                            'kritis' => 'border-kritis/40 bg-kritis/10 text-kritis',
                            default => 'border-garis text-lembut',
                        };
                    @endphp
                    <div class="sinerka-kartu-kawasan rounded-lg border border-garis bg-permukaan p-6" data-status="{{ $k->status_siaga }}">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-tinta">{{ $k->kode_kawasan }}</div>
                                <div class="text-sm text-lembut">{{ $k->kelurahan }}</div>
                            </div>
                            <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-medium {{ $sinerkaWarnaBadge }}">
                                {{ ucfirst($k->status_siaga) }}
                            </span>
                        </div>

                        <div class="mt-6 flex flex-col items-center">
                            @if ($k->persentase_sisa !== null)
                                @php
                                    $sinerkaPct = max(0, min(100, (float) $k->persentase_sisa));
                                    $sinerkaR = 40;
                                    $sinerkaKel = 2 * pi() * $sinerkaR;
                                    $sinerkaOffset = $sinerkaKel - ($sinerkaPct / 100 * $sinerkaKel);
                                    $sinerkaStroke = match ($k->status_siaga) {
                                        'aman' => '#2F7A4F',
                                        'waspada' => '#B07A1E',
                                        'kritis' => '#B3352B',
                                        default => '#6E675C',
                                    };
                                @endphp
                                <div class="relative flex h-[104px] w-[104px] items-center justify-center">
                                    <svg width="104" height="104" viewBox="0 0 104 104" class="-rotate-90">
                                        <circle cx="52" cy="52" r="{{ $sinerkaR }}" fill="none" stroke="#E7E0D4" stroke-width="10" />
                                        <circle cx="52" cy="52" r="{{ $sinerkaR }}" fill="none" stroke="{{ $sinerkaStroke }}" stroke-width="10"
                                            stroke-linecap="round" stroke-dasharray="{{ $sinerkaKel }}" stroke-dashoffset="{{ $sinerkaOffset }}" />
                                    </svg>
                                    <span class="num absolute text-sm font-semibold text-tinta">{{ number_format($sinerkaPct, 0) }}%</span>
                                </div>
                                <span class="mt-2 text-xs text-lembut">Sisa kuota residu</span>
                            @else
                                <div class="flex h-[104px] w-[104px] items-center justify-center rounded-full border border-dashed border-garis">
                                    <span class="text-center text-xs text-lembut">Belum<br>berkuota</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Komposisi jalur pengolahan --}}
        <section class="mx-auto max-w-6xl px-6 py-16 lg:px-8">
            <h2 class="mb-6 text-2xl font-bold">Komposisi Jalur Pengolahan</h2>

            @php
                $sinerkaAksiRgb = [31, 78, 90];
                $sinerkaPasirRgb = [255, 221, 176];
                $sinerkaJumlahJalur = $kontribusiJalur->count();
                $sinerkaWarnaJalur = [];
                foreach ($kontribusiJalur as $sinerkaI => $sinerkaJ) {
                    $sinerkaT = $sinerkaJumlahJalur > 1 ? $sinerkaI / ($sinerkaJumlahJalur - 1) : 0;
                    $sinerkaR2 = (int) round($sinerkaAksiRgb[0] + ($sinerkaPasirRgb[0] - $sinerkaAksiRgb[0]) * $sinerkaT);
                    $sinerkaG2 = (int) round($sinerkaAksiRgb[1] + ($sinerkaPasirRgb[1] - $sinerkaAksiRgb[1]) * $sinerkaT);
                    $sinerkaB2 = (int) round($sinerkaAksiRgb[2] + ($sinerkaPasirRgb[2] - $sinerkaAksiRgb[2]) * $sinerkaT);
                    $sinerkaWarnaJalur[] = sprintf('#%02X%02X%02X', $sinerkaR2, $sinerkaG2, $sinerkaB2);
                }
                $sinerkaTotalTonaseJalur = $kontribusiJalur->sum('total_tonase');
                $sinerkaKelilingDonut = 2 * pi() * 70;
                $sinerkaKumulatif = 0;
            @endphp

            <div class="flex flex-col items-center gap-10 lg:flex-row lg:items-center">
                <svg width="180" height="180" viewBox="0 0 180 180" class="-rotate-90 shrink-0">
                    <circle cx="90" cy="90" r="70" fill="none" stroke="#E7E0D4" stroke-width="24" />
                    @foreach ($kontribusiJalur as $sinerkaI => $sinerkaJ)
                        @php
                            $sinerkaPanjang = $sinerkaTotalTonaseJalur > 0
                                ? ((float) $sinerkaJ->total_tonase / $sinerkaTotalTonaseJalur) * $sinerkaKelilingDonut
                                : 0;
                        @endphp
                        <circle cx="90" cy="90" r="70" fill="none" stroke="{{ $sinerkaWarnaJalur[$sinerkaI] }}" stroke-width="24"
                            stroke-dasharray="{{ $sinerkaPanjang }} {{ $sinerkaKelilingDonut - $sinerkaPanjang }}"
                            stroke-dashoffset="{{ -$sinerkaKumulatif }}" />
                        @php
                            $sinerkaKumulatif += $sinerkaPanjang;
                        @endphp
                    @endforeach
                </svg>

                <ul class="w-full max-w-lg space-y-3">
                    @foreach ($kontribusiJalur as $sinerkaI => $sinerkaJ)
                        @php
                            $sinerkaPersenJalur = $sinerkaTotalTonaseJalur > 0 ? (float) $sinerkaJ->total_tonase / $sinerkaTotalTonaseJalur * 100 : 0;
                        @endphp
                        <li class="flex items-center gap-3 text-sm">
                            <span class="h-3 w-3 shrink-0 rounded-full" style="background-color: {{ $sinerkaWarnaJalur[$sinerkaI] }}"></span>
                            <span class="flex-1 truncate text-tinta">{{ $sinerkaJ->nama }}</span>
                            <span class="num text-lembut">{{ number_format((float) $sinerkaJ->total_tonase, 0) }} kg</span>
                            <span class="num w-14 text-right font-semibold text-tinta">{{ number_format($sinerkaPersenJalur, 1) }}%</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- Peringkat kemandirian kawasan --}}
        <section class="mx-auto max-w-6xl px-6 py-16 lg:px-8">
            <h2 class="mb-6 text-2xl font-bold">Peringkat Kemandirian Kawasan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-garis text-left text-lembut">
                            <th class="py-2 pr-4 font-medium">#</th>
                            <th class="py-2 pr-4 font-medium">Kawasan</th>
                            <th class="py-2 pr-4 font-medium">Kelurahan</th>
                            <th class="py-2 pr-4 text-right font-medium">Timbulan (kg)</th>
                            <th class="py-2 pr-4 text-right font-medium">Terolah (kg)</th>
                            <th class="py-2 text-right font-medium">Rasio Kemandirian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($peringkatKawasan as $sinerkaI => $sinerkaP)
                            <tr class="border-b border-garis">
                                <td class="py-3 pr-4 text-lembut">{{ $sinerkaI + 1 }}</td>
                                <td class="py-3 pr-4 font-medium text-tinta">{{ $sinerkaP->kode_kawasan }}</td>
                                <td class="py-3 pr-4 text-lembut">{{ $sinerkaP->kelurahan }}</td>
                                <td class="num py-3 pr-4 text-right">{{ number_format((float) $sinerkaP->timbulan, 0) }}</td>
                                <td class="num py-3 pr-4 text-right">{{ number_format((float) $sinerkaP->terolah, 0) }}</td>
                                <td class="py-3">
                                    <div class="flex items-center justify-end gap-3">
                                        <div class="h-2 w-32 overflow-hidden rounded-full bg-sejuk">
                                            <div class="h-full bg-aksi" style="width: {{ max(0, min(100, (float) $sinerkaP->rasio)) }}%"></div>
                                        </div>
                                        <span class="num w-14 text-right font-semibold text-tinta">{{ number_format((float) $sinerkaP->rasio, 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script>
        (function () {
            var counter = document.getElementById('sinerka-counter');
            if (counter) {
                var target = parseInt(counter.getAttribute('data-target'), 10) || 0;
                var mulai = null;
                var durasi = 1500;

                function langkah(waktu) {
                    if (! mulai) {
                        mulai = waktu;
                    }
                    var progres = Math.min((waktu - mulai) / durasi, 1);
                    var nilai = Math.floor(progres * target);
                    counter.textContent = nilai.toLocaleString('id-ID');
                    if (progres < 1) {
                        requestAnimationFrame(langkah);
                    } else {
                        counter.textContent = target.toLocaleString('id-ID');
                    }
                }

                requestAnimationFrame(langkah);
            }

            var hariEl = document.getElementById('sinerka-hari-tersisa');
            if (hariEl) {
                var sekarang = new Date();
                var target2026 = new Date('2026-10-01T00:00:00');
                var selisihHari = Math.max(0, Math.ceil((target2026 - sekarang) / (1000 * 60 * 60 * 24)));
                hariEl.textContent = selisihHari.toLocaleString('id-ID');
            }

            var tombolFilter = document.querySelectorAll('.sinerka-filter-btn');
            var kartuKawasan = document.querySelectorAll('.sinerka-kartu-kawasan');

            tombolFilter.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var filter = btn.getAttribute('data-filter');

                    tombolFilter.forEach(function (b) {
                        b.classList.remove('bg-tinta', 'text-white', 'border-tinta');
                        b.classList.add('border-garis', 'text-tinta');
                        b.setAttribute('aria-pressed', 'false');
                    });
                    btn.classList.remove('border-garis');
                    btn.classList.add('bg-tinta', 'text-white', 'border-tinta');
                    btn.setAttribute('aria-pressed', 'true');

                    kartuKawasan.forEach(function (k) {
                        var tampil = filter === 'semua' || k.getAttribute('data-status') === filter;
                        k.classList.toggle('hidden', ! tampil);
                    });
                });
            });
        })();
    </script>
@endpush
