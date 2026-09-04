@extends('layouts.app')

@section('title', 'Kelola Periode Kuota — SINERKA')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Kelola Periode Kuota</h1>
        <a href="{{ route('admin.periode-kuota.create') }}" class="btn btn-primary btn-sm">Tambah Periode</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Kawasan</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th class="text-end">Kuota Total (kg)</th>
                    <th class="text-end">Terpakai (kg)</th>
                    <th class="text-end">Sisa (kg)</th>
                    <th class="text-end">% Sisa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftar as $row)
                    @php($p = $row['model'])
                    <tr>
                        <td>{{ $p->kawasan->kode_kawasan }}</td>
                        <td>{{ $p->tanggal_mulai->format('d M Y') }}</td>
                        <td>{{ $p->tanggal_selesai->format('d M Y') }}</td>
                        <td class="text-end">{{ number_format((float) $p->kuota_residu_kg, 2) }}</td>
                        <td class="text-end">{{ number_format($row['kuota_terpakai'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['sisa_kuota'], 2) }}</td>
                        <td class="text-end">{{ number_format($row['persentase_sisa'], 1) }}%</td>
                        <td>
                            <span class="badge {{ $p->status === 'aktif' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td>
                            @if ($p->status === 'aktif')
                                <form method="POST" action="{{ route('admin.periode-kuota.tutup', $p->id) }}"
                                    onsubmit="return confirm('Tutup periode kuota ini?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-outline-warning">Tutup</button>
                                </form>
                            @else
                                <span class="text-muted">&mdash;</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada data periode kuota.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
