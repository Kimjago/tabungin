<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavingsGoalRequest;
use App\Models\SavingsGoal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SavingsGoalController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $goals = SavingsGoal::forUser($user->id)
            ->withCount('contributions')
            ->orderByRaw("FIELD(status, 'active', 'completed', 'cancelled')")
            ->latest('created_at')
            ->get();

        $totalTarget = (float) $goals->where('status', 'active')->sum('target_amount');
        $totalSaved = (float) $goals->where('status', 'active')->sum('saved_amount');

        return view('goals.index', compact('goals', 'totalTarget', 'totalSaved'));
    }

    public function create(): View
    {
        return view('goals.create', [
            'goal' => new SavingsGoal(['color' => '#10b981']),
        ]);
    }

    public function store(SavingsGoalRequest $request): RedirectResponse
    {
        $request->user()->savingsGoals()->create($request->validated());

        return redirect()->route('goals.index')
            ->with('status', 'Target tabungan berhasil dibuat.');
    }

    public function edit(Request $request, SavingsGoal $goal): View
    {
        $this->authorizeOwner($request, $goal);

        return view('goals.edit', compact('goal'));
    }

    public function update(SavingsGoalRequest $request, SavingsGoal $goal): RedirectResponse
    {
        $this->authorizeOwner($request, $goal);

        $goal->update($request->validated());

        return redirect()->route('goals.index')
            ->with('status', 'Target tabungan berhasil diperbarui.');
    }

    public function destroy(Request $request, SavingsGoal $goal): RedirectResponse
    {
        $this->authorizeOwner($request, $goal);

        $goal->delete();

        return redirect()->route('goals.index')
            ->with('status', 'Target tabungan dihapus.');
    }

    /**
     * Setor ke target tabungan. Saldo target dihitung ulang dari
     * total kontribusi di dalam satu transaksi agar selalu konsisten.
     */
    public function deposit(Request $request, SavingsGoal $goal): RedirectResponse
    {
        $this->authorizeOwner($request, $goal);

        // terima "250.000" maupun "250000"
        if ($request->filled('amount')) {
            $request->merge([
                'amount' => preg_replace('/[^0-9]/', '', (string) $request->input('amount')),
            ]);
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
            'note' => ['nullable', 'string', 'max:255'],
            'date' => ['required', 'date', 'before_or_equal:today'],
        ], [
            'amount.required' => 'Nominal setoran wajib diisi.',
            'amount.min' => 'Setoran minimal Rp1.000.',
            'date.required' => 'Tanggal setoran wajib diisi.',
            'date.before_or_equal' => 'Tanggal tidak boleh di masa depan.',
        ]);

        DB::transaction(function () use ($goal, $validated) {
            $goal->contributions()->create($validated);

            $goal->update([
                'saved_amount' => $goal->contributions()->sum('amount'),
            ]);

            if ((float) $goal->saved_amount >= (float) $goal->target_amount) {
                $goal->update(['status' => 'completed']);
            }
        });

        return back()->with('status', 'Setoran berhasil dicatat.');
    }

    private function authorizeOwner(Request $request, SavingsGoal $goal): void
    {
        abort_unless($goal->user_id === $request->user()->id, 403);
    }
}
