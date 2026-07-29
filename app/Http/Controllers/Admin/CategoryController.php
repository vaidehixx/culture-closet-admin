<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        $categories->each(function ($cat) {
            $cat->product_count = Product::where('category', $cat->name)->count();
        });

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:255',
            'icon'        => 'nullable|string|max:10',
            'sort_order'  => 'nullable|integer',
        ]);

        $data['slug'] = Str::slug($data['name']);
        Category::create($data);

        return back()->with('success', "Category '{$data['name']}' created.");
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update(['is_active' => !$category->is_active]);
        $label = $category->is_active ? 'enabled' : 'disabled';
        return back()->with('success', "'{$category->name}' {$label}.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $name = $category->name;
        $category->delete();
        return back()->with('success', "Category '{$name}' deleted.");
    }
}
