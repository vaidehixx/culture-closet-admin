<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['reviewer', 'reviewee', 'product'])->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('reviewer', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('product', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $filter = $request->get('filter', 'all');
        $query->when($filter === 'pending',  fn($q) => $q->where('status', 'pending'))
              ->when($filter === 'approved', fn($q) => $q->where('status', 'approved'))
              ->when($filter === 'rejected', fn($q) => $q->where('status', 'rejected'))
              ->when($filter === 'low',      fn($q) => $q->where('rating', '<=', 2));

        $reviews = $query->paginate(20)->withQueryString();

        $counts = [
            'all'      => Review::count(),
            'pending'  => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'low'      => Review::where('rating', '<=', 2)->count(),
        ];

        return view('reviews.index', compact('reviews', 'counts'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Review approved.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return back()->with('success', 'Review removed.');
    }
}
