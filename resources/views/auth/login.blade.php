@extends('layouts.auth')

@section('title', 'Masuk')

@section('form')
    <h1 class="text-2xl font-bold tracking-tight text-ink-900">Masuk ke akunmu</h1>
    <p class="mt-1.5 text-sm text-ink-500">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-brand-700 underline-offset-4 hover:underline">Daftar gratis</a>
    </p>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
        @csrf

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   class="input @error('email') input-error @enderror"
                   placeholder="nama@email.com"
                   @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
            @error('email')
                <p id="email-error" class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-baseline justify-between">
                <label for="password" class="label">Kata sandi</label>
            </div>
            <input id="password" name="password" type="password"
                   required autocomplete="current-password"
                   class="input @error('password') input-error @enderror"
                   placeholder="••••••••"
                   @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
            @error('password')
                <p id="password-error" class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex cursor-pointer items-center gap-2.5 text-sm text-ink-600">
            <input type="checkbox" name="remember" value="1"
                   class="size-4 rounded border-ink-300 text-brand-600 focus:ring-brand-500/30">
            Ingat saya
        </label>

        <button type="submit" class="btn-primary w-full">Masuk</button>
    </form>

    <div class="mt-6 rounded-lg border border-ink-200 bg-ink-50 px-4 py-3">
        <p class="text-xs font-semibold text-ink-700">Akun demo untuk penilaian</p>
        <p class="mt-1 text-xs leading-relaxed text-ink-500">
            Email <span class="font-mono font-semibold text-ink-800">demo@tabungin.test</span><br>
            Kata sandi <span class="font-mono font-semibold text-ink-800">password</span>
        </p>
    </div>

    <p class="mt-8 border-t border-ink-100 pt-5 text-center text-xs text-ink-400">
        Dengan masuk, kamu menyetujui penggunaan data hanya untuk keperluan pencatatan keuangan pribadi.
    </p>
@endsection