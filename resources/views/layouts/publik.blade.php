<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'SINERKA')</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-latar text-tinta font-sans antialiased">
    @php
        $sinerkaTanpaHeader = trim($__env->yieldContent('tanpaHeader')) !== '';
    @endphp

    @unless ($sinerkaTanpaHeader)
        <header id="sinerka-header" class="fixed inset-x-0 top-0 z-40 transition-colors duration-200">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4 lg:px-8">
                <a href="{{ route('dashboard.publik') }}" class="text-lg font-bold text-tinta">SINERKA</a>
                <nav class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="rounded-md border border-garis px-4 py-2 text-sm font-medium text-tinta hover:bg-permukaan">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Daftar</a>
                </nav>
            </div>
        </header>
    @endunless

    @if (session('success') || session('error') || $errors->any())
        <div class="mx-auto max-w-6xl px-6 {{ $sinerkaTanpaHeader ? 'pt-8' : 'pt-24' }} lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md border border-aman/40 bg-aman/10 px-4 py-3 text-sm text-tinta" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-md border border-kritis/40 bg-kritis/10 px-4 py-3 text-sm text-tinta" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-md border border-kritis/40 bg-kritis/10 px-4 py-3 text-sm text-tinta" role="alert">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    @yield('content')

    <footer class="border-t border-garis bg-permukaan">
        <div class="mx-auto max-w-6xl px-6 py-8 text-sm text-lembut lg:px-8">
            <span class="font-semibold text-tinta">SINERKA</span> &middot; Sistem Neraca Sampah Kota Bandung
            &copy; {{ date('Y') }}
        </div>
    </footer>

    @stack('scripts')

    @unless ($sinerkaTanpaHeader)
        <script>
            (function () {
                var header = document.getElementById('sinerka-header');
                if (! header) {
                    return;
                }

                function perbarui() {
                    if (window.scrollY > 8) {
                        header.classList.add('border-b', 'border-garis', 'bg-permukaan/90', 'backdrop-blur');
                    } else {
                        header.classList.remove('border-b', 'border-garis', 'bg-permukaan/90', 'backdrop-blur');
                    }
                }

                perbarui();
                window.addEventListener('scroll', perbarui, { passive: true });
            })();
        </script>
    @endunless
</body>
</html>
