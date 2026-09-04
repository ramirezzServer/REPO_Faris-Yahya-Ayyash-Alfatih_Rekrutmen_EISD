@extends('layouts.app')

@section('title', 'Ubah Jalur Pengolahan — SINERKA')

@section('content')
    <h1 class="h4 mb-4">Ubah Jalur Pengolahan: {{ $jalurPengolahan->nama }}</h1>

    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.jalur-pengolahan.update', $jalurPengolahan) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.jalur-pengolahan._form')

            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('admin.jalur-pengolahan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
@endsection
