@extends('layouts.app')

@section('title', 'Laporan Neraca — SINERKA')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Laporan Neraca Kawasan {{ $kawasan?->kode_kawasan ?? '-' }}</h1>
        <a href="{{ route('operator.laporan-neraca.create') }}" class="btn btn-primary btn-sm">Buat Laporan</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th class="text-end">Timbulan (kg)</th>
                    <th class="text-end">Total Diolah (kg)</th>
                    <th class="text-end">Residu (kg)</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $l)
                    @php($badge = match ($l->status) {
                        'menunggu' => 'bg-warning text-dark',
                        'terverifikasi' => 'bg-success',
                        'ditolak' => 'bg-danger',
                        default => 'bg-secondary',
                    })
                    <tr>
                        <td>{{ $l->tanggal_laporan->format('d M Y') }}</td>
                        <td class="text-end">{{ number_format((float) $l->timbulan_kg, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $l->total_diolah_kg, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $l->residu_kg, 2) }}</td>
                        <td><span class="badge {{ $badge }}">{{ $l->status }}</span></td>
                        <td>
                            <a href="{{ route('operator.laporan-neraca.show', $l) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada laporan neraca.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
