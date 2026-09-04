@extends('layouts.app')

@section('judul', 'Buat Laporan Neraca')

@section('content')
    <h1 class="mb-4 text-lg font-semibold text-tinta">Buat Laporan Neraca</h1>

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
        <div class="mb-3 overflow-hidden rounded-lg border border-garis bg-permukaan">
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="uraian-table">
                    <thead>
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

        <button type="button" id="tambah-baris" class="mb-4 rounded-md border border-garis px-3 py-1.5 text-sm font-medium text-tinta hover:bg-latar">Tambah Baris</button>

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
