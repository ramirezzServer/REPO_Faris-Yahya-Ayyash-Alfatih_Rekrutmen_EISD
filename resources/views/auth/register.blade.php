@extends('layouts.publik')

@section('title', 'Daftar — SINERKA')

@section('tanpaHeader', '1')

@section('content')
    <div class="flex min-h-screen">
        <div class="flex w-full flex-col justify-center px-6 py-16 lg:w-1/2 lg:px-16">
            <div class="mx-auto w-full max-w-[26rem]">
                <a href="{{ route('dashboard.publik') }}" class="text-lg font-bold text-tinta">SINERKA</a>

                <h1 class="mt-8 text-3xl font-bold text-tinta">Daftar Akun Warga</h1>

                <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-tinta">Nama Lengkap</label>
                        <input type="text" name="name" id="name"
                            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                            value="{{ old('name') }}" required autofocus>
                    </div>

                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-tinta">Email</label>
                        <input type="email" name="email" id="email"
                            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                            value="{{ old('email') }}" required>
                    </div>

                    <div>
                        <label for="no_hp" class="mb-1 block text-sm font-medium text-tinta">Nomor HP</label>
                        <input type="text" name="no_hp" id="no_hp"
                            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                            value="{{ old('no_hp') }}" required>
                    </div>

                    <div>
                        <label for="kawasan_id" class="mb-1 block text-sm font-medium text-tinta">Kawasan</label>
                        <select name="kawasan_id" id="kawasan_id"
                            class="w-full rounded-md border border-garis bg-white px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                            required>
                            <option value="">-- Pilih Kawasan --</option>
                            @foreach ($kawasan as $k)
                                <option value="{{ $k->id }}" @selected(old('kawasan_id') == $k->id)>
                                    {{ $k->kode_kawasan }} — {{ $k->nama_rw }}, {{ $k->kelurahan }} ({{ $k->kecamatan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-tinta">Kata Sandi</label>
                        <input type="password" name="password" id="password"
                            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                            required>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-medium text-tinta">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                            required>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                        <button type="submit" class="rounded-md bg-aksi px-5 py-2.5 text-sm font-medium text-white hover:opacity-90">Daftar</button>
                        <a href="{{ route('login') }}" class="text-sm font-medium text-aksi hover:underline">Sudah punya akun? Masuk</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="hidden bg-aksi px-12 py-16 text-white lg:flex lg:w-1/2 lg:flex-col lg:justify-center">
            <div class="mx-auto max-w-md">
                <h2 class="text-2xl font-bold">Apa itu SINERKA?</h2>
                <p class="mt-4 text-sm text-white/85">
                    Sistem pencatatan neraca sampah Kota Bandung untuk kawasan tempat Anda tinggal.
                </p>
                <ul class="mt-8 space-y-4 text-sm text-white/90">
                    <li class="flex gap-3">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-white/70"></span>
                        <span>Laporkan tumpukan sampah liar di kawasan Anda secara langsung.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-white/70"></span>
                        <span>Pantau status kesiagaan kawasan dan sisa kuota residu sebelum dikirim ke TPA.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-white/70"></span>
                        <span>Ikut mengawasi neraca sampah kota secara terbuka bersama admin dan operator kawasan.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
