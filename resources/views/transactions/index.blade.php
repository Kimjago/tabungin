@extends('layouts.app')

@section('title', 'Transaksi')

@section('heading', 'Transaksi')
@section('subheading', 'Semua pemasukan dan pengeluaran yang kamu catat')

@section('actions')
    <a href="{{ route('transactions.create') }}" class="btn-primary btn-sm">Catat transaksi</a>
@endsection

@section('content')
    {{-- filter --}}
    <form method="GET" action="{{ route('transactions.index') }}"
          class="card card-pad mb-5">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1fr_auto_auto_auto]">
            <div>
                <label for="q" class="sr-only">Cari catatan</label>
                <input id="q" name="q" type="search" value="{{ request('q') }}"
                       class="input" placeholder="Cari catatan transaksi…">
            </div>

            <div>
                <label for="type" class="sr-only">Jenis</label>
                <select id="type" name="type" class="input">
                    <option value="">Semua jenis</option>
                    <option value="income" @selected(request('type') === 'income')>Pemasukan</option>
                    <option value="expense" @selected(request('type') === 'expense')>Pengeluaran</option>
                </select>
            </div>

            <div>
                <label for="category_id" class="sr-only">Kategori</label>
                <select id="category_id" name="category_id" class="input">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string) request('category_id') === (string) $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-secondary flex-1">Terapkan</button>
                @if (request()->hasAny(['q', 'type', 'category_id']))
                    <a href="{{ route('transactions.index') }}" class="btn-ghost">Reset</a>
                @endif
            </div>
        </div>
    </form>

    <div class="card overflow-hidden">
        @if ($transactions->isEmpty())
            <div class="px-6 py-16 text-center">
                <span class="mx-auto grid size-12 place-items-center rounded-full bg-ink-100 text-ink-400">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 6h13M8 12h13M8 18h13M3.5 6h.01M3.5 12h.01M3.5 18h.01" />
                    </svg>
                </span>
                <p class="mt-4 text-sm font-semibold text-ink-800">
                    {{ request()->hasAny(['q', 'type', 'category_id']) ? 'Tidak ada transaksi yang cocok' : 'Belum ada transaksi' }}
                </p>
                <p class="mx-auto mt-1 max-w-sm text-pretty text-xs leading-relaxed text-ink-500">
                    {{ request()->hasAny(['q', 'type', 'category_id'])
                        ? 'Coba ubah kata kunci atau hapus filternya.'
                        : 'Setiap catatan kecil membantu kamu memahami pola pengeluaran.' }}
                </p>
                @if (request()->hasAny(['q', 'type', 'category_id']))
                    <a href="{{ route('transactions.index') }}" class="btn-secondary btn-sm mt-5">Hapus filter</a>
                @else
                    <a href="{{ route('transactions.create') }}" class="btn-primary btn-sm mt-5">Catat transaksi</a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table-base">
                    <thead>
                        <tr>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col" class="text-right">Nominal</th>
                            <th scope="col" class="text-right"><span class="sr-only">Aksi</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $trx)
                            <tr>
                                <td>
                                    <p class="max-w-[18rem] truncate font-medium text-ink-900">
                                        {{ $trx->note ?: 'Tanpa catatan' }}
                                    </p>
                                    <div class="mt-1">
                                        <x-badge :variant="$trx->type">
                                            {{ $trx->type === 'income' ? 'Masuk' : 'Keluar' }}
                                        </x-badge>
                                    </div>
                                </td>
                                <td>
                                    @if ($trx->category)
                                        <span class="inline-flex items-center gap-1.5 text-xs text-ink-600">
                                            <span class="size-2 rounded-full" style="background: {{ $trx->category->color }}"></span>
                                            {{ $trx->category->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-ink-400">—</span>
                                    @endif
                                </td>
                                <td class="num whitespace-nowrap text-xs text-ink-500">{{ $trx->date->format('d M Y') }}</td>
                                <td class="num whitespace-nowrap text-right font-semibold {{ $trx->type === 'income' ? 'text-brand-700' : 'text-ink-900' }}">
                                    {{ $trx->type === 'income' ? '+' : '−' }}{{ rupiah($trx->amount) }}
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('transactions.edit', $trx) }}"
                                           class="btn-ghost btn-sm" aria-label="Ubah transaksi {{ $trx->note }}">
                                            Ubah
                                        </a>
                                        <form method="POST" action="{{ route('transactions.destroy', $trx) }}"
                                              onsubmit="return confirm('Hapus transaksi ini? Tindakan ini tidak bisa dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-ghost btn-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($transactions->hasPages())
                <div class="border-t border-ink-100 px-5 py-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection