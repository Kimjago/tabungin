<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Transaction::forUser($user->id)->with('category:id,name,color');

        if ($request->filled('type') && in_array($request->type, ['income', 'expense'], true)) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('q')) {
            $query->where('note', 'like', '%'.$request->string('q').'%');
        }

        $transactions = $query->latest('date')->latest('id')->paginate(12)->withQueryString();

        $categories = Category::forUser($user->id)->orderBy('name')->get(['id', 'name', 'type', 'color']);

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function create(Request $request): View
    {
        $categories = Category::forUser($request->user()->id)->orderBy('name')->get(['id', 'name', 'type', 'color']);

        return view('transactions.create', [
            'categories' => $categories,
            'transaction' => new Transaction(['date' => now()->toDateString(), 'type' => 'expense']),
        ]);
    }

    public function store(TransactionRequest $request): RedirectResponse
    {
        $request->user()->transactions()->create($request->validated());

        return redirect()->route('transactions.index')
            ->with('status', 'Transaksi berhasil disimpan.');
    }

    public function edit(Request $request, Transaction $transaction): View
    {
        $this->authorizeOwner($request, $transaction);

        $categories = Category::forUser($request->user()->id)->orderBy('name')->get(['id', 'name', 'type', 'color']);

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(TransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $this->authorizeOwner($request, $transaction);

        $transaction->update($request->validated());

        return redirect()->route('transactions.index')
            ->with('status', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorizeOwner($request, $transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('status', 'Transaksi berhasil dihapus.');
    }

    private function authorizeOwner(Request $request, Transaction $transaction): void
    {
        abort_unless($transaction->user_id === $request->user()->id, 403);
    }
}
