@extends('layouts.app')

@section('title', 'Ubah Kategori')

@section('heading', 'Ubah kategori')
@section('subheading', $category->name)

@section('content')
    <div class="mx-auto max-w-lg">
        <form method="POST" action="{{ route('categories.update', $category) }}" class="card card-pad">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <x-category-fields :category="$category" />
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 border-t border-ink-100 pt-5 sm:flex-row sm:justify-between">
                <button type="button" class="btn-danger"
                        onclick="if (confirm('Hapus kategori ini? Transaksi terkait tidak ikut terhapus.')) document.getElementById('delete-cat').submit()">
                    Hapus
                </button>
                <div class="flex flex-col-reverse gap-3 sm:flex-row">
                    <a href="{{ route('categories.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan perubahan</button>
                </div>
            </div>
        </form>

        <form id="delete-cat" method="POST" action="{{ route('categories.destroy', $category) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection