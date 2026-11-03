@props(['name', 'value' => 'expense', 'categories' => collect()])

@php
    $current = old('type', $value);
@endphp

<fieldset>
    <legend class="label">Jenis transaksi</legend>
    <div class="grid grid-cols-2 gap-3">
        @foreach ([['income', 'Pemasukan', 'Uang masuk'], ['expense', 'Pengeluaran', 'Uang keluar']] as [$val, $label, $desc])
            <label class="relative cursor-pointer">
                <input type="radio" name="{{ $name }}" value="{{ $val }}"
                       class="peer sr-only" @checked($current === $val)>
                <span class="flex flex-col rounded-md border border-ink-200 bg-white px-4 py-3 shadow-soft transition
                             peer-checked:border-brand-500 peer-checked:bg-brand-50/60 peer-checked:ring-2 peer-checked:ring-brand-500/15
                             peer-focus-visible:ring-2 peer-focus-visible:ring-brand-500/40">
                    <span class="text-sm font-semibold text-ink-900 peer-checked:text-brand-800">{{ $label }}</span>
                    <span class="mt-0.5 text-xs text-ink-500">{{ $desc }}</span>
                </span>
            </label>
        @endforeach
    </div>
    @error($name)
        <p class="error-text">{{ $message }}</p>
    @enderror
</fieldset>