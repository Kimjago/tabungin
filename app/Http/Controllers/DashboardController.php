<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $totals = Transaction::forUser($user->id)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END), 0) as income,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as expense
            ")
            ->first();

        $income = (float) $totals->income;
        $expense = (float) $totals->expense;

        $recent = Transaction::forUser($user->id)
            ->with('category:id,name,color')
            ->latest('date')
            ->latest('id')
            ->limit(8)
            ->get();

        // pengeluaran per kategori bulan ini
        $byCategory = Transaction::forUser($user->id)
            ->expense()
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->with('category:id,name,color')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // tren 6 bulan terakhir
        $labels = [];
        $incomeSeries = [];
        $expenseSeries = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->translatedFormat('M');

            $sums = Transaction::forUser($user->id)
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->selectRaw("
                    COALESCE(SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END), 0) as income,
                    COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as expense
                ")
                ->first();

            $incomeSeries[] = (float) $sums->income;
            $expenseSeries[] = (float) $sums->expense;
        }

        $goals = SavingsGoal::forUser($user->id)
            ->where('status', 'active')
            ->orderByRaw('saved_amount / NULLIF(target_amount, 0) DESC')
            ->limit(3)
            ->get();

        $savedTotal = (float) SavingsGoal::forUser($user->id)
            ->where('status', 'active')
            ->sum('saved_amount');

        // navigasi bulan
        $month = $request->filled('month')
            ? Carbon::createFromFormat('Y-m', $request->string('month')->toString())->startOfMonth()
            : now()->startOfMonth();

        return view('dashboard', [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'recent' => $recent,
            'byCategory' => $byCategory,
            'labels' => $labels,
            'incomeSeries' => $incomeSeries,
            'expenseSeries' => $expenseSeries,
            'goals' => $goals,
            'savedTotal' => $savedTotal,
            'month' => $month,
        ]);
    }
}
