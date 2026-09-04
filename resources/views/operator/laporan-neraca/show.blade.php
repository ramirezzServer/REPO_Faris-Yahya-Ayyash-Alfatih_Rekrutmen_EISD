@extends('layouts.app')

@section('title', 'Detail Laporan Neraca — SINERKA')

@section('content')
    @php($badge = match ($laporan->status) {
        'menunggu' => 'bg-warning text-dark',
        'terverifikasi' => 'bg-success',
        'ditolak' => 'bg-danger',
        default => 'bg-secondary',
    })

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Laporan Neraca {{ $laporan->tanggal_laporan->format('d M Y') }}</h1>
        <a href="{{ route('operator.laporan-neraca.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
    </div>

    <dl class="row">
        <dt class="col-sm-3">Kawasan</dt>
        <dd class="col-sm-9">{{ $laporan->kawasan->kode_kawasan ?? '-' }}</dd>
        <dt class="col-sm-3">Operator</dt>
        <dd class="col-sm-9">{{ $laporan->operator->name ?? '-' }}</dd>
        <dt class="col-sm-3">Periode</dt>
        <dd class="col-sm-9">
            {{ $laporan->periodeKuota->tanggal_mulai->format('d M Y') }} &ndash;
            {{ $laporan->periodeKuota->tanggal_selesai->format('d M Y') }}
        </dd>
        <dt class="col-sm-3">Status</dt>
        <dd class="col-sm-9"><span class="badge {{ $badge }}">{{ $laporan->status }}</span></dd>
        <dt class="col-sm-3">Timbulan</dt>
        <dd class="col-sm-9">{{ number_format((float) $laporan->timbulan_kg, 2) }} kg</dd>
        <dt class="col-sm-3">Total Diolah</dt>
        <dd class="col-sm-9">{{ number_format((float) $laporan->total_diolah_kg, 2) }} kg</dd>
        <dt class="col-sm-3">Residu</dt>
        <dd class="col-sm-9">{{ number_format((float) $laporan->residu_kg, 2) }} kg</dd>
        @if ($laporan->catatan)
            <dt class="col-sm-3">Catatan</dt>
            <dd class="col-sm-9">{{ $laporan->catatan }}</dd>
        @endif
    </dl>

    <h2 class="h6 mt-3">Uraian Tonase per Jalur</h2>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Jalur Pengolahan</th>
                    <th class="text-end">Tonase (kg)</th>
                    <th class="text-end">Faktor Emisi CO<sub>2</sub> (saat lapor)</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan->jalurPengolahan as $jalur)
                    <tr>
                        <td>{{ $jalur->nama }}</td>
                        <td class="text-end">{{ number_format((float) $jalur->pivot->tonase_kg, 2) }}</td>
                        <td class="text-end">{{ $jalur->pivot->faktor_emisi_saat_lapor }}</td>
                        <td>{{ $jalur->pivot->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
