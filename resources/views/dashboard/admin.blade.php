@extends('layouts.app')

@section('judul', 'Dashboard Admin')

@section('content')
    <p class="text-tinta">Selamat datang, <strong>{{ $user->name }}</strong>.</p>
    <p class="mt-1 text-tinta">
        Peran Anda:
        <span class="inline-flex items-center rounded-md bg-tinta px-2 py-0.5 text-xs font-medium text-white">{{ $user->role }}</span>
    </p>

    <h2 class="mb-2 mt-6 text-sm font-semibold text-lembut">Kelola Data Induk</h2>
    <div class="max-w-sm divide-y divide-garis overflow-hidden rounded-lg border border-garis bg-permukaan">
        <a href="{{ route('admin.kawasan.index') }}" class="block px-4 py-3 text-sm font-medium text-tinta hover:bg-latar">Kelola Kawasan</a>
        <a href="{{ route('admin.jalur-pengolahan.index') }}" class="block px-4 py-3 text-sm font-medium text-tinta hover:bg-latar">Kelola Jalur Pengolahan</a>
        <a href="{{ route('admin.periode-kuota.index') }}" class="block px-4 py-3 text-sm font-medium text-tinta hover:bg-latar">Kelola Periode Kuota</a>
        <a href="{{ route('admin.verifikasi-laporan.index') }}" class="block px-4 py-3 text-sm font-medium text-tinta hover:bg-latar">Verifikasi Laporan Neraca</a>
        <a href="{{ route('tindak-lanjut.index') }}" class="block px-4 py-3 text-sm font-medium text-tinta hover:bg-latar">Tindak Lanjut Tumpukan</a>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit" class="rounded-md border border-kritis/40 px-3 py-1.5 text-sm font-medium text-kritis hover:bg-kritis/10">Keluar</button>
    </form>
@endsection
