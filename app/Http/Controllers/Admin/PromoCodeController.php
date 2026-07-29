<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromoCodeController extends Controller
{
    public function index(Request $request): View
    {
        $query = PromoCode::latest();

        if ($search = $request->get('search')) {
            $query->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $filter = $request->get('filter', 'all');
        $query->when($filter === 'active',   fn($q) => $q->where('is_active', true))
              ->when($filter === 'inactive', fn($q) => $q->where('is_active', false));

        $codes = $query->paginate(20)->withQueryString();

        $counts = [
            'all'      => PromoCode::count(),
            'active'   => PromoCode::where('is_active', true)->count(),
            'inactive' => PromoCode::where('is_active', false)->count(),
        ];

        return view('promocodes.index', compact('codes', 'counts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code'        => 'required|string|unique:promo_codes,code|max:32',
            'description' => 'nullable|string|max:255',
            'type'        => 'required|in:percent,fixed',
            'value'       => 'required|numeric|min:0',
            'min_order'   => 'nullable|numeric|min:0',
            'max_uses'    => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date',
        ]);

        PromoCode::create($data);
        return back()->with('success', "Promo code {$data['code']} created.");
    }

    public function update(Request $request, PromoCode $code): RedirectResponse
    {
        $code->update(['is_active' => !$code->is_active]);
        $label = $code->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Code {$code->code} {$label}.");
    }

    public function destroy(PromoCode $code): RedirectResponse
    {
        $label = $code->code;
        $code->delete();
        return back()->with('success', "Code {$label} deleted.");
    }
}
