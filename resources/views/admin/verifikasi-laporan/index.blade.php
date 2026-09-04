@extends('layouts.app')

@section('title', 'Verifikasi Laporan Neraca — SINERKA')

@section('content')
    <h1 class="h4 mb-3">Verifikasi Laporan Neraca</h1>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kawasan</th>
                    <th>Operator</th>
                    <th class="text-end">Timbulan (kg)</th>
                    <th class="text-end">Residu (kg)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $l)
                    <tr>
                        <td>{{ $l->tanggal_laporan->format('d M Y') }}</td>
                        <td>{{ $l->kawasan->kode_kawasan ?? '-' }}</td>
                        <td>{{ $l->operator->name ?? '-' }}</td>
                        <td class="text-end">{{ number_format((float) $l->timbulan_kg, 2) }}</td>
                        <td class="text-end">{{ number_format((float) $l->residu_kg, 2) }}</td>
                        <td>
                            <a href="{{ route('admin.verifikasi-laporan.show', $l->id) }}" class="btn btn-sm btn-outline-primary">Tinjau</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Tidak ada laporan yang menunggu verifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
