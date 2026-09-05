@props([
    'label' => [],
    'terpakai' => [],
    'sisa' => [],
])

@php
    // Nilai terpakai/sisa adalah PERSENTASE (0-100), bukan kg mentah --
    // kawasan berbeda ukuran kuotanya jauh, jadi persentase-dari-kuota
    // dipakai supaya semua baris tetap sebanding pada skala yang sama.
    $sinerkaKritis = '#B3352B';
    $sinerkaAksi = '#1F4E5A';
    $sinerkaLembut = '#6E675C';
    $sinerkaTinta = '#2A2620';

    $sinerkaN = count($label);
    $sinerkaTinggiBaris = 28;
    $sinerkaTinggiBar = 14;
    $sinerkaPadKiri = 64;
    $sinerkaPadKanan = 40;
    $sinerkaLebar = 500;
    $sinerkaLebarPlot = $sinerkaLebar - $sinerkaPadKiri - $sinerkaPadKanan;
    $sinerkaTinggi = max($sinerkaTinggiBaris, $sinerkaN * $sinerkaTinggiBaris);
@endphp

<svg viewBox="0 0 {{ $sinerkaLebar }} {{ $sinerkaTinggi }}" class="w-full" style="height: {{ $sinerkaN * 28 }}px">
    @forelse ($label as $i => $sinerkaTeksLabel)
        @php
            $sinerkaTerpakai = max(0, min(100, (float) ($terpakai[$i] ?? 0)));
            $sinerkaSisa = max(0, min(100, (float) ($sisa[$i] ?? 0)));
            $sinerkaY = $i * $sinerkaTinggiBaris + ($sinerkaTinggiBaris - $sinerkaTinggiBar) / 2;
            $sinerkaLebarTerpakai = $sinerkaTerpakai / 100 * $sinerkaLebarPlot;
            $sinerkaLebarSisa = $sinerkaSisa / 100 * $sinerkaLebarPlot;
        @endphp
        <g>
            <title>{{ $sinerkaTeksLabel }}: {{ number_format($sinerkaTerpakai, 1) }}% terpakai, {{ number_format($sinerkaSisa, 1) }}% sisa</title>
            <text x="0" y="{{ $sinerkaY + $sinerkaTinggiBar / 2 + 3 }}" font-size="10" fill="{{ $sinerkaTinta }}">{{ $sinerkaTeksLabel }}</text>
            <rect x="{{ $sinerkaPadKiri }}" y="{{ $sinerkaY }}" width="{{ $sinerkaLebarTerpakai }}" height="{{ $sinerkaTinggiBar }}"
                fill="{{ $sinerkaKritis }}" rx="2" />
            <rect x="{{ $sinerkaPadKiri + $sinerkaLebarTerpakai }}" y="{{ $sinerkaY }}" width="{{ $sinerkaLebarSisa }}" height="{{ $sinerkaTinggiBar }}"
                fill="{{ $sinerkaAksi }}" rx="2" />
            <text x="{{ $sinerkaPadKiri + $sinerkaLebarPlot + 6 }}" y="{{ $sinerkaY + $sinerkaTinggiBar / 2 + 3 }}"
                font-size="10" fill="{{ $sinerkaLembut }}">{{ number_format($sinerkaTerpakai, 0) }}%</text>
        </g>
    @empty
        <text x="0" y="14" font-size="10" fill="{{ $sinerkaLembut }}">Belum ada data.</text>
    @endforelse
</svg>
