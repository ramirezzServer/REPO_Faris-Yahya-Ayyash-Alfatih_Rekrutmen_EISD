@props([
    'label' => [],
    'nilai' => [],
    'batas' => null,
    'satuan' => 'kg',
])

@php
    // Warna diambil literal dari tailwind.config.js (bukan lewat class
    // Tailwind) karena elemen SVG di proyek ini selalu diwarnai lewat
    // atribut presentasi (stroke/fill), mengikuti pola cincin donut yang
    // sudah ada di operator.blade.php/warga.blade.php/publik.blade.php.
    $sinerkaAksi = '#1F4E5A';
    $sinerkaBatas = '#B3352B';
    $sinerkaLembut = '#6E675C';
    $sinerkaGaris = '#E7E0D4';

    $sinerkaLebar = 600;
    $sinerkaTinggi = 200;
    $sinerkaPadKiri = 44;
    $sinerkaPadKanan = 10;
    $sinerkaPadAtas = 10;
    $sinerkaPadBawah = 10;
    $sinerkaLebarPlot = $sinerkaLebar - $sinerkaPadKiri - $sinerkaPadKanan;
    $sinerkaTinggiPlot = $sinerkaTinggi - $sinerkaPadAtas - $sinerkaPadBawah;
    $sinerkaBawah = $sinerkaPadAtas + $sinerkaTinggiPlot;

    $sinerkaN = count($nilai);
    $sinerkaMaks = max(1, $nilai === [] ? 0 : max($nilai), $batas ?? 0);

    $sinerkaFormatSingkat = function (float $n): string {
        if (abs($n) >= 1000) {
            return number_format($n / 1000, $n >= 10000 ? 0 : 1) . 'K';
        }

        return number_format($n, 0);
    };

    $sinerkaX = fn (int $i) => $sinerkaPadKiri + ($sinerkaN > 1 ? $i / ($sinerkaN - 1) * $sinerkaLebarPlot : $sinerkaLebarPlot / 2);
    $sinerkaY = fn (float $v) => $sinerkaPadAtas + (1 - $v / $sinerkaMaks) * $sinerkaTinggiPlot;

    $sinerkaTitik = [];
    foreach ($nilai as $i => $v) {
        $sinerkaTitik[] = [$sinerkaX($i), $sinerkaY((float) $v)];
    }

    $sinerkaGarisD = '';
    foreach ($sinerkaTitik as $i => [$x, $y]) {
        $sinerkaGarisD .= ($i === 0 ? 'M' : ' L') . $x . ',' . $y;
    }

    $sinerkaAreaD = $sinerkaTitik === []
        ? ''
        : $sinerkaGarisD . ' L' . $sinerkaTitik[$sinerkaN - 1][0] . ',' . $sinerkaBawah
            . ' L' . $sinerkaTitik[0][0] . ',' . $sinerkaBawah . ' Z';

    $sinerkaGradId = 'sinerkaGrafikGaris' . uniqid();

    // Renggangkan label sumbu-X bila titik terlalu banyak supaya tidak
    // saling tumpuk.
    $sinerkaLewatiLabel = $sinerkaN > 10 ? 2 : 1;
@endphp

<div {{ $attributes->class(['w-full']) }}>
    <svg viewBox="0 0 {{ $sinerkaLebar }} {{ $sinerkaTinggi }}" preserveAspectRatio="none" class="h-40 w-full">
        <defs>
            <linearGradient id="{{ $sinerkaGradId }}" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="{{ $sinerkaAksi }}" stop-opacity="0.25" />
                <stop offset="100%" stop-color="{{ $sinerkaAksi }}" stop-opacity="0" />
            </linearGradient>
        </defs>

        {{-- Sumbu Y: 3 penanda (0, tengah, maksimum) --}}
        @foreach ([0, $sinerkaMaks / 2, $sinerkaMaks] as $sinerkaNilaiTick)
            <text x="{{ $sinerkaPadKiri - 6 }}" y="{{ $sinerkaY($sinerkaNilaiTick) + 3 }}" text-anchor="end"
                font-size="9" fill="{{ $sinerkaLembut }}">{{ $sinerkaFormatSingkat($sinerkaNilaiTick) }}</text>
        @endforeach

        @if ($batas !== null)
            <line x1="{{ $sinerkaPadKiri }}" y1="{{ $sinerkaY((float) $batas) }}"
                x2="{{ $sinerkaLebar - $sinerkaPadKanan }}" y2="{{ $sinerkaY((float) $batas) }}"
                stroke="{{ $sinerkaBatas }}" stroke-width="1.5" stroke-dasharray="4 3" />
            <text x="{{ $sinerkaLebar - $sinerkaPadKanan }}" y="{{ $sinerkaY((float) $batas) - 4 }}"
                text-anchor="end" font-size="9" fill="{{ $sinerkaBatas }}">Batas {{ $sinerkaFormatSingkat((float) $batas) }}</text>
        @endif

        @if ($sinerkaAreaD !== '')
            <path d="{{ $sinerkaAreaD }}" fill="url(#{{ $sinerkaGradId }})" stroke="none" />
            <path d="{{ $sinerkaGarisD }}" fill="none" stroke="{{ $sinerkaAksi }}" stroke-width="2"
                stroke-linejoin="round" stroke-linecap="round" />

            @foreach ($sinerkaTitik as $i => [$x, $y])
                <circle cx="{{ $x }}" cy="{{ $y }}" r="8" fill="transparent">
                    <title>{{ $label[$i] ?? '' }}: {{ number_format((float) $nilai[$i], 1) }} {{ $satuan }}</title>
                </circle>
            @endforeach

            @php [$sinerkaXAkhir, $sinerkaYAkhir] = $sinerkaTitik[$sinerkaN - 1]; @endphp
            <circle cx="{{ $sinerkaXAkhir }}" cy="{{ $sinerkaYAkhir }}" r="3.5" fill="{{ $sinerkaAksi }}" />
        @else
            <line x1="{{ $sinerkaPadKiri }}" y1="{{ $sinerkaBawah }}" x2="{{ $sinerkaLebar - $sinerkaPadKanan }}"
                y2="{{ $sinerkaBawah }}" stroke="{{ $sinerkaGaris }}" stroke-width="1" />
        @endif
    </svg>

    @if ($sinerkaN > 0)
        <div class="mt-1 flex justify-between pl-[44px] text-[10px] text-lembut">
            @foreach ($label as $i => $sinerkaTeksLabel)
                <span class="{{ $i % $sinerkaLewatiLabel === 0 ? '' : 'invisible' }}">{{ $sinerkaTeksLabel }}</span>
            @endforeach
        </div>
    @endif
</div>
