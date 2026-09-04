@extends('layouts.app')

@section('title', 'Kelola Jalur Pengolahan — SINERKA')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Kelola Jalur Pengolahan</h1>
        <a href="{{ route('admin.jalur-pengolahan.create') }}" class="btn btn-primary btn-sm">Tambah Jalur</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Ikon</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th class="text-end">Faktor Emisi CO<sub>2</sub></th>
                    <th>Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jalur as $j)
                    <tr>
                        <td>
                            @if ($j->ikon)
                                <img src="{{ asset('storage/' . $j->ikon) }}" alt="Ikon {{ $j->nama }}" height="32">
                            @else
                                <span class="text-muted">&mdash;</span>
                            @endif
                        </td>
                        <td>{{ $j->nama }}</td>
                        <td>{{ $j->kategori }}</td>
                        <td class="text-end">{{ $j->faktor_emisi_co2 }}</td>
                        <td>
                            @if ($j->is_aktif)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.jalur-pengolahan.edit', $j) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.jalur-pengolahan.destroy', $j) }}"
                                class="d-inline" onsubmit="return confirm('Yakin hapus jalur pengolahan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data jalur pengolahan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
