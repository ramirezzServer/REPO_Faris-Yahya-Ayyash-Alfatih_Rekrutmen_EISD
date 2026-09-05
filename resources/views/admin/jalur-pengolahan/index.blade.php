@extends('layouts.app')

@section('judul', 'Kelola Jalur Pengolahan')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-tinta">Kelola Jalur Pengolahan</h1>
            <p class="mt-1 text-sm text-lembut">Kelola jalur pengolahan sampah yang tersedia bagi operator saat mencatat laporan neraca.</p>
        </div>
        <a href="{{ route('admin.jalur-pengolahan.create') }}" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Tambah Jalur</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-garis bg-permukaan">
        <div class="max-h-[70vh] overflow-auto">
            <table class="w-full text-sm">
                <thead class="sticky top-0 z-10 bg-permukaan">
                    <tr class="border-b border-garis text-left text-lembut">
                        <th class="px-4 py-2 font-medium">Ikon</th>
                        <th class="px-4 py-2 font-medium">Nama</th>
                        <th class="px-4 py-2 font-medium">Kategori</th>
                        <th class="px-4 py-2 text-right font-medium">Faktor Emisi CO<sub>2</sub></th>
                        <th class="px-4 py-2 font-medium">Aktif</th>
                        <th class="px-4 py-2 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jalur as $j)
                        <tr class="border-b border-garis last:border-0 hover:bg-latar">
                            <td class="px-4 py-3">
                                @if ($j->ikon)
                                    <img src="{{ asset('storage/' . $j->ikon) }}" alt="Ikon {{ $j->nama }}" height="32">
                                @else
                                    <span class="text-lembut">&mdash;</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-tinta">{{ $j->nama }}</td>
                            <td class="px-4 py-3">{{ $j->kategori }}</td>
                            <td class="num px-4 py-3 text-right tabular-nums">{{ $j->faktor_emisi_co2 }}</td>
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
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.jalur-pengolahan.edit', $j) }}" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Edit</a>
                                    <form method="POST" action="{{ route('admin.jalur-pengolahan.destroy', $j) }}"
                                        onsubmit="return sinerkaKonfirmasi(this, 'Hapus jalur pengolahan {{ $j->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-kritis/40 px-3 py-1.5 text-sm font-medium text-kritis hover:bg-kritis/10">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <p class="text-sm text-tinta">Belum ada data jalur pengolahan.</p>
                                <p class="mt-1 text-sm text-lembut">Tambahkan jalur pengolahan agar operator dapat mencatat uraian tonase laporannya.</p>
                                <a href="{{ route('admin.jalur-pengolahan.create') }}" class="mt-4 inline-block rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Tambah Jalur</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
