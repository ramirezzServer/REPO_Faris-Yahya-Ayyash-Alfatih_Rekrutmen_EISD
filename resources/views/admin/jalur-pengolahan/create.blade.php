@extends('layouts.app')

@section('judul', 'Tambah Jalur Pengolahan')

@section('content')
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-tinta">Tambah Jalur Pengolahan</h1>
        <p class="mt-1 text-sm text-lembut">Isi data jalur pengolahan baru.</p>
    </div>

    <div class="max-w-3xl rounded-lg border border-garis bg-permukaan p-6">
        <form method="POST" action="{{ route('admin.jalur-pengolahan.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @include('admin.jalur-pengolahan._form')

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.jalur-pengolahan.index') }}" class="rounded-md border border-garis px-4 py-2 text-sm font-medium text-tinta hover:bg-latar">Batal</a>
                <button type="submit" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Simpan</button>
            </div>
        </form>
    </div>
@endsection
