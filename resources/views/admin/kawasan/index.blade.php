@extends('layouts.app')

@section('title', 'Kelola Kawasan — SINERKA')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Kelola Kawasan</h1>
        <a href="{{ route('admin.kawasan.create') }}" class="btn btn-primary btn-sm">Tambah Kawasan</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>RW</th>
                    <th>Kelurahan</th>
                    <th>Kecamatan</th>
                    <th class="text-end">Jumlah KK</th>
                    <th class="text-end">Jumlah Warga</th>
                    <th>Status Siaga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftar as $row)
                    @php($k = $row['model'])
                    @php($badge = match ($row['status_siaga']) {
                        'aman' => 'bg-success',
                        'waspada' => 'bg-warning text-dark',
                        'kritis' => 'bg-danger',
                        default => 'bg-secondary',
                    })
                    <tr>
                        <td>{{ $k->kode_kawasan }}</td>
                        <td>{{ $k->nama_rw }}</td>
                        <td>{{ $k->kelurahan }}</td>
                        <td>{{ $k->kecamatan }}</td>
                        <td class="text-end">{{ $k->jumlah_kk }}</td>
                        <td class="text-end">{{ $k->jumlah_warga }}</td>
                        <td><span class="badge {{ $badge }}">{{ $row['status_siaga'] }}</span></td>
                        <td>
                            <a href="{{ route('admin.kawasan.edit', $k) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.kawasan.destroy', $k) }}"
                                class="d-inline" onsubmit="return confirm('Yakin hapus kawasan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada data kawasan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
