@extends('layouts.app')

@section('judul', 'Buat Laporan Neraca')

@section('content')
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-tinta">Buat Laporan Neraca</h1>
        <p class="mt-1 text-sm text-lembut">Catat timbulan dan uraian tonase per jalur pengolahan hari ini.</p>
    </div>

    <div class="mb-4 rounded-lg border border-garis bg-permukaan p-6">
        <h2 class="mb-3 text-sm font-semibold text-lembut">Informasi Kuota</h2>
        <div class="grid grid-cols-2 gap-4 text-sm sm:grid-cols-4">
            <div><strong class="text-tinta">Kawasan</strong><br>{{ $kawasan->kode_kawasan }} — {{ $kawasan->nama_rw }}</div>
            <div><strong class="text-tinta">Periode Aktif</strong><br>{{ $periode->tanggal_mulai->format('d M Y') }} &ndash; {{ $periode->tanggal_selesai->format('d M Y') }}</div>
            <div><strong class="text-tinta">Sisa Kuota Residu</strong><br>{{ number_format($sisaKuota, 2) }} kg</div>
            <div><strong class="text-tinta">Persentase Sisa</strong><br>{{ number_format($persentaseSisa, 1) }}%</div>
        </div>
    </div>

    @if ($kuotaMenipis)
        <div class="mb-4 rounded-md border border-waspada/40 bg-waspada/10 p-4 text-sm text-tinta" role="alert">
            Kuota residu kawasan Anda menipis (di bawah 40%). Utamakan pengolahan agar residu tetap dalam kuota.
        </div>
    @endif

    @php
        $barisLama = old('uraian', [['jalur_pengolahan_id' => '', 'tonase_kg' => '', 'keterangan' => '']]);
    @endphp

    <form method="POST" action="{{ route('operator.laporan-neraca.store') }}">
        @csrf

        <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-tinta" for="tanggal_laporan">Tanggal Laporan</label>
                <input type="date" id="tanggal_laporan" name="tanggal_laporan"
                    class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                    value="{{ old('tanggal_laporan') }}" max="{{ now()->toDateString() }}" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-tinta" for="timbulan_kg">Timbulan Sampah (kg)</label>
                <input type="number" id="timbulan_kg" name="timbulan_kg"
                    class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                    step="0.01" min="0" value="{{ old('timbulan_kg') }}" required>
            </div>
        </div>

        <h2 class="mb-2 mt-2 text-sm font-semibold text-lembut">Uraian Tonase per Jalur Pengolahan</h2>
        <div class="mb-2 overflow-hidden rounded-lg border border-garis bg-permukaan">
            <div class="max-h-[50vh] overflow-auto">
                <table class="w-full text-sm" id="uraian-table">
                    <thead class="sticky top-0 z-10 bg-permukaan">
                        <tr class="border-b border-garis text-left text-lembut">
                            <th class="px-4 py-2 font-medium" style="width: 40%">Jalur Pengolahan</th>
                            <th class="px-4 py-2 font-medium" style="width: 20%">Tonase (kg)</th>
                            <th class="px-4 py-2 font-medium" style="width: 30%">Keterangan</th>
                            <th class="px-4 py-2 font-medium" style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody id="uraian-body">
                        @foreach ($barisLama as $i => $baris)
                            <tr class="border-b border-garis last:border-0">
                                <td class="px-4 py-3">
                                    <select name="uraian[{{ $i }}][jalur_pengolahan_id]"
                                        class="w-full rounded-md border border-garis bg-white px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30" required>
                                        <option value="">-- Pilih Jalur --</option>
                                        @foreach ($jalurList as $jalur)
                                            <option value="{{ $jalur->id }}"
                                                @selected(($baris['jalur_pengolahan_id'] ?? '') == $jalur->id)>
                                                {{ $jalur->nama }} ({{ $jalur->kategori }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" step="0.01" min="0"
                                        class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                                        name="uraian[{{ $i }}][tonase_kg]" value="{{ $baris['tonase_kg'] ?? '' }}" required>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" maxlength="255"
                                        class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
                                        name="uraian[{{ $i }}][keterangan]" value="{{ $baris['keterangan'] ?? '' }}">
                                </td>
                                <td class="px-4 py-3">
                                    <button type="button" class="btn-hapus-baris rounded-md border border-kritis/40 px-3 py-1.5 text-sm font-medium text-kritis hover:bg-kritis/10">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mb-4 flex flex-wrap items-center gap-3">
            <button type="button" id="tambah-baris" class="rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Tambah Baris</button>
            <p id="uraian-total-info" class="num text-sm text-lembut"></p>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Simpan Laporan</button>
            <a href="{{ route('operator.laporan-neraca.index') }}" class="rounded-md border border-garis px-4 py-2 text-sm font-medium text-tinta hover:bg-latar">Batal</a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
(function () {
    var body = document.getElementById('uraian-body');
    var tambah = document.getElementById('tambah-baris');
    var counter = body.querySelectorAll('tr').length;

    function bindHapus(btn) {
        btn.addEventListener('click', function () {
            if (body.querySelectorAll('tr').length > 1) {
                btn.closest('tr').remove();
            } else {
                alert('Minimal satu baris uraian harus diisi.');
            }
        });
    }

    body.querySelectorAll('.btn-hapus-baris').forEach(bindHapus);

    tambah.addEventListener('click', function () {
        var baris = body.querySelector('tr').cloneNode(true);
        baris.querySelectorAll('select, input').forEach(function (el) {
            el.name = el.name.replace(/uraian\[\d+\]/, 'uraian[' + counter + ']');
            if (el.tagName === 'SELECT') {
                el.selectedIndex = 0;
            } else {
                el.value = '';
            }
        });
        bindHapus(baris.querySelector('.btn-hapus-baris'));
        body.appendChild(baris);
        counter++;
    });
})();
</script>
@endpush

@push('scripts')
<script>
(function () {
    // Penghitung total tonase berjalan -- murni pratinjau di sisi klien,
    // tidak menggantikan validasi aturan bisnis di server (yang tetap
    // menolak total > timbulan). Memakai delegasi event pada #uraian-body
    // supaya otomatis bekerja untuk baris yang ditambah/dihapus lewat
    // skrip di atas, tanpa perlu mengubah skrip tersebut.
    var body = document.getElementById('uraian-body');
    var timbulanInput = document.getElementById('timbulan_kg');
    var info = document.getElementById('uraian-total-info');

    function format(n) {
        return n.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function hitungTotal() {
        var total = 0;

        body.querySelectorAll('input[name*="[tonase_kg]"]').forEach(function (el) {
            var v = parseFloat(el.value);
            if (! isNaN(v)) {
                total += v;
            }
        });

        var timbulan = parseFloat(timbulanInput.value) || 0;
        var lebih = total > timbulan;

        info.textContent = 'Total tonase: ' + format(total) + ' kg dari ' + format(timbulan) + ' kg timbulan'
            + (lebih ? ' — melebihi timbulan!' : '');
        info.classList.toggle('text-kritis', lebih);
        info.classList.toggle('text-lembut', ! lebih);
    }

    body.addEventListener('input', function (e) {
        if (e.target.matches('input[name*="[tonase_kg]"]')) {
            hitungTotal();
        }
    });

    timbulanInput.addEventListener('input', hitungTotal);

    hitungTotal();
})();
</script>
@endpush
