@extends('layouts.app')

@section('title', 'Tambah Kawasan — SINERKA')

@section('content')
    <h1 class="h4 mb-4">Tambah Kawasan</h1>

    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.kawasan.store') }}">
            @csrf
            @include('admin.kawasan._form')

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.kawasan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
@endsection
