<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_gmv'     => Order::whereIn('status', ['completed', 'active'])->sum('total'),
            'platform_rev'  => Transaction::where('type', 'platform_fee')->where('status', 'completed')->sum('amount'),
            'total_orders'  => Order::count(),
            'total_users'   => User::where('is_admin', false)->count(),
            'total_listings'=> Product::where('status', 'approved')->count(),
            'avg_order'     => Order::whereIn('status', ['completed', 'active'])->avg('total') ?? 0,
        ];

        // Monthly GMV for chart (last 6 months)
        $monthlyGmv = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyGmv->push([
                'label'  => $month->format('M'),
                'amount' => Order::whereIn('status', ['completed', 'active'])
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total'),
            ]);
        }

        // Monthly signups (last 6 months)
        $monthlyUsers = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyUsers->push([
                'label' => $month->format('M'),
                'count' => User::where('is_admin', false)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ]);
        }

        // Category breakdown
        $categoryBreakdown = Product::where('status', 'approved')
            ->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        return view('analytics.index', compact('stats', 'monthlyGmv', 'monthlyUsers', 'categoryBreakdown'));
    }
}
