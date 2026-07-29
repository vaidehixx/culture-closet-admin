<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = [
            'platform_fee_percent' => PlatformSetting::get('platform_fee_percent', 10),
            'service_fee_percent'  => PlatformSetting::get('service_fee_percent', 4),
            'cleaning_fee'         => PlatformSetting::get('cleaning_fee', 5.90),
            'late_fee_per_day'     => PlatformSetting::get('late_fee_per_day', 50),
            'coin_earn_rate'       => PlatformSetting::get('coin_earn_rate', 500),
            'coin_redeem_rate'     => PlatformSetting::get('coin_redeem_rate', 500),
            'max_rental_days'      => PlatformSetting::get('max_rental_days', 30),
            'min_listing_price'    => PlatformSetting::get('min_listing_price', 10),
            'prohibited_words'     => explode(',', PlatformSetting::get('prohibited_words', 'fake,replica,copy,counterfeit,knockoff')),
            'terms_and_conditions' => PlatformSetting::get('terms_and_conditions', ''),
            'privacy_policy'       => PlatformSetting::get('privacy_policy', ''),
        ];
        return view('settings.index', compact('settings'));
    }

    public function updateFees(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'platform_fee_percent' => 'required|numeric|min:0|max:50',
            'service_fee_percent'  => 'required|numeric|min:0|max:50',
            'cleaning_fee'         => 'required|numeric|min:0',
            'late_fee_per_day'     => 'required|numeric|min:0',
            'min_listing_price'    => 'required|numeric|min:0',
            'max_rental_days'      => 'required|integer|min:1|max:365',
        ]);
        foreach ($data as $key => $value) PlatformSetting::set($key, $value);
        return back()->with('success', 'Fee settings updated.');
    }

    public function updateCoins(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'coin_earn_rate'   => 'required|numeric|min:0',
            'coin_redeem_rate' => 'required|numeric|min:1',
        ]);
        foreach ($data as $key => $value) PlatformSetting::set($key, $value);
        return back()->with('success', 'Reward coin settings updated.');
    }

    public function updateContentPolicy(Request $request): RedirectResponse
    {
        $request->validate(['prohibited_words' => 'nullable|string']);
        $words = collect(explode(',', $request->prohibited_words ?? ''))->map(fn($w) => trim($w))->filter()->join(',');
        PlatformSetting::set('prohibited_words', $words);
        return back()->with('success', 'Content policy updated.');
    }

    public function updateLegal(Request $request): RedirectResponse
    {
        $request->validate([
            'terms_and_conditions' => 'nullable|string',
            'privacy_policy'       => 'nullable|string',
        ]);
        if ($request->has('terms_and_conditions')) PlatformSetting::set('terms_and_conditions', $request->terms_and_conditions);
        if ($request->has('privacy_policy')) PlatformSetting::set('privacy_policy', $request->privacy_policy);
        return back()->with('success', 'Legal content updated.');
    }
}
