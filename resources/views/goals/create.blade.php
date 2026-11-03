@extends('layouts.app')

@section('title', 'Buat Target')

@section('heading', 'Buat target tabungan')
@section('subheading', 'Tentukan tujuan dan tenggatnya')

@section('content')
    <div class="mx-auto max-w-2xl">
        <form method="POST" action="{{ route('goals.store') }}" class="card card-pad">
            @csrf

            <div class="space-y-5">
                <div>
                    <label for="name" class="label">Nama target</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}"
                           required autofocus maxlength="100"
                           class="input @error('name') input-error @enderror"
                           placeholder="Contoh: Dana darurat"
                           @error('name') aria-invalid="true" @enderror>
                    @error('name') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="label">
                        Deskripsi <span class="font-normal text-ink-400">(opsional)</span>
                    </label>
                    <textarea id="description" name="description" rows="3" maxlength="1000"
                              class="input resize-none @error('description') input-error @enderror"
                              placeholder="Kenapa target ini penting untukmu?">{{ old('description') }}</textarea>
                    @error('description') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="target_amount" class="label">Nominal target</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-medium text-ink-400">Rp</span>
                            <input id="target_amount" name="target_amount" type="text" inputmode="numeric"
                                   value="{{ old('target_amount') }}"
                                   required
                                   class="input num pl-10 text-lg font-semibold @error('target_amount') input-error @enderror"
                                   placeholder="0"
                                   @error('target_amount') aria-invalid="true" @enderror>
                        </div>
                        @error('target_amount') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="deadline" class="label">
                            Tenggat <span class="font-normal text-ink-400">(opsional)</span>
                        </label>
                        <input id="deadline" name="deadline" type="date"
                               value="{{ old('deadline') }}"
                               min="{{ now()->addDay()->toDateString() }}"
                               class="input num @error('deadline') input-error @enderror">
                        @error('deadline') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="color" class="label">Warna penanda</label>
                    <div class="flex flex-wrap items-center gap-2.5">
                        @foreach (['#10b981', '#6366f1', '#f59e0b', '#ef4444', '#ec4899', '#0ea5e9'] as $color)
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="{{ $color }}"
                                       class="peer sr-only" @checked(old('color', $goal->color) === $color)>
                                <span class="block size-9 rounded-full border-2 border-transparent ring-2 ring-transparent transition
                                             peer-checked:border-white peer-checked:ring-ink-900/30 peer-focus-visible:ring-brand-500"
                                      style="background: {{ $color }}" aria-hidden="true"></span>
                                <span class="sr-only">Warna {{ $color }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('color') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 border-t border-ink-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('goals.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Buat target</button>
            </div>
        </form>
    </div>
@endsection