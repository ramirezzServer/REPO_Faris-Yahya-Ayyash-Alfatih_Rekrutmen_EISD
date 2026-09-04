@php($k = $kawasan ?? null)

<div class="mb-3">
    <label class="form-label" for="kode_kawasan">Kode Kawasan</label>
    <input type="text" id="kode_kawasan" name="kode_kawasan" maxlength="20"
        class="form-control" value="{{ old('kode_kawasan', $k?->kode_kawasan) }}" required>
</div>

<div class="mb-3">
    <label class="form-label" for="nama_rw">Nama RW</label>
    <input type="text" id="nama_rw" name="nama_rw" maxlength="100"
        class="form-control" value="{{ old('nama_rw', $k?->nama_rw) }}" required>
</div>

<div class="mb-3">
    <label class="form-label" for="kelurahan">Kelurahan</label>
    <input type="text" id="kelurahan" name="kelurahan" maxlength="100"
        class="form-control" value="{{ old('kelurahan', $k?->kelurahan) }}" required>
</div>

<div class="mb-3">
    <label class="form-label" for="kecamatan">Kecamatan</label>
    <input type="text" id="kecamatan" name="kecamatan" maxlength="100"
        class="form-control" value="{{ old('kecamatan', $k?->kecamatan) }}" required>
</div>

<div class="mb-3">
    <label class="form-label" for="jumlah_kk">Jumlah KK</label>
    <input type="number" id="jumlah_kk" name="jumlah_kk" min="0"
        class="form-control" value="{{ old('jumlah_kk', $k?->jumlah_kk) }}" required>
</div>
