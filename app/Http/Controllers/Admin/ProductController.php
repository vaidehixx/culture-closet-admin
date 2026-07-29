<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('owner')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('owner', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        match($request->get('filter')) {
            'pending'  => $query->pending(),
            'approved' => $query->approved(),
            'rejected' => $query->rejected(),
            'featured' => $query->featured(),
            default    => null,
        };

        $products = $query->paginate(20)->withQueryString();

        $counts = [
            'all'      => Product::count(),
            'pending'  => Product::pending()->count(),
            'approved' => Product::approved()->count(),
            'rejected' => Product::rejected()->count(),
            'featured' => Product::featured()->count(),
        ];

        return view('products.index', compact('products', 'counts'));
    }

    public function show(Product $product): View
    {
        $product->load('owner');
        return view('products.show', compact('product'));
    }

    public function approve(Product $product): RedirectResponse
    {
        $product->update(['status' => 'approved', 'reject_reason' => null]);
        return back()->with('success', "'{$product->name}' has been approved.");
    }

    public function reject(Request $request, Product $product): RedirectResponse
    {
        $product->update([
            'status'        => 'rejected',
            'reject_reason' => $request->input('reason', 'Does not meet listing guidelines.'),
        ]);
        return back()->with('success', "'{$product->name}' has been rejected.");
    }

    public function feature(Product $product): RedirectResponse
    {
        $product->update(['is_featured' => !$product->is_featured]);
        $label = $product->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "'{$product->name}' has been {$label}.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();
        return redirect()->route('admin.products')->with('success', "'{$name}' has been deleted.");
    }
}
