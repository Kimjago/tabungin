@extends('layouts.auth')

@section('title', 'Daftar')

@section('form')
    <h1 class="text-2xl font-bold tracking-tight text-ink-900">Buat akun baru</h1>
    <p class="mt-1.5 text-sm text-ink-500">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-brand-700 underline-offset-4 hover:underline">Masuk di sini</a>
    </p>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            <p class="font-semibold">Ada {{ $errors->count() }} hal yang perlu diperbaiki:</p>
            <ul class="mt-1.5 list-inside list-disc space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="mt-7 space-y-5">
        @csrf

        <div>
            <label for="name" class="label">Nama lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   class="input @error('name') input-error @enderror"
                   placeholder="Nama kamu"
                   @error('name') aria-invalid="true" @enderror>
            @error('name') <p class="error-text">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   required autocomplete="username"
                   class="input @error('email') input-error @enderror"
                   placeholder="nama@email.com"
                   @error('email') aria-invalid="true" @enderror>
            @error('email') <p class="error-text">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="label">Kata sandi</label>
            <input id="password" name="password" type="password"
                   required autocomplete="new-password"
                   class="input @error('password') input-error @enderror"
                   placeholder="Minimal 8 karakter"
                   @error('password') aria-invalid="true" @enderror>
            @error('password') <p class="error-text">{{ $message }}</p> @enderror
            <p class="hint">Gunakan kombinasi huruf dan angka agar aman.</p>
        </div>

        <div>
            <label for="password_confirmation" class="label">Ulangi kata sandi</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   required autocomplete="new-password"
                   class="input" placeholder="Ketik ulang kata sandi">
        </div>

        <button type="submit" class="btn-primary w-full">Buat akun</button>
    </form>

    <p class="mt-8 border-t border-ink-100 pt-5 text-center text-xs text-ink-400">
        Akun baru otomatis dilengkapi 5 kategori umum supaya kamu bisa langsung mencatat.
    </p>
@endsection