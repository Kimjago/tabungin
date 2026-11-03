@props(['category'])

@php
    $colors = ['#10b981', '#6366f1', '#f59e0b', '#ef4444', '#ec4899', '#0ea5e9', '#8b5cf6', '#14b8a6'];
    $current = old('color', $category->color ?? '#6366f1');
@endphp

<div>
    <label for="name" class="label">Nama kategori</label>
    <input id="name" name="name" type="text" value="{{ old('name', $category->name ?? '') }}"
           required autofocus maxlength="60"
           class="input @error('name') input-error @enderror"
           placeholder="Contoh: Makan & Minum"
           @error('name') aria-invalid="true" @enderror>
    @error('name') <p class="error-text">{{ $message }}</p> @enderror
</div>

<div>
    <label for="type" class="label">Jenis</label>
    <select id="type" name="type" class="input @error('type') input-error @enderror">
        <option value="expense" @selected(old('type', $category->type ?? 'expense') === 'expense')>Pengeluaran</option>
        <option value="income" @selected(old('type', $category->type ?? 'expense') === 'income')>Pemasukan</option>
    </select>
    @error('type') <p class="error-text">{{ $message }}</p> @enderror
</div>

<div>
    <span class="label">Warna</span>
    <div class="flex flex-wrap items-center gap-2.5">
        @foreach ($colors as $color)
            <label class="cursor-pointer">
                <input type="radio" name="color" value="{{ $color }}"
                       class="peer sr-only" @checked($current === $color)>
                <span class="block size-9 rounded-full border-2 border-transparent ring-2 ring-transparent transition
                             peer-checked:border-white peer-checked:ring-ink-900/30 peer-focus-visible:ring-brand-500"
                      style="background: {{ $color }}" aria-hidden="true"></span>
                <span class="sr-only">Warna {{ $color }}</span>
            </label>
        @endforeach
    </div>
    @error('color') <p class="error-text">{{ $message }}</p> @enderror
    <input type="hidden" name="icon" value="{{ old('icon', $category->icon ?? 'wallet') }}">
</div>