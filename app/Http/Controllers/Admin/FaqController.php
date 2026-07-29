<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $q = Faq::orderBy('category')->orderBy('sort_order');
        if ($category !== 'all') $q->where('category', $category);
        $faqs = $q->get();
        $categories = Faq::distinct()->pluck('category')->sort()->values();
        return view('faqs.index', compact('faqs','categories','category'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'category'   => 'required|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        Faq::create($data);
        return back()->with('success', 'FAQ added.');
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string',
            'category'   => 'required|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->has('is_active');
        $faq->update($data);
        return back()->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted.');
    }
}
