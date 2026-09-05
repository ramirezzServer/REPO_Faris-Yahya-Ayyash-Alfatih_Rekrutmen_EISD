@php
    $j = $jalurPengolahan ?? null;
@endphp

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-tinta" for="nama">Nama</label>
        <input type="text" id="nama" name="nama" maxlength="100"
            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
            value="{{ old('nama', $j?->nama) }}" required>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-tinta" for="kategori">Kategori</label>
        <select id="kategori" name="kategori"
            class="w-full rounded-md border border-garis bg-white px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
            required>
            <option value="">-- Pilih Kategori --</option>
            <option value="organik" @selected(old('kategori', $j?->kategori) === 'organik')>organik</option>
            <option value="anorganik" @selected(old('kategori', $j?->kategori) === 'anorganik')>anorganik</option>
        </select>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-tinta" for="faktor_emisi_co2">Faktor Emisi CO<sub>2</sub></label>
        <input type="number" id="faktor_emisi_co2" name="faktor_emisi_co2" step="0.0001" min="0"
            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
            value="{{ old('faktor_emisi_co2', $j?->faktor_emisi_co2) }}" required>
    </div>

    <div class="flex items-end pb-2.5">
        <div class="flex items-center gap-2">
            <input type="checkbox" id="is_aktif" name="is_aktif" value="1" class="h-4 w-4 rounded border-garis accent-aksi"
                @checked(old('is_aktif', $j?->is_aktif ?? true))>
            <label class="text-sm font-medium text-tinta" for="is_aktif">Aktif</label>
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-tinta" for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="3"
            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30">{{ old('deskripsi', $j?->deskripsi) }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-tinta" for="ikon">Ikon (gambar, maks. 2 MB)</label>
        @if ($j?->ikon)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $j->ikon) }}" alt="Ikon {{ $j->nama }}" height="48">
            </div>
        @endif
        <input type="file" id="ikon" name="ikon" accept="image/*"
            class="w-full rounded-md border border-garis px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-latar file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-tinta">
    </div>
</div>
