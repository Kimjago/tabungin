@extends('layouts.app')

@section('title', 'Ubah Transaksi')

@section('heading', 'Ubah transaksi')
@section('subheading', 'Perbaiki data yang keliru, lalu simpan')

@section('content')
    <div class="mx-auto max-w-2xl">
        <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="card card-pad">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <x-type-toggle name="type" :value="$transaction->type" />

                <div>
                    <label for="amount" class="label">Nominal</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-medium text-ink-400">
                            Rp
                        </span>
                        <input id="amount" name="amount" type="text" inputmode="numeric"
                               value="{{ old('amount', (int) $transaction->amount) }}"
                               required autofocus
                               class="input num pl-10 text-lg font-semibold @error('amount') input-error @enderror"
                               @error('amount') aria-invalid="true" @enderror>
                    </div>
                    @error('amount')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="category_id" class="label">Kategori</label>
                        <select id="category_id" name="category_id"
                                class="input @error('category_id') input-error @enderror">
                            <option value="">Tanpa kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected((string) old('category_id', $transaction->category_id) === (string) $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="label">Tanggal</label>
                        <input id="date" name="date" type="date"
                               value="{{ old('date', $transaction->date->format('Y-m-d')) }}"
                               max="{{ now()->toDateString() }}"
                               required
                               class="input num @error('date') input-error @enderror">
                        @error('date')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="note" class="label">
                        Catatan <span class="font-normal text-ink-400">(opsional)</span>
                    </label>
                    <input id="note" name="note" type="text" value="{{ old('note', $transaction->note) }}"
                           maxlength="255"
                           class="input @error('note') input-error @enderror">
                    @error('note')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 border-t border-ink-100 pt-5 sm:flex-row sm:justify-between">
                <button type="button"
                        class="btn-danger"
                        onclick="if (confirm('Hapus transaksi ini permanen?')) document.getElementById('delete-form').submit()">
                    Hapus
                </button>
                <div class="flex flex-col-reverse gap-3 sm:flex-row">
                    <a href="{{ route('transactions.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan perubahan</button>
                </div>
            </div>
        </form>

        <form id="delete-form" method="POST" action="{{ route('transactions.destroy', $transaction) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection