<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $from = $request->filled('from')
            ? Carbon::parse($request->string('from')->toString())->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->string('to')->toString())->endOfDay()
            : now()->endOfDay();

        // jaga agar rentang tidak terbalik
        if ($to->lt($from)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        $base = Transaction::forUser($user->id)->whereBetween('date', [$from, $to]);

        $summary = (clone $base)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END), 0) as income,
                COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as expense,
                COUNT(*) as total
            ")
            ->first();

        $perCategory = (clone $base)
            ->expense()
            ->selectRaw('category_id, SUM(amount) as total, COUNT(*) as jumlah')
            ->groupBy('category_id')
            ->with('category:id,name,color')
            ->orderByDesc('total')
            ->get();

        // rincian harian untuk grafik
        $daily = (clone $base)
            ->selectRaw('date, type, SUM(amount) as total')
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        $dailyLabels = [];
        $dailyIncome = [];
        $dailyExpense = [];

        foreach ($daily as $date => $rows) {
            $dailyLabels[] = Carbon::parse($date)->format('d/m');
            $dailyIncome[] = (float) ($rows->firstWhere('type', 'income')?->total ?? 0);
            $dailyExpense[] = (float) ($rows->firstWhere('type', 'expense')?->total ?? 0);
        }

        return view('reports.index', [
            'from' => $from,
            'to' => $to,
            'income' => (float) $summary->income,
            'expense' => (float) $summary->expense,
            'count' => (int) $summary->total,
            'perCategory' => $perCategory,
            'dailyLabels' => $dailyLabels,
            'dailyIncome' => $dailyIncome,
            'dailyExpense' => $dailyExpense,
        ]);
    }
}
