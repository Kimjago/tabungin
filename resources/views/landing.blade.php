<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tabungin — Kelola uang, capai target tabunganmu</title>
    <meta name="description" content="Tabungin membantu kamu mencatat pemasukan dan pengeluaran, memantau tren, dan menabung untuk target yang benar-benar kamu inginkan.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-ink-950 text-ink-100 antialiased selection:bg-brand-400 selection:text-ink-950">

{{-- ============ NAVBAR ============ --}}
<header class="sticky top-0 z-50 border-b border-white/[0.08] bg-ink-950/80 backdrop-blur-xl">
    <div class="mx-auto flex h-16 max-w-6xl items-center gap-6 px-5 sm:px-8">
        <a href="{{ route('landing') }}" class="flex items-center gap-2.5" aria-label="Tabungin, beranda">
            <span class="grid size-9 place-items-center rounded-md bg-brand-500 text-ink-950">
                <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v18M7 7h7.5a2.5 2.5 0 0 1 0 5H9a2.5 2.5 0 0 0 0 5h8" />
                </svg>
            </span>
            <span class="text-[15px] font-bold tracking-tight text-white">Tabungin</span>
        </a>

        <nav class="ml-auto hidden items-center gap-1 md:flex" aria-label="Navigasi halaman">
            <a href="#fitur" class="rounded-md px-3 py-2 text-sm font-medium text-ink-300 transition hover:bg-white/5 hover:text-white">Fitur</a>
            <a href="#cara-kerja" class="rounded-md px-3 py-2 text-sm font-medium text-ink-300 transition hover:bg-white/5 hover:text-white">Cara kerja</a>
            <a href="#tanya" class="rounded-md px-3 py-2 text-sm font-medium text-ink-300 transition hover:bg-white/5 hover:text-white">FAQ</a>
        </nav>

        <div class="ml-auto flex items-center gap-2 md:ml-0">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="btn-primary btn-sm">Buka dashboard</a>
            @else
                <a href="{{ route('login') }}"
                   class="hidden rounded-md px-3 py-2 text-sm font-semibold text-ink-200 transition hover:text-white sm:block">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="btn-primary btn-sm">Mulai gratis</a>
            @endauth
        </div>
    </div>
</header>

{{-- ============ HERO ============ --}}
<section class="relative overflow-hidden">
    {{-- cahaya latar --}}
    <div aria-hidden="true" class="pointer-events-none absolute inset-0">
        <div class="absolute left-1/2 top-[-18rem] size-[52rem] -translate-x-1/2 rounded-full bg-brand-600/20 blur-[120px]"></div>
        <div class="absolute -left-40 top-40 size-[30rem] rounded-full bg-emerald-500/10 blur-[100px]"></div>
    </div>

    <div class="relative mx-auto max-w-6xl px-5 pb-20 pt-20 sm:px-8 sm:pb-28 sm:pt-28">
        <div class="max-w-3xl">
            <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/[0.04] px-3.5 py-1.5 text-xs font-medium text-brand-300">
                <span class="relative flex size-1.5">
                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-brand-400 opacity-60"></span>
                    <span class="relative inline-flex size-1.5 rounded-full bg-brand-400"></span>
                </span>
                Pencatatan keuangan + target tabungan dalam satu tempat
            </p>

            <h1 class="mt-7 text-balance text-[2.6rem] font-bold leading-[1.05] tracking-[-0.03em] text-white sm:text-6xl lg:text-[4.2rem]">
                Berhenti menebak
                <span class="whitespace-nowrap underline decoration-brand-500 decoration-[0.11em] underline-offset-[0.14em]">ke mana</span>
                uangmu pergi.
            </h1>

            <p class="mt-6 max-w-xl text-pretty text-lg leading-relaxed text-ink-300">
                Tabungin mencatat setiap pemasukan dan pengeluaran, menunjukkan pola
                belanjamu, lalu membantu menyisihkan sisanya untuk tujuan yang nyata.
            </p>

            <div class="mt-9 flex flex-wrap items-center gap-3">
                <a href="{{ route('register') }}" class="btn-primary px-6 py-3 text-base">
                    Mulai gratis sekarang
                    <svg class="size-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#fitur" class="btn px-6 py-3 text-base text-ink-200 hover:bg-white/5 hover:text-white">
                    Lihat fiturnya
                </a>
            </div>

            <p class="mt-6 flex items-center gap-2 text-xs text-ink-400">
                <svg class="size-4 text-brand-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                </svg>
                Tanpa kartu kredit · Tanpa biaya bulanan · Data tetap milikmu
            </p>
        </div>

        {{-- kartu pratinjau dashboard --}}
        <div class="relative mt-16 sm:mt-20">
            <div aria-hidden="true"
                 class="absolute -inset-x-6 -top-6 bottom-0 rounded-[2rem] bg-gradient-to-b from-white/[0.06] to-transparent"></div>

            <div class="relative rounded-2xl border border-white/10 bg-ink-900/80 p-2 shadow-2xl shadow-black/60 backdrop-blur">
                <div class="rounded-xl border border-white/[0.06] bg-ink-950 p-5 sm:p-7">

                    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-white/[0.06] pb-5">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wider text-ink-500">Saldo bersih</p>
                            <p class="num mt-1.5 text-3xl font-bold tracking-tight text-white sm:text-4xl">Rp4.280.000</p>
                        </div>
                        <div class="flex gap-6">
                            <div>
                                <p class="text-xs text-ink-500">Masuk</p>
                                <p class="num mt-1 text-sm font-semibold text-brand-400">Rp7.500.000</p>
                            </div>
                            <div>
                                <p class="text-xs text-ink-500">Keluar</p>
                                <p class="num mt-1 text-sm font-semibold text-amber-400">Rp3.220.000</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-5 pt-5 sm:grid-cols-[1.4fr_1fr]">
                        {{-- grafik batang dekoratif --}}
                        <div>
                            <p class="mb-4 text-xs font-medium text-ink-400">Tren 6 bulan terakhir</p>
                            <div class="flex h-32 items-end gap-2.5" role="img"
                                 aria-label="Ilustrasi grafik tren pemasukan dan pengeluaran enam bulan">
                                @foreach ([[62, 38], [74, 45], [58, 52], [88, 41], [70, 60], [96, 48]] as [$inc, $exp])
                                    <div class="flex h-full flex-1 items-end gap-1">
                                        <div class="flex-1 rounded-t-sm bg-brand-500/80" style="height: {{ $inc }}%"></div>
                                        <div class="flex-1 rounded-t-sm bg-amber-500/60" style="height: {{ $exp }}%"></div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 flex justify-between text-[10px] text-ink-600">
                                @foreach (['Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov'] as $m)
                                    <span>{{ $m }}</span>
                                @endforeach
                            </div>
                        </div>

                        {{-- target tabungan --}}
                        <div class="space-y-3">
                            <p class="text-xs font-medium text-ink-400">Target aktif</p>
                            @foreach ([['Dana Darurat', 78, 'Rp15,6 jt', 'Rp20 jt'], ['Liburan Bali', 42, 'Rp2,5 jt', 'Rp6 jt']] as [$name, $pct, $now, $target])
                                <div class="rounded-lg border border-white/[0.06] bg-white/[0.02] p-3">
                                    <div class="flex items-baseline justify-between gap-2">
                                        <p class="truncate text-xs font-semibold text-ink-100">{{ $name }}</p>
                                        <p class="num text-[11px] font-semibold text-brand-400">{{ $pct }}%</p>
                                    </div>
                                    <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-white/10">
                                        <div class="h-full rounded-full bg-brand-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <p class="num mt-2 text-[11px] text-ink-500">{{ $now }} <span class="text-ink-600">/ {{ $target }}</span></p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ ANGKA ============ --}}
<section class="border-y border-white/[0.08] bg-white/[0.02]">
    <div class="mx-auto grid max-w-6xl grid-cols-2 gap-px px-5 sm:px-8 lg:grid-cols-4">
        @foreach ([
            ['3', 'jenis catatan', 'Masuk, keluar, dan setoran tabungan'],
            ['100%', 'gratis', 'Tanpa langganan atau biaya tersembunyi'],
            ['6 bln', 'tren', 'Lihat pola keuanganmu membentuk'],
            ['1 akun', 'semua data', 'Tersimpan rapi dan bisa diubah kapan saja'],
        ] as [$value, $label, $desc])
            <div class="border-white/[0.06] px-1 py-8 [&:not(:last-child)]:border-r sm:px-4">
                <p class="num text-2xl font-bold tracking-tight text-white sm:text-3xl">{{ $value }}</p>
                <p class="mt-1 text-sm font-semibold text-brand-400">{{ $label }}</p>
                <p class="mt-1.5 text-xs leading-relaxed text-ink-500">{{ $desc }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ============ FITUR ============ --}}
<section id="fitur" class="scroll-mt-20 py-20 sm:py-28">
    <div class="mx-auto max-w-6xl px-5 sm:px-8">
        <div class="max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-brand-400">Fitur</p>
            <h2 class="mt-3 text-balance text-3xl font-bold tracking-tight text-white sm:text-4xl">
                Cukup empat hal untuk mengendalikan keuangan
            </h2>
            <p class="mt-4 text-pretty leading-relaxed text-ink-400">
                Bukan aplikasi akuntansi. Tabungin hanya melakukan hal-hal yang benar-benar
                kamu pakai setiap hari.
            </p>
        </div>

        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['M3 7.5A2.25 2.25 0 0 1 5.25 5.25h13.5A2.25 2.25 0 0 1 21 7.5v9A2.25 2.25 0 0 1 18.75 18.75H5.25A2.25 2.25 0 0 1 3 16.5v-9Zm13.5 3.75h.008v.008H16.5V11.25Z',
                 'Catat transaksi', 'Pemasukan dan pengeluaran tercatat dalam hitungan detik, lengkap dengan kategori dan catatan.'],
                ['M3.75 20.25h16.5M7.5 16.5V9.75M12 16.5V4.5M16.5 16.5v-4.5',
                 'Lihat polanya', 'Grafik enam bulan dan rincian per kategori menunjukkan ke mana uangmu sebenarnya pergi.'],
                ['M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-4.5a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm0-3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z',
                 'Target tabungan', 'Buat target dengan tenggat, setor sedikit-sedikit, dan lihat progresnya naik secara nyata.'],
                ['M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 0 0 3.182 0l4.318-4.318a2.25 2.25 0 0 0 0-3.182L11.16 3.659A2.25 2.25 0 0 0 9.568 3Z',
                 'Kategori sendiri', 'Buat kategori sesuai gaya hidupmu, lengkap dengan warna agar mudah dikenali.'],
            ] as [$icon, $title, $desc])
                <article class="group rounded-xl border border-white/[0.08] bg-white/[0.02] p-6 transition hover:border-brand-500/30 hover:bg-white/[0.04]">
                    <span class="grid size-10 place-items-center rounded-md bg-brand-500/10 text-brand-400 transition group-hover:bg-brand-500/15">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $icon }}" />
                        </svg>
                    </span>
                    <h3 class="mt-4 text-base font-semibold text-white">{{ $title }}</h3>
                    <p class="mt-2 text-pretty text-sm leading-relaxed text-ink-400">{{ $desc }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ CARA KERJA ============ --}}
<section id="cara-kerja" class="scroll-mt-20 border-y border-white/[0.08] bg-white/[0.02] py-20 sm:py-28">
    <div class="mx-auto max-w-6xl px-5 sm:px-8">
        <div class="grid gap-14 lg:grid-cols-[0.85fr_1.15fr] lg:items-center">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-brand-400">Cara kerja</p>
                <h2 class="mt-3 text-balance text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    Tiga langkah, lalu kebiasaan terbentuk
                </h2>
                <p class="mt-4 text-pretty leading-relaxed text-ink-400">
                    Semua dimulai dari mencatat. Setelah data terkumpul, keputusan
                    keuangan jadi jauh lebih mudah diambil.
                </p>

                <a href="{{ route('register') }}" class="btn-primary mt-8">
                    Coba sekarang
                </a>
            </div>

            <ol class="relative space-y-8 border-l border-white/10 pl-8">
                @foreach ([
                    ['Catat apa adanya', 'Mulai dari transaksi hari ini. Tidak perlu rapi di awal — yang penting tercatat.'],
                    ['Baca polanya', 'Setelah beberapa minggu, grafik dan rincian kategori menunjukkan kebiasaanmu.'],
                    ['Arahkan sisanya', 'Tentukan target, setor rutin, dan biarkan progresnya terlihat setiap kali kamu buka.'],
                ] as $i => [$title, $desc])
                    <li class="relative">
                        <span class="num absolute -left-[2.85rem] grid size-9 place-items-center rounded-full border border-white/10 bg-ink-950 text-sm font-bold text-brand-400">
                            {{ $i + 1 }}
                        </span>
                        <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
                        <p class="mt-2 text-pretty text-sm leading-relaxed text-ink-400">{{ $desc }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>

{{-- ============ FAQ ============ --}}
<section id="tanya" class="scroll-mt-20 py-20 sm:py-28">
    <div class="mx-auto max-w-3xl px-5 sm:px-8">
        <div class="text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-brand-400">FAQ</p>
            <h2 class="mt-3 text-balance text-3xl font-bold tracking-tight text-white sm:text-4xl">
                Pertanyaan yang sering muncul
            </h2>
        </div>

        <div class="mt-10 space-y-3">
            @foreach ([
                ['Apakah benar-benar gratis?', 'Ya. Tabungin dibuat sebagai proyek pembelajaran, jadi tidak ada biaya langganan maupun fitur terkunci.'],
                ['Apakah data saya aman?', 'Setiap pengguna hanya bisa melihat dan mengubah datanya sendiri. Setiap permintaan diperiksa kepemilikannya di sisi server.'],
                ['Perlu install aplikasi?', 'Tidak. Tabungin berjalan di browser, baik di laptop maupun ponsel.'],
                ['Bisa dipakai untuk usaha kecil?', 'Bisa. Kamu dapat membuat kategori sendiri untuk memisahkan keuangan pribadi dan usaha.'],
            ] as [$q, $a])
                <details class="group rounded-lg border border-white/[0.08] bg-white/[0.02] px-5 open:bg-white/[0.04]">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-4 text-sm font-semibold text-white marker:hidden">
                        {{ $q }}
                        <svg class="size-4 shrink-0 text-ink-500 transition group-open:rotate-45" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                        </svg>
                    </summary>
                    <p class="pb-4 text-pretty text-sm leading-relaxed text-ink-400">{{ $a }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ CTA ============ --}}
<section class="pb-20 sm:pb-28">
    <div class="mx-auto max-w-6xl px-5 sm:px-8">
        <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-brand-600/20 via-ink-900 to-ink-900 px-6 py-14 text-center sm:px-12 sm:py-20">
            <div aria-hidden="true" class="pointer-events-none absolute -right-20 -top-20 size-72 rounded-full bg-brand-500/20 blur-3xl"></div>
            <h2 class="relative text-balance text-3xl font-bold tracking-tight text-white sm:text-4xl">
                Mulai dari satu transaksi hari ini
            </h2>
            <p class="relative mx-auto mt-4 max-w-md text-pretty leading-relaxed text-ink-300">
                Gratis, tanpa kartu kredit, dan siap dipakai dalam satu menit.
            </p>
            <a href="{{ route('register') }}" class="btn-primary relative mt-8 px-6 py-3 text-base">
                Buat akun gratis
            </a>
        </div>
    </div>
</section>

{{-- ============ FOOTER ============ --}}
<footer class="border-t border-white/[0.08]">
    <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-5 py-8 sm:flex-row sm:px-8">
        <div class="flex items-center gap-2.5">
            <span class="grid size-7 place-items-center rounded-md bg-brand-500 text-ink-950">
                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v18M7 7h7.5a2.5 2.5 0 0 1 0 5H9a2.5 2.5 0 0 0 0 5h8" />
                </svg>
            </span>
            <span class="text-sm font-semibold text-white">Tabungin</span>
        </div>
        <p class="text-xs text-ink-500">
            © {{ date('Y') }} Tabungin · Proyek Tugas Laravel
        </p>
    </div>
</footer>

</body>
</html>