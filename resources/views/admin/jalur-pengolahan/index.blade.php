@extends('layouts.app')

@section('judul', 'Kelola Jalur Pengolahan')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-lg font-semibold text-tinta">Kelola Jalur Pengolahan</h1>
        <a href="{{ route('admin.jalur-pengolahan.create') }}" class="rounded-md bg-aksi px-3 py-1.5 text-sm font-medium text-white hover:opacity-90">Tambah Jalur</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Ikon</th>
                        <th class="px-4 py-2 font-medium">Nama</th>
                        <th class="px-4 py-2 font-medium">Kategori</th>
                        <th class="px-4 py-2 text-right font-medium">Faktor Emisi CO<sub>2</sub></th>
                        <th class="px-4 py-2 font-medium">Aktif</th>
                        <th class="px-4 py-2 font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jalur as $j)
                        <tr class="border-b border-garis last:border-0">
                            <td class="px-4 py-3">
                                @if ($j->ikon)
                                    <img src="{{ asset('storage/' . $j->ikon) }}" alt="Ikon {{ $j->nama }}" height="32">
                                @else
                                    <span class="text-lembut">&mdash;</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-tinta">{{ $j->nama }}</td>
                            <td class="px-4 py-3">{{ $j->kategori }}</td>
                            <td class="px-4 py-3 text-right">{{ $j->faktor_emisi_co2 }}</td>
                            <td class="px-4 py-3">
                                @if ($j->is_aktif)
                                    <span class="inline-flex items-center gap-1.5 rounded-md border border-garis px-2 py-0.5 text-xs font-medium text-tinta">
                                        <span class="h-1.5 w-1.5 rounded-full bg-aksi"></span> Ya
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-md border border-garis px-2 py-0.5 text-xs font-medium text-lembut">
                                        <span class="h-1.5 w-1.5 rounded-full bg-garis"></span> Tidak
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.jalur-pengolahan.edit', $j) }}" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Edit</a>
                                    <form method="POST" action="{{ route('admin.jalur-pengolahan.destroy', $j) }}"
                                        onsubmit="return confirm('Yakin hapus jalur pengolahan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-kritis/40 px-3 py-1.5 text-sm font-medium text-kritis hover:bg-kritis/10">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-lembut">Belum ada data jalur pengolahan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
