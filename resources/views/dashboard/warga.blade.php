@extends('layouts.app')

@section('title', 'Dashboard Warga — SINERKA')

@section('content')
    <h1 class="h3 mb-3">Dashboard Warga</h1>

    <p>Selamat datang, <strong>{{ $user->name }}</strong>.</p>
    <p>Peran Anda: <span class="badge bg-dark">{{ $user->role }}</span></p>

    <h2 class="h5 mt-4 mb-2">Menu</h2>
    <div class="list-group mb-4" style="max-width: 24rem;">
        <a href="{{ route('warga.laporan-tumpukan.index') }}" class="list-group-item list-group-item-action">Laporan Tumpukan Liar</a>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-outline-danger btn-sm">Keluar</button>
    </form>
@endsection
