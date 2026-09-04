@extends('layouts.app')

@section('title', 'Ubah Kawasan — SINERKA')

@section('content')
    <h1 class="h4 mb-4">Ubah Kawasan {{ $kawasan->kode_kawasan }}</h1>

    <div class="col-md-6">
        <form method="POST" action="{{ route('admin.kawasan.update', $kawasan) }}">
            @csrf
            @method('PUT')
            @include('admin.kawasan._form')

            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('admin.kawasan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
@endsection
