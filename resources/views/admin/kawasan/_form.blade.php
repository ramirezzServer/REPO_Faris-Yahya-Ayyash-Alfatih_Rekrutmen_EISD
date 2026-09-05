@php
    $k = $kawasan ?? null;
@endphp

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-tinta" for="kode_kawasan">Kode Kawasan</label>
        <input type="text" id="kode_kawasan" name="kode_kawasan" maxlength="20"
            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
            value="{{ old('kode_kawasan', $k?->kode_kawasan) }}" required>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-tinta" for="nama_rw">Nama RW</label>
        <input type="text" id="nama_rw" name="nama_rw" maxlength="100"
            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
            value="{{ old('nama_rw', $k?->nama_rw) }}" required>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-tinta" for="kelurahan">Kelurahan</label>
        <input type="text" id="kelurahan" name="kelurahan" maxlength="100"
            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
            value="{{ old('kelurahan', $k?->kelurahan) }}" required>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-tinta" for="kecamatan">Kecamatan</label>
        <input type="text" id="kecamatan" name="kecamatan" maxlength="100"
            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
            value="{{ old('kecamatan', $k?->kecamatan) }}" required>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-tinta" for="jumlah_kk">Jumlah KK</label>
        <input type="number" id="jumlah_kk" name="jumlah_kk" min="0"
            class="w-full rounded-md border border-garis px-3 py-2 focus:border-aksi focus:outline-none focus:ring-2 focus:ring-aksi/30"
            value="{{ old('jumlah_kk', $k?->jumlah_kk) }}" required>
    </div>
</div>
