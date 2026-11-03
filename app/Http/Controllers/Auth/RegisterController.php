<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create($request->validated());

            // kategori bawaan supaya pengguna baru tidak mulai dari layar kosong
            $defaults = [
                ['name' => 'Gaji', 'type' => 'income', 'color' => '#10b981', 'icon' => 'banknote'],
                ['name' => 'Makan & Minum', 'type' => 'expense', 'color' => '#f59e0b', 'icon' => 'utensils'],
                ['name' => 'Transportasi', 'type' => 'expense', 'color' => '#6366f1', 'icon' => 'car'],
                ['name' => 'Belanja', 'type' => 'expense', 'color' => '#ec4899', 'icon' => 'shopping-bag'],
                ['name' => 'Tagihan', 'type' => 'expense', 'color' => '#ef4444', 'icon' => 'receipt'],
            ];

            foreach ($defaults as $category) {
                $user->categories()->create($category);
            }

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('status', 'Akun berhasil dibuat. Yuk mulai catat keuanganmu!');
    }
}
