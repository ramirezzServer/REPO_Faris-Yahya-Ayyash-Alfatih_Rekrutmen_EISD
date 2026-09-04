@extends('layouts.app')

@section('title', 'Laporan Tumpukan Liar — SINERKA')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Laporan Tumpukan Liar Saya</h1>
        <a href="{{ route('warga.laporan-tumpukan.create') }}" class="btn btn-primary btn-sm">Laporkan Tumpukan</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Catatan Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporan as $l)
                    @php($badge = match ($l->status) {
                        'baru' => 'bg-secondary',
                        'diproses' => 'bg-warning text-dark',
                        'selesai' => 'bg-success',
                        default => 'bg-secondary',
                    })
                    <tr>
                        <td>{{ $l->created_at->format('d M Y') }}</td>
                        <td>{{ $l->lokasi }}</td>
                        <td><span class="badge {{ $badge }}">{{ $l->status }}</span></td>
                        <td>{{ $l->catatan_tindak_lanjut ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Anda belum pernah melaporkan tumpukan liar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
