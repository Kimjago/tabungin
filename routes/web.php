<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SavingsGoalController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('transactions', TransactionController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('goals', SavingsGoalController::class)->except(['show']);

    Route::post('/goals/{goal}/deposit', [SavingsGoalController::class, 'deposit'])
        ->name('goals.deposit');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
