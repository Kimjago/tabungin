@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('heading', 'Tambah kategori')
@section('subheading', 'Beri nama dan warna agar mudah dikenali')

@section('content')
    <div class="mx-auto max-w-lg">
        <form method="POST" action="{{ route('categories.store') }}" class="card card-pad">
            @csrf
            <div class="space-y-5">
                <x-category-fields :category="$category" />
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 border-t border-ink-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('categories.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan kategori</button>
            </div>
        </form>
    </div>
@endsection