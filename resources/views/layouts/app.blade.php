<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('judul', 'SINERKA') — SINERKA</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-latar text-tinta font-sans antialiased">
    <div class="flex min-h-screen">
        <div id="sinerka-backdrop" class="fixed inset-0 z-30 hidden bg-tinta/40 lg:hidden"></div>

        <aside id="sinerka-sidebar"
            class="fixed inset-y-0 left-0 z-40 flex w-[260px] -translate-x-full flex-col border-r border-garis bg-permukaan transition-transform duration-200 lg:static lg:translate-x-0">
            <div class="flex h-16 shrink-0 items-center border-b border-garis px-6">
                <a href="{{ route('dashboard.publik') }}" class="text-lg font-bold text-tinta">SINERKA</a>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                @php
                    $sinerkaNav = match (true) {
                        auth()->user()->isAdmin() => [
                            ['route' => 'dashboard.admin', 'pola' => 'dashboard.admin', 'label' => 'Dashboard'],
                            ['route' => 'admin.kawasan.index', 'pola' => 'admin.kawasan.*', 'label' => 'Kawasan'],
                            ['route' => 'admin.jalur-pengolahan.index', 'pola' => 'admin.jalur-pengolahan.*', 'label' => 'Jalur Pengolahan'],
                            ['route' => 'admin.periode-kuota.index', 'pola' => 'admin.periode-kuota.*', 'label' => 'Periode Kuota'],
                            ['route' => 'admin.verifikasi-laporan.index', 'pola' => 'admin.verifikasi-laporan.*', 'label' => 'Verifikasi Laporan'],
                            ['route' => 'tindak-lanjut.index', 'pola' => 'tindak-lanjut.*', 'label' => 'Tindak Lanjut Tumpukan'],
                        ],
                        auth()->user()->isOperator() => [
                            ['route' => 'dashboard.operator', 'pola' => 'dashboard.operator', 'label' => 'Dashboard'],
                            ['route' => 'operator.laporan-neraca.index', 'pola' => 'operator.laporan-neraca.*', 'label' => 'Laporan Neraca'],
                            ['route' => 'tindak-lanjut.index', 'pola' => 'tindak-lanjut.*', 'label' => 'Tindak Lanjut Tumpukan'],
                        ],
                        default => [
                            ['route' => 'dashboard.warga', 'pola' => 'dashboard.warga', 'label' => 'Dashboard'],
                            ['route' => 'warga.laporan-tumpukan.index', 'pola' => 'warga.laporan-tumpukan.*', 'label' => 'Laporan Tumpukan Liar'],
                        ],
                    };
                @endphp
                @foreach ($sinerkaNav as $item)
                    @php
                        $aktif = request()->routeIs($item['pola']);
                    @endphp
                    <a href="{{ route($item['route']) }}"
                        class="block rounded-md border-l-2 px-3 py-2 text-sm font-medium {{ $aktif ? 'border-aksi bg-latar text-aksi' : 'border-transparent text-tinta hover:bg-latar' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex h-16 shrink-0 items-center justify-between gap-4 border-b border-garis bg-permukaan px-4 lg:px-8">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" id="sinerka-toggle" aria-expanded="false" aria-controls="sinerka-sidebar"
                        class="rounded-md p-2 text-tinta hover:bg-latar lg:hidden">
                        <span class="sr-only">Buka menu</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" d="M3 5.5h14M3 10h14M3 14.5h14" />
                        </svg>
                    </button>
                    <h1 class="truncate text-lg font-semibold">@yield('judul')</h1>
                </div>

                <div class="flex shrink-0 items-center gap-3">
                    @php
                        $sinerkaLabelPeran = match (auth()->user()->role) {
                            'admin' => 'Administrator',
                            'operator' => 'Operator',
                            'warga' => 'Warga',
                            default => ucfirst(auth()->user()->role),
                        };
                    @endphp
                    <span class="hidden text-sm text-lembut sm:block">
                        {{ auth()->user()->name }} &middot; {{ $sinerkaLabelPeran }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="rounded-md border border-garis px-3 py-1.5 text-sm hover:bg-latar">Keluar</button>
                    </form>
                </div>
            </header>

            <main class="flex-1 bg-latar p-8">
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

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
        (function () {
            var toggle = document.getElementById('sinerka-toggle');
            var sidebar = document.getElementById('sinerka-sidebar');
            var backdrop = document.getElementById('sinerka-backdrop');

            function tutup() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
            }

            function buka() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                toggle.setAttribute('aria-expanded', 'true');
            }

            if (toggle && sidebar && backdrop) {
                toggle.addEventListener('click', function () {
                    sidebar.classList.contains('-translate-x-full') ? buka() : tutup();
                });
                backdrop.addEventListener('click', tutup);
            }
        })();
    </script>
</body>
</html>
