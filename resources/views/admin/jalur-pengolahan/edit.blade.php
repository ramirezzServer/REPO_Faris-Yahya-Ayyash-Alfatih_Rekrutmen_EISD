@extends('layouts.app')

@section('judul', 'Ubah Jalur Pengolahan')

@section('content')
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-tinta">Ubah Jalur Pengolahan: {{ $jalurPengolahan->nama }}</h1>
        <p class="mt-1 text-sm text-lembut">Perbarui data jalur pengolahan ini.</p>
    </div>

    <div class="max-w-3xl rounded-lg border border-garis bg-permukaan p-6">
        <form method="POST" action="{{ route('admin.jalur-pengolahan.update', $jalurPengolahan) }}"
            enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            @include('admin.jalur-pengolahan._form')

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.jalur-pengolahan.index') }}" class="rounded-md border border-garis px-4 py-2 text-sm font-medium text-tinta hover:bg-latar">Batal</a>
                <button type="submit" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Perbarui</button>
            </div>
        </form>
    </div>
@endsection
