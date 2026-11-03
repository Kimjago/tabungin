@extends('layouts.app')

@section('title', 'Laporan')

@section('heading', 'Laporan')
@section('subheading', 'Ringkasan keuangan pada rentang tanggal tertentu')

@section('actions')
    <a href="{{ route('transactions.index') }}" class="btn-secondary btn-sm">Lihat transaksi</a>
@endsection

@section('content')
    @php
        $maxDaily = max(1, max(array_merge($dailyIncome ?: [0], $dailyExpense ?: [0])));
        $balance = $income - $expense;
        $totalCat = $perCategory->sum('total');
    @endphp

    {{-- ============ FILTER ============ --}}
    <form method="GET" action="{{ route('reports.index') }}" class="card card-pad mb-5">
        <div class="grid gap-3 sm:grid-cols-[1fr_1fr_auto]">
            <div>
                <label for="from" class="label">Dari tanggal</label>
                <input id="from" name="from" type="date" value="{{ $from->format('Y-m-d') }}"
                       max="{{ now()->toDateString() }}" class="input num">
            </div>
            <div>
                <label for="to" class="label">Sampai tanggal</label>
                <input id="to" name="to" type="date" value="{{ $to->format('Y-m-d') }}"
                       max="{{ now()->toDateString() }}" class="input num">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary">Tampilkan</button>
                <a href="{{ route('reports.index') }}" class="btn-ghost">Reset</a>
            </div>
        </div>
        <p class="mt-3 text-xs text-ink-500">
            Menampilkan {{ $from->translatedFormat('d F Y') }} – {{ $to->translatedFormat('d F Y') }}
            · {{ $count }} transaksi
        </p>
    </form>

    @if ($count === 0)
        <div class="card px-6 py-16 text-center">
            <span class="mx-auto grid size-12 place-items-center rounded-full bg-ink-100 text-ink-400">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3.75 20.25h16.5M7.5 16.5V9.75M12 16.5V4.5M16.5 16.5v-4.5" />
                </svg>
            </span>
            <p class="mt-4 text-sm font-semibold text-ink-800">Tidak ada transaksi pada rentang ini</p>
            <p class="mx-auto mt-1 max-w-xs text-xs leading-relaxed text-ink-500">
                Coba perluas rentang tanggalnya atau catat transaksi baru.
            </p>
            <a href="{{ route('transactions.create') }}" class="btn-primary btn-sm mt-5">Catat transaksi</a>
        </div>
    @else
        {{-- ============ RINGKASAN ============ --}}
        <section class="mb-5 grid gap-4 sm:grid-cols-3">
            <div class="card card-pad">
                <p class="text-sm font-medium text-ink-500">Pemasukan</p>
                <p class="num mt-2 text-xl font-bold text-brand-700">{{ rupiah($income) }}</p>
            </div>
            <div class="card card-pad">
                <p class="text-sm font-medium text-ink-500">Pengeluaran</p>
                <p class="num mt-2 text-xl font-bold text-ink-900">{{ rupiah($expense) }}</p>
            </div>
            <div class="card card-pad">
                <p class="text-sm font-medium text-ink-500">Selisih</p>
                <p class="num mt-2 text-xl font-bold {{ $balance < 0 ? 'text-red-600' : 'text-brand-700' }}">
                    {{ rupiah($balance) }}
                </p>
            </div>
        </section>

        <div class="grid gap-5 xl:grid-cols-[1.5fr_1fr]">
            {{-- ============ GRAFIK HARIAN ============ --}}
            <section class="card card-pad" aria-labelledby="harian-title">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 id="harian-title" class="text-base font-semibold text-ink-900">Arus kas harian</h2>
                        <p class="mt-0.5 text-xs text-ink-500">Masuk dan keluar per hari</p>
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

                <div class="mt-7 flex h-52 items-end gap-1 overflow-x-auto pb-1">
                    @foreach ($dailyLabels as $i => $label)
                        <div class="flex min-w-[2rem] flex-1 flex-col items-center gap-2">
                            <div class="flex h-44 w-full items-end justify-center gap-0.5">
                                <div class="w-full max-w-3 rounded-t-sm bg-brand-500 transition hover:bg-brand-600"
                                     style="height: {{ max(2, round($dailyIncome[$i] / $maxDaily * 100)) }}%"
                                     title="Masuk {{ rupiah($dailyIncome[$i]) }}"></div>
                                <div class="w-full max-w-3 rounded-t-sm bg-amber-400 transition hover:bg-amber-500"
                                     style="height: {{ max(2, round($dailyExpense[$i] / $maxDaily * 100)) }}%"
                                     title="Keluar {{ rupiah($dailyExpense[$i]) }}"></div>
                            </div>
                            <span class="text-[10px] font-medium text-ink-500">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- ============ PER KATEGORI ============ --}}
            <section class="card card-pad" aria-labelledby="perkat-title">
                <h2 id="perkat-title" class="text-base font-semibold text-ink-900">Pengeluaran per kategori</h2>
                <p class="mt-0.5 text-xs text-ink-500">Diurutkan dari terbesar</p>

                @if ($perCategory->isEmpty())
                    <p class="mt-6 rounded-lg border border-dashed border-ink-200 py-10 text-center text-sm text-ink-500">
                        Tidak ada pengeluaran.
                    </p>
                @else
                    <div class="mt-5 space-y-3.5">
                        @foreach ($perCategory as $row)
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
                                    <span class="num shrink-0 text-xs font-semibold text-ink-800">
                                        {{ rupiah_short($row->total) }}
                                        <span class="font-normal text-ink-400">({{ $pct }}%)</span>
                                    </span>
                                </div>
                                <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-ink-100">
                                    <div class="h-full rounded-full" style="width: {{ max(2, $pct) }}%; background: {{ $color }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 flex items-baseline justify-between border-t border-ink-100 pt-4">
                        <span class="text-xs font-medium text-ink-500">Total pengeluaran</span>
                        <span class="num text-sm font-bold text-ink-900">{{ rupiah($totalCat) }}</span>
                    </div>
                @endif
            </section>
        </div>
    @endif
@endsection