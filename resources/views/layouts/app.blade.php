<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-ink-50">

<div x-data="{ sidebar: false }" class="min-h-full lg:flex">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="hidden w-64 shrink-0 border-r border-ink-200/80 bg-ink-100/40 lg:flex lg:flex-col">
        <div class="flex h-16 items-center gap-2.5 border-b border-ink-200/80 px-5">
            <span class="grid size-9 place-items-center rounded-md bg-brand-600 text-white shadow-soft">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v18M7 7h7.5a2.5 2.5 0 0 1 0 5H9a2.5 2.5 0 0 0 0 5h8" />
                </svg>
            </span>
            <span class="text-[15px] font-bold tracking-tight text-ink-900">Tabungin</span>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto p-3" aria-label="Navigasi utama">
            <p class="px-3 pb-1 pt-2 text-[11px] font-semibold uppercase tracking-wider text-ink-400">Utama</p>
            <x-nav-link href="{{ route('dashboard') }}" icon="home"
                        :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
            <x-nav-link href="{{ route('transactions.index') }}" icon="list"
                        :active="request()->routeIs('transactions.*')">Transaksi</x-nav-link>
            <x-nav-link href="{{ route('goals.index') }}" icon="target"
                        :active="request()->routeIs('goals.*')">Target Tabungan</x-nav-link>
            <x-nav-link href="{{ route('reports.index') }}" icon="chart"
                        :active="request()->routeIs('reports.*')">Laporan</x-nav-link>

            <p class="px-3 pb-1 pt-4 text-[11px] font-semibold uppercase tracking-wider text-ink-400">Pengaturan</p>
            <x-nav-link href="{{ route('categories.index') }}" icon="tag"
                        :active="request()->routeIs('categories.*')">Kategori</x-nav-link>
        </nav>

        <div class="border-t border-ink-200/80 p-3">
            <div class="flex items-center gap-3 rounded-md px-2 py-2">
                <span class="grid size-9 shrink-0 place-items-center rounded-full bg-ink-800 text-xs font-bold text-white">
                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-ink-900">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-ink-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="nav-link w-full text-left">
                    <svg class="size-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H3" />
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ================= KONTEN ================= --}}
    <div class="flex min-w-0 flex-1 flex-col">

        {{-- topbar --}}
        <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-ink-200/80 bg-white/85 px-4 backdrop-blur-md sm:px-6">
            <div class="flex items-center gap-2 lg:hidden">
                <span class="grid size-8 place-items-center rounded-md bg-brand-600 text-white">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3v18M7 7h7.5a2.5 2.5 0 0 1 0 5H9a2.5 2.5 0 0 0 0 5h8" />
                    </svg>
                </span>
                <span class="text-sm font-bold text-ink-900">Tabungin</span>
            </div>

            <div class="hidden min-w-0 flex-1 lg:block">
                <h1 class="truncate text-sm font-semibold text-ink-900">@yield('heading', 'Dashboard')</h1>
                <p class="truncate text-xs text-ink-500">@yield('subheading', 'Ringkasan keuanganmu hari ini')</p>
            </div>

            <div class="ml-auto flex items-center gap-2">
                @yield('actions')
            </div>
        </header>

        {{-- toast --}}
        @if (session('status'))
            <div class="px-4 pt-4 sm:px-6" role="status" aria-live="polite">
                <div class="flex items-start gap-3 rounded-lg border border-brand-600/20 bg-brand-50 px-4 py-3 text-sm text-brand-800">
                    <svg class="mt-0.5 size-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                    </svg>
                    <p class="flex-1">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <main class="flex-1 px-4 py-5 sm:px-6 sm:py-6">
            @yield('content')
        </main>

        <footer class="border-t border-ink-200/80 px-4 py-4 text-center text-xs text-ink-400 sm:px-6">
            {{ config('app.name') }} · Dibuat untuk Tugas Project Laravel
        </footer>
    </div>
</div>

</body>
</html>