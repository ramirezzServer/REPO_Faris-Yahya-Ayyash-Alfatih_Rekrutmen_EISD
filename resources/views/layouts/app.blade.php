<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'SINERKA')</title>

    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sinerka.css') }}">
</head>
<body>
    <nav class="navbar navbar-sinerka">
        <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2 py-2">
            <a class="navbar-brand mb-0" href="{{ route('dashboard.publik') }}">SINERKA</a>

            <div class="d-flex align-items-center gap-2">
                @auth
                    @php
                        $sinerkaLabelPeran = match (auth()->user()->role) {
                            'admin' => 'Administrator',
                            'operator' => 'Operator',
                            'warga' => 'Warga',
                            default => ucfirst(auth()->user()->role),
                        };
                    @endphp
                    <span class="navbar-text-peran">
                        {{ auth()->user()->name }} &middot; {{ $sinerkaLabelPeran }}
                    </span>
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Keluar</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Daftar</a>
                @endguest
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
