@php($j = $jalurPengolahan ?? null)

<div class="mb-3">
    <label class="form-label" for="nama">Nama</label>
    <input type="text" id="nama" name="nama" maxlength="100"
        class="form-control" value="{{ old('nama', $j?->nama) }}" required>
</div>

<div class="mb-3">
    <label class="form-label" for="kategori">Kategori</label>
    <select id="kategori" name="kategori" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="organik" @selected(old('kategori', $j?->kategori) === 'organik')>organik</option>
        <option value="anorganik" @selected(old('kategori', $j?->kategori) === 'anorganik')>anorganik</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label" for="faktor_emisi_co2">Faktor Emisi CO<sub>2</sub></label>
    <input type="number" id="faktor_emisi_co2" name="faktor_emisi_co2" step="0.0001" min="0"
        class="form-control" value="{{ old('faktor_emisi_co2', $j?->faktor_emisi_co2) }}" required>
</div>

<div class="mb-3">
    <label class="form-label" for="deskripsi">Deskripsi</label>
    <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control">{{ old('deskripsi', $j?->deskripsi) }}</textarea>
</div>

<div class="mb-3 form-check">
    <input type="checkbox" id="is_aktif" name="is_aktif" value="1" class="form-check-input"
        @checked(old('is_aktif', $j?->is_aktif ?? true))>
    <label class="form-check-label" for="is_aktif">Aktif</label>
</div>

<div class="mb-3">
    <label class="form-label" for="ikon">Ikon (gambar, maks. 2 MB)</label>
    @if ($j?->ikon)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $j->ikon) }}" alt="Ikon {{ $j->nama }}" height="48">
        </div>
    @endif
    <input type="file" id="ikon" name="ikon" class="form-control" accept="image/*">
</div>
