@extends('layouts.app')

@section('title', 'Dashboard')

@section('heading', 'Dashboard')
@section('subheading', 'Ringkasan keuanganmu per ' . now()->translatedFormat('d F Y'))

@section('actions')
    <a href="{{ route('transactions.create') }}" class="btn-primary btn-sm">
        <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
        </svg>
        Catat transaksi
    </a>
@endsection

@section('content')
    @php
        $maxSeries = max(1, max(array_merge($incomeSeries, $expenseSeries)));
        $expenseRatio = $income > 0 ? min(100, round($expense / $income * 100)) : 0;
    @endphp

    {{-- ============ KARTU RINGKASAN ============ --}}
    <section aria-label="Ringkasan saldo" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="card card-pad">
            <div class="flex items-start justify-between">
                <p class="text-sm font-medium text-ink-500">Saldo bersih</p>
                <span class="grid size-8 place-items-center rounded-md bg-ink-100 text-ink-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 7.5A2.25 2.25 0 0 1 5.25 5.25h13.5A2.25 2.25 0 0 1 21 7.5v9A2.25 2.25 0 0 1 18.75 18.75H5.25A2.25 2.25 0 0 1 3 16.5v-9Z" />
                    </svg>
                </span>
            </div>
            <p class="num mt-3 text-2xl font-bold tracking-tight {{ $balance < 0 ? 'text-red-600' : 'text-ink-900' }}">
                {{ rupiah($balance) }}
            </p>
            <p class="mt-1 text-xs text-ink-500">
                {{ $balance < 0 ? 'Pengeluaran melebihi pemasukan' : 'Sisa dari seluruh catatan' }}
            </p>
        </div>

        <div class="card card-pad">
            <div class="flex items-start justify-between">
                <p class="text-sm font-medium text-ink-500">Total pemasukan</p>
                <span class="grid size-8 place-items-center rounded-md bg-brand-50 text-brand-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 19V5M5 12l7-7 7 7" />
                    </svg>
                </span>
            </div>
            <p class="num mt-3 text-2xl font-bold tracking-tight text-ink-900">{{ rupiah($income) }}</p>
            <p class="mt-1 text-xs text-ink-500">Dari semua transaksi masuk</p>
        </div>

        <div class="card card-pad">
            <div class="flex items-start justify-between">
                <p class="text-sm font-medium text-ink-500">Total pengeluaran</p>
                <span class="grid size-8 place-items-center rounded-md bg-amber-50 text-amber-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 5v14M5 12l7 7 7-7" />
                    </svg>
                </span>
            </div>
            <p class="num mt-3 text-2xl font-bold tracking-tight text-ink-900">{{ rupiah($expense) }}</p>
            <p class="mt-1 text-xs text-ink-500">
                @if ($income > 0)
                    Menyerap {{ $expenseRatio }}% pemasukan
                @else
                    Belum ada pemasukan tercatat
                @endif
            </p>
        </div>

        <div class="card card-pad">
            <div class="flex items-start justify-between">
                <p class="text-sm font-medium text-ink-500">Total ditabung</p>
                <span class="grid size-8 place-items-center rounded-md bg-brand-50 text-brand-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-13.5v9m-2.25-2.25h3a1.5 1.5 0 0 0 0-3h-1.5a1.5 1.5 0 0 1 0-3h3" />
                    </svg>
                </span>
            </div>
            <p class="num mt-3 text-2xl font-bold tracking-tight text-ink-900">{{ rupiah($savedTotal) }}</p>
            <p class="mt-1 text-xs text-ink-500">{{ $goals->count() }} target aktif</p>
        </div>
    </section>

    <div class="mt-5 grid gap-5 xl:grid-cols-[1.6fr_1fr]">

        {{-- ============ TREN ============ --}}
        <section class="card card-pad" aria-labelledby="tren-title">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 id="tren-title" class="text-base font-semibold text-ink-900">Tren 6 bulan terakhir</h2>
                    <p class="mt-0.5 text-xs text-ink-500">Perbandingan pemasukan dan pengeluaran bulanan</p>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <span class="flex items-center gap-1.5 text-ink-600">
                        <span class="size-2.5 rounded-sm bg-brand-500"></span> Masuk
                    </span>
                    <span class="flex items-center gap-1.5 text-ink-600">
                        <span class="size-2.5 rounded-sm bg-amber-400"></span> Keluar
                    </span>
                </div>
            </div>

            @if (array_sum($incomeSeries) + array_sum($expenseSeries) > 0)
                <div class="mt-7 flex h-48 items-end justify-between gap-2 sm:gap-4">
                    @foreach ($labels as $i => $label)
                        <div class="flex flex-1 flex-col items-center gap-2">
                            <div class="flex h-40 w-full items-end justify-center gap-1 sm:gap-1.5">
                                <div class="group relative w-full max-w-6 rounded-t-sm bg-brand-500 transition hover:bg-brand-600"
                                     style="height: {{ max(2, round($incomeSeries[$i] / $maxSeries * 100)) }}%">
                                    <span class="pointer-events-none absolute -top-7 left-1/2 hidden -translate-x-1/2 whitespace-nowrap rounded bg-ink-900 px-1.5 py-0.5 text-[10px] font-medium text-white group-hover:block">
                                        {{ rupiah_short($incomeSeries[$i]) }}
                                    </span>
                                </div>
                                <div class="group relative w-full max-w-6 rounded-t-sm bg-amber-400 transition hover:bg-amber-500"
                                     style="height: {{ max(2, round($expenseSeries[$i] / $maxSeries * 100)) }}%">
                                    <span class="pointer-events-none absolute -top-7 left-1/2 hidden -translate-x-1/2 whitespace-nowrap rounded bg-ink-900 px-1.5 py-0.5 text-[10px] font-medium text-white group-hover:block">
                                        {{ rupiah_short($expenseSeries[$i]) }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-ink-500">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-6 rounded-lg border border-dashed border-ink-200 py-12 text-center">
                    <p class="text-sm font-medium text-ink-700">Belum ada data untuk digambarkan</p>
                    <p class="mt-1 text-xs text-ink-500">Catat transaksi pertamamu untuk melihat tren.</p>
                    <a href="{{ route('transactions.create') }}" class="btn-secondary btn-sm mt-4">Catat sekarang</a>
                </div>
            @endif
        </section>

        {{-- ============ TARGET TABUNGAN ============ --}}
        <section class="card card-pad" aria-labelledby="target-title">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 id="target-title" class="text-base font-semibold text-ink-900">Target tabungan</h2>
                    <p class="mt-0.5 text-xs text-ink-500">Progres target aktif</p>
                </div>
                <a href="{{ route('goals.index') }}" class="text-xs font-semibold text-brand-700 hover:underline">Semua</a>
            </div>

            @if ($goals->isEmpty())
                <div class="mt-6 rounded-lg border border-dashed border-ink-200 py-10 text-center">
                    <p class="text-sm font-medium text-ink-700">Belum ada target</p>
                    <p class="mt-1 text-xs text-ink-500">Tentukan tujuan menabungmu.</p>
                    <a href="{{ route('goals.create') }}" class="btn-secondary btn-sm mt-4">Buat target</a>
                </div>
            @else
                <div class="mt-5 space-y-4">
                    @foreach ($goals as $goal)
                        @php $pct = $goal->progress_percent; @endphp
                        <div>
                            <div class="flex items-baseline justify-between gap-2">
                                <p class="truncate text-sm font-semibold text-ink-900">{{ $goal->name }}</p>
                                <p class="num text-xs font-bold text-ink-700">{{ $pct }}%</p>
                            </div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-ink-100"
                                 role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"
                                 aria-label="Progres {{ $goal->name }}">
                                <div class="h-full rounded-full {{ progress_tone($pct) }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="num mt-1.5 text-[11px] text-ink-500">
                                {{ rupiah($goal->saved_amount) }}
                                <span class="text-ink-400">/ {{ rupiah($goal->target_amount) }}</span>
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <div class="mt-5 grid gap-5 xl:grid-cols-[1.6fr_1fr]">

        {{-- ============ TRANSAKSI TERBARU ============ --}}
        <section class="card overflow-hidden" aria-labelledby="recent-title">
            <div class="flex items-center justify-between gap-3 border-b border-ink-100 px-5 py-4 sm:px-6">
                <h2 id="recent-title" class="text-base font-semibold text-ink-900">Transaksi terbaru</h2>
                <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-brand-700 hover:underline">Lihat semua</a>
            </div>

            @if ($recent->isEmpty())
                <div class="px-6 py-14 text-center">
                    <span class="mx-auto grid size-12 place-items-center rounded-full bg-ink-100 text-ink-400">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 6h13M8 12h13M8 18h13M3.5 6h.01M3.5 12h.01M3.5 18h.01" />
                        </svg>
                    </span>
                    <p class="mt-4 text-sm font-semibold text-ink-800">Belum ada transaksi</p>
                    <p class="mx-auto mt-1 max-w-xs text-pretty text-xs leading-relaxed text-ink-500">
                        Mulai dari pengeluaran kecil hari ini. Catatan pertama biasanya yang paling menentukan.
                    </p>
                    <a href="{{ route('transactions.create') }}" class="btn-primary btn-sm mt-5">Catat transaksi pertama</a>
                </div>
            @else
                <table class="table-base">
                    <thead>
                        <tr>
                            <th scope="col">Keterangan</th>
                            <th scope="col" class="hidden sm:table-cell">Kategori</th>
                            <th scope="col" class="hidden md:table-cell">Tanggal</th>
                            <th scope="col" class="text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recent as $trx)
                            <tr>
                                <td>
                                    <p class="max-w-[16rem] truncate font-medium text-ink-900">
                                        {{ $trx->note ?: ($trx->type === 'income' ? 'Pemasukan' : 'Pengeluaran') }}
                                    </p>
                                    <p class="mt-0.5 text-xs text-ink-500 sm:hidden">
                                        {{ $trx->date->format('d M Y') }}
                                    </p>
                                </td>
                                <td class="hidden sm:table-cell">
                                    @if ($trx->category)
                                        <span class="inline-flex items-center gap-1.5 text-xs text-ink-600">
                                            <span class="size-2 rounded-full" style="background: {{ $trx->category->color }}"></span>
                                            {{ $trx->category->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-ink-400">Tanpa kategori</span>
                                    @endif
                                </td>
                                <td class="num hidden whitespace-nowrap text-xs text-ink-500 md:table-cell">
                                    {{ $trx->date->format('d M Y') }}
                                </td>
                                <td class="num whitespace-nowrap text-right font-semibold {{ $trx->type === 'income' ? 'text-brand-700' : 'text-ink-900' }}">
                                    {{ $trx->type === 'income' ? '+' : '−' }}{{ rupiah($trx->amount) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>

        {{-- ============ PENGELUARAN PER KATEGORI ============ --}}
        <section class="card card-pad" aria-labelledby="kategori-title">
            <div>
                <h2 id="kategori-title" class="text-base font-semibold text-ink-900">Pengeluaran bulan ini</h2>
                <p class="mt-0.5 text-xs text-ink-500">{{ now()->translatedFormat('F Y') }} · per kategori</p>
            </div>

            @if ($byCategory->isEmpty())
                <div class="mt-6 rounded-lg border border-dashed border-ink-200 py-10 text-center">
                    <p class="text-sm font-medium text-ink-700">Belum ada pengeluaran</p>
                    <p class="mt-1 text-xs text-ink-500">Bulan ini masih bersih.</p>
                </div>
            @else
                @php $totalCat = $byCategory->sum('total'); @endphp
                <div class="mt-5 space-y-3.5">
                    @foreach ($byCategory as $row)
                        @php
                            $pct = $totalCat > 0 ? round($row->total / $totalCat * 100, 1) : 0;
                            $color = $row->category->color ?? '#8d969a';
                            $name = $row->category->name ?? 'Tanpa kategori';
                        @endphp
                        <div>
                            <div class="flex items-baseline justify-between gap-2">
                                <span class="flex min-w-0 items-center gap-2 text-sm text-ink-700">
                                    <span class="size-2 shrink-0 rounded-full" style="background: {{ $color }}"></span>
                                    <span class="truncate">{{ $name }}</span>
                                </span>
                                <span class="num shrink-0 text-xs font-semibold text-ink-800">{{ rupiah_short($row->total) }}</span>
                            </div>
                            <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-ink-100">
                                <div class="h-full rounded-full" style="width: {{ max(2, $pct) }}%; background: {{ $color }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 flex items-baseline justify-between border-t border-ink-100 pt-4">
                    <span class="text-xs font-medium text-ink-500">Total</span>
                    <span class="num text-sm font-bold text-ink-900">{{ rupiah($totalCat) }}</span>
                </div>
            @endif
        </section>
    </div>
@endsection