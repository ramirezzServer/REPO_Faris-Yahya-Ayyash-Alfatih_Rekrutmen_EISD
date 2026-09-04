@extends('layouts.app')

@section('judul', 'Tambah Jalur Pengolahan')

@section('content')
    <h1 class="mb-6 text-lg font-semibold text-tinta">Tambah Jalur Pengolahan</h1>

    <div class="max-w-xl">
        <form method="POST" action="{{ route('admin.jalur-pengolahan.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @include('admin.jalur-pengolahan._form')

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-md bg-aksi px-4 py-2 text-sm font-medium text-white hover:opacity-90">Simpan</button>
                <a href="{{ route('admin.jalur-pengolahan.index') }}" class="rounded-md border border-garis px-4 py-2 text-sm font-medium text-tinta hover:bg-latar">Batal</a>
            </div>
        </form>
    </div>
@endsection
