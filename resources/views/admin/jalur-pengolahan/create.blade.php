@extends('layouts.app')

@section('title', 'Tambah Jalur Pengolahan — SINERKA')

@section('content')
    <h1 class="h4 mb-4">Tambah Jalur Pengolahan</h1>

    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.jalur-pengolahan.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.jalur-pengolahan._form')

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.jalur-pengolahan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
@endsection
