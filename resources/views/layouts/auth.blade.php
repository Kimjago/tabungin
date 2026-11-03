<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-ink-950">

<div class="grid min-h-full lg:grid-cols-[1.05fr_1fr]">

    {{-- sisi kiri: brand --}}
    <aside class="relative hidden overflow-hidden bg-ink-950 p-12 lg:flex lg:flex-col lg:justify-between">
        <div aria-hidden="true"
             class="pointer-events-none absolute -left-32 -top-32 size-[28rem] rounded-full bg-brand-600/25 blur-3xl"></div>
        <div aria-hidden="true"
             class="pointer-events-none absolute -bottom-40 -right-24 size-[26rem] rounded-full bg-brand-500/15 blur-3xl"></div>

        <a href="{{ route('landing') }}" class="relative flex items-center gap-2.5">
            <span class="grid size-10 place-items-center rounded-md bg-brand-500 text-ink-950">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v18M7 7h7.5a2.5 2.5 0 0 1 0 5H9a2.5 2.5 0 0 0 0 5h8" />
                </svg>
            </span>
            <span class="text-base font-bold tracking-tight text-white">Tabungin</span>
        </a>

        <div class="relative max-w-md">
            <p class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/15 px-3 py-1 text-xs font-medium text-brand-300">
                <span class="size-1.5 rounded-full bg-brand-400"></span>
                Kelola uang tanpa ribet
            </p>
            <h2 class="text-pretty text-4xl font-bold leading-[1.1] tracking-tight text-white">
                Setiap rupiah punya tujuan.
            </h2>
            <p class="mt-5 max-w-sm text-pretty leading-relaxed text-ink-300">
                Catat pemasukan dan pengeluaran, lalu arahkan sisanya ke target tabungan
                yang benar-benar kamu inginkan.
            </p>

            <dl class="mt-10 grid grid-cols-3 gap-4 border-t border-white/10 pt-6">
                @foreach ([['100%', 'Gratis'], ['< 1 mnt', 'Mulai'], ['6 bln', 'Riwayat tren']] as [$v, $l])
                    <div>
                        <dt class="num text-xl font-bold text-white">{{ $v }}</dt>
                        <dd class="mt-0.5 text-xs text-ink-400">{{ $l }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        <p class="relative text-xs text-ink-500">© {{ date('Y') }} Tabungin. Proyek pembelajaran Laravel.</p>
    </aside>

    {{-- sisi kanan: form --}}
    <main class="flex items-center justify-center bg-white px-5 py-12 sm:px-10">
        <div class="w-full max-w-sm">
            <a href="{{ route('landing') }}" class="mb-8 inline-flex items-center gap-2 text-sm text-ink-500 hover:text-ink-900 lg:hidden">
                <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 0 1-.02 1.06L8.832 10l3.938 3.71a.75.75 0 1 1-1.04 1.08l-4.5-4.25a.75.75 0 0 1 0-1.08l4.5-4.25a.75.75 0 0 1 1.06.02Z" clip-rule="evenodd" />
                </svg>
                Kembali
            </a>

            @yield('form')
        </div>
    </main>
</div>

</body>
</html>