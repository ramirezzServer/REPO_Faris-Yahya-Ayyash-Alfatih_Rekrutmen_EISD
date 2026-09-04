@extends('layouts.app')

@section('title', 'Neraca Sampah Kota Bandung')

@section('content')
    <h1 class="h1 mb-3">Neraca Sampah Kota Bandung</h1>

    <p class="measure text-tinta-lembut">
        TPA Sarimukti diproyeksikan mencapai kapasitas maksimum pada Oktober 2026. Untuk memperpanjang
        masa pakai TPA, pengiriman residu sampah dari setiap kawasan di Kota Bandung dibatasi kuota.
        SINERKA mencatat neraca sampah tiap kawasan &mdash; berapa yang ditimbulkan, berapa yang diolah,
        dan berapa residu yang dikirim ke TPA &mdash; sehingga sisa kuota dan status kesiagaan tiap
        kawasan dapat dipantau publik.
    </p>

    @if (! $adaData)
        <div class="notice-netral" role="alert">
            Belum ada laporan neraca yang terverifikasi, sehingga neraca sampah kota belum dapat ditampilkan.
        </div>
    @else
        {{-- Batang neraca --}}
        <section class="mb-4">
            <div class="d-flex justify-content-between align-items-baseline mb-2">
                <h2 class="h4 mb-0">Batang Neraca Sampah Kota</h2>
                <span class="small text-tinta-lembut">Total timbulan: <strong class="num">{{ number_format($totalTimbulan, 0) }} kg</strong></span>
            </div>
            <div class="progress neraca-bar" role="group" aria-label="Batang neraca sampah kota">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persenTerolah }}%">
                    Terolah {{ number_format($totalTerolah, 0) }} kg ({{ number_format($persenTerolah, 1) }}%)
                </div>
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $persenResidu }}%">
                    Residu {{ number_format($totalResidu, 0) }} kg ({{ number_format($persenResidu, 1) }}%)
                </div>
            </div>
            <p class="small text-tinta-lembut mt-2 mb-0">
                Timbulan dikurangi tonase yang terolah menghasilkan residu &mdash; residu inilah yang dikirim ke TPA dan memotong kuota tiap kawasan.
            </p>
        </section>

        {{-- Ringkasan --}}
        <section class="mb-4">
            <h2 class="h4 mb-2">Ringkasan Kota</h2>
            <div class="angka-strip">
                <div class="angka-strip__item">
                    <div class="angka-strip__nilai">{{ number_format($totalTimbulan, 0) }} <span class="fs-6 fw-normal">kg</span></div>
                    <div class="angka-strip__label">Total Timbulan</div>
                </div>
                <div class="angka-strip__item">
                    <div class="angka-strip__nilai">{{ number_format($totalTerolah, 0) }} <span class="fs-6 fw-normal">kg</span></div>
                    <div class="angka-strip__label">Total Terolah</div>
                </div>
                <div class="angka-strip__item">
                    <div class="angka-strip__nilai">{{ number_format($totalResidu, 0) }} <span class="fs-6 fw-normal">kg</span></div>
                    <div class="angka-strip__label">Total Residu</div>
                </div>
                <div class="angka-strip__item">
                    <div class="angka-strip__nilai">{{ number_format($totalEmisi, 1) }} <span class="fs-6 fw-normal">kg CO<sub>2</sub>e</span></div>
                    <div class="angka-strip__label">Estimasi Emisi CO<sub>2</sub> Dihindari</div>
                </div>
                <div class="angka-strip__item">
                    <div class="angka-strip__nilai">{{ number_format($rasioKemandirian, 1) }}%</div>
                    <div class="angka-strip__label">Rasio Kemandirian Kota</div>
                </div>
            </div>
        </section>

        {{-- Status siaga kawasan --}}
        <section class="mb-4">
            <h2 class="h4 mb-2">Status Siaga Kawasan</h2>
            <div class="table-responsive">
                <table class="status-table" id="tabel-status-siaga">
                    <thead>
                        <tr>
                            <th data-sort="text"><button type="button">Kawasan</button></th>
                            <th data-sort="text"><button type="button">Kelurahan</button></th>
                            <th data-sort="text"><button type="button">Status Siaga</button></th>
                            <th data-sort="number" class="text-end"><button type="button">Kuota Residu (kg)</button></th>
                            <th data-sort="number" class="text-end"><button type="button">Sisa Kuota (kg)</button></th>
                            <th data-sort="number" style="min-width: 12rem;"><button type="button">Persentase Sisa</button></th>
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
                            <tr class="{{ $k->status_siaga === 'kritis' ? 'is-kritis' : '' }}">
                                <td data-value="{{ $k->kode_kawasan }}">{{ $k->kode_kawasan }}</td>
                                <td data-value="{{ $k->kelurahan }}">{{ $k->kelurahan }}</td>
                                <td data-value="{{ $k->status_siaga }}"><span class="badge {{ $badge }}">{{ $k->status_siaga }}</span></td>
                                <td class="text-end" data-value="{{ $k->kuota_residu_kg !== null ? (float) $k->kuota_residu_kg : '' }}">
                                    {{ $k->kuota_residu_kg !== null ? number_format((float) $k->kuota_residu_kg, 0) : '—' }}
                                </td>
                                <td class="text-end" data-value="{{ $k->kuota_residu_kg !== null ? (float) $k->sisa_kuota : '' }}">
                                    {{ $k->kuota_residu_kg !== null ? number_format((float) $k->sisa_kuota, 0) : '—' }}
                                </td>
                                <td data-value="{{ $k->persentase_sisa !== null ? (float) $k->persentase_sisa : '' }}">
                                    @if ($k->persentase_sisa !== null)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="min-width: 5rem;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ max(0, min(100, $k->persentase_sisa)) }}%">
                                                </div>
                                            </div>
                                            <span class="num small">{{ number_format($k->persentase_sisa, 1) }}%</span>
                                        </div>
                                    @else
                                        <span class="small text-tinta-lembut">Belum ada periode kuota aktif</span>
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
            <h2 class="h4 mb-2">Kontribusi Jalur Pengolahan</h2>
            @foreach ($kontribusiJalur as $j)
                <div class="mb-2">
                    <div class="d-flex justify-content-between small">
                        <span>{{ $j->nama }}</span>
                        <span class="num">{{ number_format((float) $j->total_tonase, 0) }} kg</span>
                    </div>
                    <div class="progress bar-neutral">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ $kontribusiMaks > 0 ? $j->total_tonase / $kontribusiMaks * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        {{-- Peringkat kemandirian kawasan --}}
        <section class="mb-4">
            <h2 class="h4 mb-2">Peringkat Kemandirian Kawasan</h2>
            <div class="table-responsive">
                <table class="status-table">
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
                                <td class="text-end num fw-semibold">{{ number_format((float) $p->rasio, 1) }}%</td>
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
            var table = document.getElementById('tabel-status-siaga');
            if (! table) {
                return;
            }

            var tbody = table.tBodies[0];
            var headers = table.querySelectorAll('th[data-sort]');

            headers.forEach(function (th, index) {
                var button = th.querySelector('button');
                button.addEventListener('click', function () {
                    var type = th.getAttribute('data-sort');
                    var current = th.getAttribute('aria-sort');
                    var direction = current === 'ascending' ? 'descending' : 'ascending';

                    headers.forEach(function (other) {
                        other.removeAttribute('aria-sort');
                    });
                    th.setAttribute('aria-sort', direction);

                    var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));

                    rows.sort(function (rowA, rowB) {
                        var cellA = rowA.children[index];
                        var cellB = rowB.children[index];
                        var rawA = cellA.getAttribute('data-value');
                        var rawB = cellB.getAttribute('data-value');

                        // Baris tanpa nilai (mis. belum ada periode kuota aktif) selalu di akhir.
                        if (rawA === '' && rawB === '') return 0;
                        if (rawA === '') return 1;
                        if (rawB === '') return -1;

                        var valA = type === 'number' ? parseFloat(rawA) : rawA.toLowerCase();
                        var valB = type === 'number' ? parseFloat(rawB) : rawB.toLowerCase();

                        var result = valA < valB ? -1 : (valA > valB ? 1 : 0);
                        return direction === 'ascending' ? result : -result;
                    });

                    rows.forEach(function (row) {
                        tbody.appendChild(row);
                    });
                });
            });
        })();
    </script>
@endpush
