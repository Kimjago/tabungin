@extends('layouts.app')

@section('title', 'Target Tabungan')

@section('heading', 'Target tabungan')
@section('subheading', 'Tentukan tujuan, lalu setor sedikit-sedikit')

@section('actions')
    <a href="{{ route('goals.create') }}" class="btn-primary btn-sm">Buat target</a>
@endsection

@section('content')
    @php
        $overallPct = $totalTarget > 0 ? min(100, round($totalSaved / $totalTarget * 100, 1)) : 0;
    @endphp

    @if ($goals->isNotEmpty())
        <section class="card card-pad mb-5">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-ink-500">Total terkumpul dari semua target aktif</p>
                    <p class="num mt-1.5 text-2xl font-bold tracking-tight text-ink-900">
                        {{ rupiah($totalSaved) }}
                        <span class="text-base font-medium text-ink-400">/ {{ rupiah($totalTarget) }}</span>
                    </p>
                </div>
                <p class="num text-2xl font-bold text-brand-700">{{ $overallPct }}%</p>
            </div>
            <div class="mt-4 h-2.5 overflow-hidden rounded-full bg-ink-100"
                 role="progressbar" aria-valuenow="{{ $overallPct }}" aria-valuemin="0" aria-valuemax="100"
                 aria-label="Progres total semua target">
                <div class="h-full rounded-full {{ progress_tone($overallPct) }}" style="width: {{ $overallPct }}%"></div>
            </div>
        </section>
    @endif

    @if ($goals->isEmpty())
        <div class="card px-6 py-16 text-center">
            <span class="mx-auto grid size-14 place-items-center rounded-full bg-brand-50 text-brand-600">
                <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-4.5a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm0-3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                </svg>
            </span>
            <p class="mt-5 text-base font-semibold text-ink-900">Belum ada target tabungan</p>
            <p class="mx-auto mt-1.5 max-w-sm text-pretty text-sm leading-relaxed text-ink-500">
                Target membuat menabung terasa konkret. Mulai dari yang kecil — dana darurat
                atau liburan akhir tahun.
            </p>
            <a href="{{ route('goals.create') }}" class="btn-primary mt-6">Buat target pertama</a>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($goals as $goal)
                @php
                    $pct = $goal->progress_percent;
                    $isDone = $goal->status === 'completed' || $pct >= 100;
                    $daysLeft = $goal->deadline?->isFuture() ? now()->diffInDays($goal->deadline) : null;
                @endphp

                <article class="card card-pad flex flex-col">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h2 class="truncate text-base font-semibold text-ink-900">{{ $goal->name }}</h2>
                            @if ($goal->description)
                                <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-ink-500">{{ $goal->description }}</p>
                            @endif
                        </div>
                        <span class="grid size-9 shrink-0 place-items-center rounded-md text-white"
                              style="background: {{ $goal->color }}" aria-hidden="true">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-4.5a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Z" />
                            </svg>
                        </span>
                    </div>

                    <div class="mt-5">
                        <div class="flex items-baseline justify-between">
                            <p class="num text-lg font-bold text-ink-900">{{ rupiah($goal->saved_amount) }}</p>
                            <p class="num text-xs font-semibold text-ink-500">{{ $pct }}%</p>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-ink-100"
                             role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"
                             aria-label="Progres {{ $goal->name }}">
                            <div class="h-full rounded-full {{ progress_tone($pct) }}" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="num mt-2 text-xs text-ink-500">
                            Target {{ rupiah($goal->target_amount) }}
                            @if ($goal->remaining_amount > 0)
                                · sisa {{ rupiah($goal->remaining_amount) }}
                            @endif
                        </p>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        @if ($isDone)
                            <span class="badge bg-brand-600 text-white">Tercapai</span>
                        @elseif ($goal->status === 'cancelled')
                            <span class="badge bg-ink-200 text-ink-600">Dibatalkan</span>
                        @else
                            <x-badge>Aktif</x-badge>
                        @endif

                        @if ($goal->deadline)
                            <span class="text-xs text-ink-500">
                                @if ($daysLeft !== null)
                                    {{ (int) $daysLeft }} hari lagi
                                @elseif ($isDone)
                                    Selesai
                                @else
                                    Tenggat lewat
                                @endif
                            </span>
                        @endif

                        <span class="text-xs text-ink-400">· {{ $goal->contributions_count }} setoran</span>
                    </div>

                    {{-- form setor --}}
                    @unless ($isDone)
                        <form method="POST" action="{{ route('goals.deposit', $goal) }}"
                              class="mt-5 flex gap-2 border-t border-ink-100 pt-4">
                            @csrf
                            <div class="relative flex-1">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-medium text-ink-400">Rp</span>
                                <label for="amount-{{ $goal->id }}" class="sr-only">Nominal setoran untuk {{ $goal->name }}</label>
                                <input id="amount-{{ $goal->id }}" name="amount" type="text" inputmode="numeric"
                                       required placeholder="0"
                                       class="input num py-2 pl-8 text-sm">
                                <input type="hidden" name="date" value="{{ now()->toDateString() }}">
                            </div>
                            <button type="submit" class="btn-primary btn-sm shrink-0">Setor</button>
                        </form>
                        @error('amount')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                        @error('date')
                            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    @endunless

                    <div class="mt-3 flex items-center gap-1">
                        <a href="{{ route('goals.edit', $goal) }}" class="btn-ghost btn-sm">Ubah</a>
                        <form method="POST" action="{{ route('goals.destroy', $goal) }}"
                              onsubmit="return confirm('Hapus target &quot;{{ $goal->name }}&quot;? Semua riwayat setorannya ikut terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-ghost btn-sm text-red-600 hover:bg-red-50">Hapus</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection