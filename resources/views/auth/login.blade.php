@extends('layouts.app')

@section('title', 'Masuk — SINERKA')

@section('content')
    <div class="auth-layout">
        <div class="auth-layout__form">
            <h1 class="h1 mb-4">Masuk</h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Masuk</button>
                <a href="{{ route('register') }}" class="btn btn-link">Belum punya akun? Daftar</a>
            </form>
        </div>

        <div class="auth-layout__blurb">
            <h2 class="h4">Apa itu SINERKA?</h2>
            <p class="measure">
                SINERKA adalah sistem pencatatan neraca sampah Kota Bandung. Tiap kawasan melaporkan
                berapa sampah yang ditimbulkan, berapa yang berhasil diolah di tingkat kawasan, dan
                berapa residu yang dikirim ke TPA Sarimukti &mdash; yang jumlahnya dibatasi kuota agar
                masa pakai TPA dapat diperpanjang. Data ini dipakai admin, operator kawasan, dan warga
                untuk memantau status kesiagaan tiap kawasan.
            </p>
        </div>
    </div>
@endsection
