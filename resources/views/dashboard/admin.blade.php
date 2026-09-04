@extends('layouts.app')

@section('title', 'Dashboard Admin — SINERKA')

@section('content')
    <h1 class="h3 mb-3">Dashboard Admin</h1>

    <p>Selamat datang, <strong>{{ $user->name }}</strong>.</p>
    <p>Peran Anda: <span class="badge bg-dark">{{ $user->role }}</span></p>

    <h2 class="h5 mt-4 mb-2">Kelola Data Induk</h2>
    <div class="list-group mb-4" style="max-width: 24rem;">
        <a href="{{ route('admin.kawasan.index') }}" class="list-group-item list-group-item-action">Kelola Kawasan</a>
        <a href="{{ route('admin.jalur-pengolahan.index') }}" class="list-group-item list-group-item-action">Kelola Jalur Pengolahan</a>
        <a href="{{ route('admin.periode-kuota.index') }}" class="list-group-item list-group-item-action">Kelola Periode Kuota</a>
        <a href="{{ route('admin.verifikasi-laporan.index') }}" class="list-group-item list-group-item-action">Verifikasi Laporan Neraca</a>
        <a href="{{ route('tindak-lanjut.index') }}" class="list-group-item list-group-item-action">Tindak Lanjut Tumpukan</a>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-outline-danger btn-sm">Keluar</button>
    </form>
@endsection
