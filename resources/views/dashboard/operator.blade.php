@extends('layouts.app')

@section('title', 'Dashboard Operator — SINERKA')

@section('content')
    <h1 class="h3 mb-3">Dashboard Operator</h1>

    <p>Selamat datang, <strong>{{ $user->name }}</strong>.</p>
    <p>Peran Anda: <span class="badge bg-dark">{{ $user->role }}</span></p>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-outline-danger btn-sm">Keluar</button>
    </form>
@endsection
