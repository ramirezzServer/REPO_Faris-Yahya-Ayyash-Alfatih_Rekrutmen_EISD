@extends('layouts.app')

@section('title', 'Daftar — SINERKA')

@section('content')
    <div class="auth-layout">
        <div class="auth-layout__form">
            <h1 class="h1 mb-4">Daftar Akun Warga</h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" class="form-control"
                        value="{{ old('no_hp') }}" required>
                </div>

                <div class="mb-3">
                    <label for="kawasan_id" class="form-label">Kawasan</label>
                    <select name="kawasan_id" id="kawasan_id" class="form-select" required>
                        <option value="">-- Pilih Kawasan --</option>
                        @foreach ($kawasan as $k)
                            <option value="{{ $k->id }}" @selected(old('kawasan_id') == $k->id)>
                                {{ $k->kode_kawasan }} — {{ $k->nama_rw }}, {{ $k->kelurahan }} ({{ $k->kecamatan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Daftar</button>
                <a href="{{ route('login') }}" class="btn btn-link">Sudah punya akun? Masuk</a>
            </form>
        </div>

        <div class="auth-layout__blurb">
            <h2 class="h4">Apa itu SINERKA?</h2>
            <p class="measure">
                SINERKA adalah sistem pencatatan neraca sampah Kota Bandung. Sebagai warga terdaftar,
                Anda dapat melaporkan tumpukan sampah liar di kawasan Anda dan memantau status
                kesiagaan kawasan &mdash; berapa kuota residu yang tersisa sebelum pengiriman ke TPA
                Sarimukti dibatasi lebih ketat.
            </p>
        </div>
    </div>
@endsection
