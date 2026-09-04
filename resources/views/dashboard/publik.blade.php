@extends('layouts.app')

@section('title', 'Neraca Sampah Kota Bandung')

@section('content')
    <h1 class="h3 mb-3">Neraca Sampah Kota Bandung</h1>

    <p class="text-muted">
        TPA Sarimukti diproyeksikan mencapai kapasitas maksimum pada Oktober 2026. Untuk memperpanjang
        masa pakai TPA, pengiriman residu sampah dari setiap kawasan di Kota Bandung dibatasi kuota.
        SINERKA mencatat neraca sampah tiap kawasan &mdash; berapa yang ditimbulkan, berapa yang diolah,
        dan berapa residu yang dikirim ke TPA &mdash; sehingga sisa kuota dan status kesiagaan tiap
        kawasan dapat dipantau publik.
    </p>

    @if (! $adaData)
        <div class="alert alert-info" role="alert">
            Belum ada laporan neraca yang terverifikasi, sehingga neraca sampah kota belum dapat ditampilkan.
        </div>
    @else
        {{-- Batang neraca --}}
        <section class="mb-4">
            <h2 class="h5 mb-2">Batang Neraca Sampah Kota</h2>
            <div class="progress" style="height: 2.75rem; font-size: .9rem;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persenTerolah }}%">
                    Terolah {{ number_format($totalTerolah, 0) }} kg ({{ number_format($persenTerolah, 1) }}%)
                </div>
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $persenResidu }}%">
                    Residu {{ number_format($totalResidu, 0) }} kg ({{ number_format($persenResidu, 1) }}%)
                </div>
            </div>
        </section>

        {{-- Ringkasan --}}
        <section class="mb-4">
            <h2 class="h5 mb-2">Ringkasan Kota</h2>
            <div class="row g-3">
                <div class="col-6 col-md">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Total Timbulan</div>
                        <div class="fs-5 fw-semibold">{{ number_format($totalTimbulan, 0) }} kg</div>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Total Terolah</div>
                        <div class="fs-5 fw-semibold">{{ number_format($totalTerolah, 0) }} kg</div>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Total Residu</div>
                        <div class="fs-5 fw-semibold">{{ number_format($totalResidu, 0) }} kg</div>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Estimasi Emisi CO<sub>2</sub> Dihindari</div>
                        <div class="fs-5 fw-semibold">{{ number_format($totalEmisi, 1) }} kg CO<sub>2</sub>e</div>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="border rounded p-3 h-100">
                        <div class="text-muted small">Rasio Kemandirian Kota</div>
                        <div class="fs-5 fw-semibold">{{ number_format($rasioKemandirian, 1) }}%</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Status siaga kawasan --}}
        <section class="mb-4">
            <h2 class="h5 mb-2">Status Siaga Kawasan</h2>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Kawasan</th>
                            <th>Kelurahan</th>
                            <th>Status Siaga</th>
                            <th class="text-end">Kuota Residu (kg)</th>
                            <th class="text-end">Sisa Kuota (kg)</th>
                            <th style="min-width: 12rem;">Persentase Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarKawasan as $k)
                            @php($badge = match ($k->status_siaga) {
                                'aman' => 'bg-success',
                                'waspada' => 'bg-warning text-dark',
                                'kritis' => 'bg-danger',
                                default => 'bg-secondary',
                            })
                            <tr>
                                <td>{{ $k->kode_kawasan }}</td>
                                <td>{{ $k->kelurahan }}</td>
                                <td><span class="badge {{ $badge }}">{{ $k->status_siaga }}</span></td>
                                <td class="text-end">
                                    {{ $k->kuota_residu_kg !== null ? number_format((float) $k->kuota_residu_kg, 0) : '—' }}
                                </td>
                                <td class="text-end">
                                    {{ $k->kuota_residu_kg !== null ? number_format((float) $k->sisa_kuota, 0) : '—' }}
                                </td>
                                <td>
                                    @if ($k->persentase_sisa !== null)
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ max(0, min(100, $k->persentase_sisa)) }}%">
                                                {{ number_format($k->persentase_sisa, 1) }}%
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Belum ada periode kuota aktif</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Kontribusi jalur pengolahan --}}
        <section class="mb-4">
            <h2 class="h5 mb-2">Kontribusi Jalur Pengolahan</h2>
            @foreach ($kontribusiJalur as $j)
                <div class="mb-2">
                    <div class="d-flex justify-content-between small">
                        <span>{{ $j->nama }}</span>
                        <span>{{ number_format((float) $j->total_tonase, 0) }} kg</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-info text-dark" role="progressbar"
                            style="width: {{ $kontribusiMaks > 0 ? $j->total_tonase / $kontribusiMaks * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        {{-- Peringkat kemandirian kawasan --}}
        <section class="mb-4">
            <h2 class="h5 mb-2">Peringkat Kemandirian Kawasan</h2>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kawasan</th>
                            <th>Kelurahan</th>
                            <th class="text-end">Timbulan (kg)</th>
                            <th class="text-end">Terolah (kg)</th>
                            <th class="text-end">Rasio Kemandirian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($peringkatKawasan as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $p->kode_kawasan }}</td>
                                <td>{{ $p->kelurahan }}</td>
                                <td class="text-end">{{ number_format((float) $p->timbulan, 0) }}</td>
                                <td class="text-end">{{ number_format((float) $p->terolah, 0) }}</td>
                                <td class="text-end">{{ number_format((float) $p->rasio, 1) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
