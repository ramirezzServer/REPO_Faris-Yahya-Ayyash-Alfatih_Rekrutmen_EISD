@extends('layouts.publik')

@section('title', 'Masuk — SINERKA')

@section('tanpaHeader', '1')

@section('content')
    <div class="flex min-h-screen">
        <div class="flex w-full flex-col justify-center px-6 py-16 lg:w-1/2 lg:px-16">
            <div class="mx-auto w-full max-w-[26rem]">
                <a href="{{ route('dashboard.publik') }}" class="text-lg font-bold text-tinta">SINERKA</a>

                <h1 class="mt-8 text-3xl font-bold text-tinta">Masuk</h1>

                <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-tinta">Email</label>
                        <input type="email" name="email" id="email"
                            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                            value="{{ old('email') }}" required autofocus>
                    </div>

                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-tinta">Kata Sandi</label>
                        <input type="password" name="password" id="password"
                            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                            required>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                        <button type="submit" class="rounded-md bg-aksi px-5 py-2.5 text-sm font-medium text-white hover:opacity-90">Masuk</button>
                        <a href="{{ route('register') }}" class="text-sm font-medium text-aksi hover:underline">Belum punya akun? Daftar</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="hidden bg-aksi px-12 py-16 text-white lg:flex lg:w-1/2 lg:flex-col lg:justify-center">
            <div class="mx-auto max-w-md">
                <h2 class="text-2xl font-bold">Apa itu SINERKA?</h2>
                <p class="mt-4 text-sm text-white/85">
                    Sistem pencatatan neraca sampah Kota Bandung &mdash; berapa yang ditimbulkan, berapa yang
                    diolah, dan berapa residu yang dikirim ke TPA Sarimukti.
                </p>
                <ul class="mt-8 space-y-4 text-sm text-white/90">
                    <li class="flex gap-3">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-white/70"></span>
                        <span>Kuota residu tiap kawasan dipantau agar masa pakai TPA Sarimukti dapat diperpanjang.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-white/70"></span>
                        <span>Operator kawasan melaporkan neraca sampah, admin memverifikasinya.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-white/70"></span>
                        <span>Warga dapat memantau status kesiagaan kawasan dan melaporkan tumpukan liar.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
