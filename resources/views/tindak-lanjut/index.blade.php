@extends('layouts.app')

@section('title', 'Tindak Lanjut Tumpukan — SINERKA')

@section('content')
    <h1 class="h4 mb-3">Tindak Lanjut Laporan Tumpukan Liar</h1>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kawasan</th>
                    <th>Pelapor</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
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
                        <td>{{ $l->kawasan->kode_kawasan ?? '-' }}</td>
                        <td>{{ $l->user->name ?? '-' }}</td>
                        <td>{{ $l->lokasi }}</td>
                        <td><span class="badge {{ $badge }}">{{ $l->status }}</span></td>
                        <td>
                            <a href="{{ route('tindak-lanjut.show', $l->id) }}" class="btn btn-sm btn-outline-primary">Tinjau</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada laporan tumpukan liar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
