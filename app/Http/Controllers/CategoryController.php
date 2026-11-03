<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $categories = Category::forUser($user->id)
            ->withCount('transactions')
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create', [
            'category' => new Category(['color' => '#6366f1', 'type' => 'expense', 'icon' => 'wallet']),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $request->user()->categories()->create($request->validated());

        return redirect()->route('categories.index')
            ->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Request $request, Category $category): View
    {
        $this->authorizeOwner($request, $category);

        return view('categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorizeOwner($request, $category);

        $category->update($request->validated());

        return redirect()->route('categories.index')
            ->with('status', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        $this->authorizeOwner($request, $category);

        // transaksi tetap ada; foreign key diset null
        $category->delete();

        return redirect()->route('categories.index')
            ->with('status', 'Kategori dihapus. Transaksi terkait tetap tersimpan.');
    }

    private function authorizeOwner(Request $request, Category $category): void
    {
        abort_unless($category->user_id === $request->user()->id, 403);
    }
}
