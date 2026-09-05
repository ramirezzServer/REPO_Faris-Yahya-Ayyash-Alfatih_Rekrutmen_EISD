@props([
    'label' => '',
    'nilai' => 0,
    'satuan' => '',
    'sparkline' => null,
    'delta' => null,
    'deltaBaik' => true,
])

@php
    $sinerkaAksi = '#1F4E5A';

    $sinerkaTitikSpark = [];
    if (is_array($sparkline) && count($sparkline) > 1) {
        $sinerkaMaksSpark = max($sparkline);
        $sinerkaMinSpark = min($sparkline);
        $sinerkaRentang = max(0.0001, $sinerkaMaksSpark - $sinerkaMinSpark);
        $sinerkaNSpark = count($sparkline);

        foreach (array_values($sparkline) as $i => $v) {
            $x = $i / ($sinerkaNSpark - 1) * 100;
            $y = 28 - (($v - $sinerkaMinSpark) / $sinerkaRentang) * 26 - 1;
            $sinerkaTitikSpark[] = $x . ',' . $y;
        }
    }
@endphp

<div {{ $attributes->class(['rounded-lg border border-garis bg-permukaan p-6']) }}>
    <p class="num text-2xl font-bold text-tinta">
        {{ number_format((float) $nilai) }}
        @if ($satuan)
            <span class="text-sm font-normal text-lembut">{{ $satuan }}</span>
        @endif
    </p>
    <p class="mt-1 text-sm text-lembut">{{ $label }}</p>

    @if ($delta !== null)
        <p class="num mt-2 inline-flex items-center gap-1 text-xs font-medium {{ $deltaBaik ? 'text-aman' : 'text-kritis' }}">
            <svg viewBox="0 0 10 10" class="h-2.5 w-2.5 {{ $delta < 0 ? 'rotate-180' : '' }}" fill="currentColor">
                <path d="M5 0 L10 10 L0 10 Z" />
            </svg>
            {{ $delta > 0 ? '+' : '' }}{{ number_format($delta, 1) }}%
        </p>
    @endif

    @if ($sinerkaTitikSpark !== [])
        <svg viewBox="0 0 100 30" preserveAspectRatio="none" class="mt-3 h-6 w-full">
            <polyline points="{{ implode(' ', $sinerkaTitikSpark) }}" fill="none" stroke="{{ $sinerkaAksi }}" stroke-width="1.5"
                stroke-linejoin="round" stroke-linecap="round" />
        </svg>
    @endif
</div>
