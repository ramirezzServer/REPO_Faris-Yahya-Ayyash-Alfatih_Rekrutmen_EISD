@extends('layouts.app')

@section('title', 'Buat Laporan Neraca — SINERKA')

@section('content')
    <h1 class="h4 mb-3">Buat Laporan Neraca</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h6 text-muted">Informasi Kuota</h2>
            <div class="row">
                <div class="col-sm-3"><strong>Kawasan</strong><br>{{ $kawasan->kode_kawasan }} — {{ $kawasan->nama_rw }}</div>
                <div class="col-sm-3"><strong>Periode Aktif</strong><br>{{ $periode->tanggal_mulai->format('d M Y') }} &ndash; {{ $periode->tanggal_selesai->format('d M Y') }}</div>
                <div class="col-sm-3"><strong>Sisa Kuota Residu</strong><br>{{ number_format($sisaKuota, 2) }} kg</div>
                <div class="col-sm-3"><strong>Persentase Sisa</strong><br>{{ number_format($persentaseSisa, 1) }}%</div>
            </div>
        </div>
    </div>

    @if ($kuotaMenipis)
        <div class="alert alert-warning" role="alert">
            Kuota residu kawasan Anda menipis (di bawah 40%). Utamakan pengolahan agar residu tetap dalam kuota.
        </div>
    @endif

    @php($barisLama = old('uraian', [['jalur_pengolahan_id' => '', 'tonase_kg' => '', 'keterangan' => '']]))

    <form method="POST" action="{{ route('operator.laporan-neraca.store') }}">
        @csrf

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label" for="tanggal_laporan">Tanggal Laporan</label>
                <input type="date" id="tanggal_laporan" name="tanggal_laporan" class="form-control"
                    value="{{ old('tanggal_laporan') }}" max="{{ now()->toDateString() }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label" for="timbulan_kg">Timbulan Sampah (kg)</label>
                <input type="number" id="timbulan_kg" name="timbulan_kg" class="form-control"
                    step="0.01" min="0" value="{{ old('timbulan_kg') }}" required>
            </div>
        </div>

        <h2 class="h6 mt-2">Uraian Tonase per Jalur Pengolahan</h2>
        <div class="table-responsive">
            <table class="table align-middle" id="uraian-table">
                <thead>
                    <tr>
                        <th style="width: 40%">Jalur Pengolahan</th>
                        <th style="width: 20%">Tonase (kg)</th>
                        <th style="width: 30%">Keterangan</th>
                        <th style="width: 10%"></th>
                    </tr>
                </thead>
                <tbody id="uraian-body">
                    @foreach ($barisLama as $i => $baris)
                        <tr>
                            <td>
                                <select name="uraian[{{ $i }}][jalur_pengolahan_id]" class="form-select" required>
                                    <option value="">-- Pilih Jalur --</option>
                                    @foreach ($jalurList as $jalur)
                                        <option value="{{ $jalur->id }}"
                                            @selected(($baris['jalur_pengolahan_id'] ?? '') == $jalur->id)>
                                            {{ $jalur->nama }} ({{ $jalur->kategori }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" class="form-control"
                                    name="uraian[{{ $i }}][tonase_kg]" value="{{ $baris['tonase_kg'] ?? '' }}" required>
                            </td>
                            <td>
                                <input type="text" maxlength="255" class="form-control"
                                    name="uraian[{{ $i }}][keterangan]" value="{{ $baris['keterangan'] ?? '' }}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-baris">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="button" id="tambah-baris" class="btn btn-sm btn-outline-secondary mb-3">Tambah Baris</button>

        <div>
            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
            <a href="{{ route('operator.laporan-neraca.index') }}" class="btn btn-outline-secondary">Batal</a>
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
