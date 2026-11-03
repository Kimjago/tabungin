@extends('layouts.app')

@section('title', 'Ubah Target')

@section('heading', 'Ubah target tabungan')
@section('subheading', $goal->name)

@section('content')
    <div class="mx-auto max-w-2xl space-y-5">
        <form method="POST" action="{{ route('goals.update', $goal) }}" class="card card-pad">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <div>
                    <label for="name" class="label">Nama target</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $goal->name) }}"
                           required autofocus maxlength="100"
                           class="input @error('name') input-error @enderror">
                    @error('name') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="label">
                        Deskripsi <span class="font-normal text-ink-400">(opsional)</span>
                    </label>
                    <textarea id="description" name="description" rows="3" maxlength="1000"
                              class="input resize-none @error('description') input-error @enderror">{{ old('description', $goal->description) }}</textarea>
                    @error('description') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="target_amount" class="label">Nominal target</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-medium text-ink-400">Rp</span>
                            <input id="target_amount" name="target_amount" type="text" inputmode="numeric"
                                   value="{{ old('target_amount', (int) $goal->target_amount) }}"
                                   required
                                   class="input num pl-10 text-lg font-semibold @error('target_amount') input-error @enderror">
                        </div>
                        @error('target_amount') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="deadline" class="label">Tenggat <span class="font-normal text-ink-400">(opsional)</span></label>
                        <input id="deadline" name="deadline" type="date"
                               value="{{ old('deadline', $goal->deadline?->format('Y-m-d')) }}"
                               min="{{ now()->addDay()->toDateString() }}"
                               class="input num @error('deadline') input-error @enderror">
                        @error('deadline') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="status" class="label">Status</label>
                    <select id="status" name="status" class="input @error('status') input-error @enderror">
                        <option value="active" @selected(old('status', $goal->status) === 'active')>Aktif</option>
                        <option value="completed" @selected(old('status', $goal->status) === 'completed')>Tercapai</option>
                        <option value="cancelled" @selected(old('status', $goal->status) === 'cancelled')>Dibatalkan</option>
                    </select>
                    @error('status') <p class="error-text">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="color" class="label">Warna penanda</label>
                    <div class="flex flex-wrap items-center gap-2.5">
                        @foreach (['#10b981', '#6366f1', '#f59e0b', '#ef4444', '#ec4899', '#0ea5e9'] as $color)
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="{{ $color }}"
                                       class="peer sr-only" @checked(old('color', $goal->color) === $color)>
                                <span class="block size-9 rounded-full border-2 border-transparent ring-2 ring-transparent transition
                                             peer-checked:border-white peer-checked:ring-ink-900/30"
                                      style="background: {{ $color }}" aria-hidden="true"></span>
                                <span class="sr-only">Warna {{ $color }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('color') <p class="error-text">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-7 flex flex-col-reverse gap-3 border-t border-ink-100 pt-5 sm:flex-row sm:justify-between">
                <button type="button" class="btn-danger"
                        onclick="if (confirm('Hapus target ini beserta riwayat setorannya?')) document.getElementById('delete-goal').submit()">
                    Hapus target
                </button>
                <div class="flex flex-col-reverse gap-3 sm:flex-row">
                    <a href="{{ route('goals.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan perubahan</button>
                </div>
            </div>
        </form>

        <form id="delete-goal" method="POST" action="{{ route('goals.destroy', $goal) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        {{-- riwayat setoran --}}
        <section class="card overflow-hidden">
            <div class="border-b border-ink-100 px-5 py-4">
                <h2 class="text-base font-semibold text-ink-900">Riwayat setoran</h2>
                <p class="mt-0.5 text-xs text-ink-500">Total {{ rupiah($goal->saved_amount) }} dari {{ rupiah($goal->target_amount) }}</p>
            </div>

            @php $contributions = $goal->contributions()->latest('date')->latest('id')->limit(20)->get(); @endphp

            @if ($contributions->isEmpty())
                <p class="px-5 py-10 text-center text-sm text-ink-500">Belum ada setoran untuk target ini.</p>
            @else
                <table class="table-base">
                    <thead>
                        <tr>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Catatan</th>
                            <th scope="col" class="text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contributions as $c)
                            <tr>
                                <td class="num whitespace-nowrap text-xs text-ink-500">{{ $c->date->format('d M Y') }}</td>
                                <td class="text-sm">{{ $c->note ?: 'â€”' }}</td>
                                <td class="num whitespace-nowrap text-right font-semibold text-brand-700">
                                    +{{ rupiah($c->amount) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>
@endsection