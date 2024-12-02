<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $totalCategories = $categories->count();
        $incomeCategories = $categories->where('type', 'income')->count();
        $expenseCategories = $categories->where('type', 'expense')->count();

        $title = 'Categories'; // Set the title variable

        return view('categories.index', compact(
            'title',
            'categories',
            'totalCategories',
            'incomeCategories',
            'expenseCategories'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['type'] = 'income'; // Always set type as income

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->transactions()->exists()) {
            return back()->with('error', 'Cannot delete category with existing transactions.');
        }

        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
