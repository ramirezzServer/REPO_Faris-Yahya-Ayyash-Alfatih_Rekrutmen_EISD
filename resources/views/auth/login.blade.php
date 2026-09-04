@extends('layouts.app')

@section('title', 'Masuk — SINERKA')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h1 class="h3 mb-4">Masuk</h1>

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
    </div>
@endsection
