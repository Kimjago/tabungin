@extends('layouts.app')

@section('title', 'Kategori')

@section('heading', 'Kategori')
@section('subheading', 'Kelompokkan transaksi sesuai kebiasaanmu')

@section('actions')
    <a href="{{ route('categories.create') }}" class="btn-primary btn-sm">Tambah kategori</a>
@endsection

@section('content')
    <div class="card overflow-hidden">
        @if ($categories->isEmpty())
            <div class="px-6 py-16 text-center">
                <span class="mx-auto grid size-12 place-items-center rounded-full bg-ink-100 text-ink-400">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 0 0 3.182 0l4.318-4.318a2.25 2.25 0 0 0 0-3.182L11.16 3.659A2.25 2.25 0 0 0 9.568 3Z" />
                    </svg>
                </span>
                <p class="mt-4 text-sm font-semibold text-ink-800">Belum ada kategori</p>
                <p class="mx-auto mt-1 max-w-xs text-xs text-ink-500">
                    Kategori membuat laporan lebih mudah dibaca.
                </p>
                <a href="{{ route('categories.create') }}" class="btn-primary btn-sm mt-5">Tambah kategori</a>
            </div>
        @else
            <table class="table-base">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Dipakai</th>
                        <th scope="col" class="text-right"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cat)
                        <tr>
                            <td>
                                <span class="flex items-center gap-2.5">
                                    <span class="size-3 shrink-0 rounded-full" style="background: {{ $cat->color }}"></span>
                                    <span class="font-medium text-ink-900">{{ $cat->name }}</span>
                                </span>
                            </td>
                            <td>
                                <x-badge :variant="$cat->type">
                                    {{ $cat->type === 'income' ? 'Masuk' : 'Keluar' }}
                                </x-badge>
                            </td>
                            <td class="num text-xs text-ink-500">{{ $cat->transactions_count }} transaksi</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('categories.edit', $cat) }}" class="btn-ghost btn-sm">Ubah</a>
                                    <form method="POST" action="{{ route('categories.destroy', $cat) }}"
                                          onsubmit="return confirm('Hapus kategori &quot;{{ $cat->name }}&quot;? Transaksi terkait akan kehilangan kategori, tapi tidak terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-ghost btn-sm text-red-600 hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection