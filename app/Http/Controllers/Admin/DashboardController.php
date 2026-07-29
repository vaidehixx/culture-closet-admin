<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $gmv = Order::whereIn('status', ['completed', 'active', 'returning'])->sum('total_amount');
        $lastMonthGmv = Order::whereIn('status', ['completed', 'active', 'returning'])
            ->where('created_at', '<', now()->startOfMonth())
            ->where('created_at', '>=', now()->subMonth()->startOfMonth())
            ->sum('total_amount');

        $stats = [
            'gmv'              => $gmv,
            'gmv_growth'       => $lastMonthGmv > 0 ? round((($gmv - $lastMonthGmv) / $lastMonthGmv) * 100) : 0,
            'active_users'     => User::where('is_suspended', false)->count(),
            'user_growth'      => User::whereMonth('created_at', now()->month)->count(),
            'active_listings'  => Product::where('status', 'approved')->count(),
            'listings_pending' => Product::where('status', 'pending')->count(),
            'monthly_orders'   => Order::whereMonth('created_at', now()->month)->count(),
            'order_growth'     => 0,
            'revenue'          => Order::whereIn('status', ['completed', 'active', 'returning'])->sum('platform_fee_amount'),
            'coins_total'      => User::sum('coins'),
            'avg_rental_days'  => 0,
            'pending_products' => Product::where('status', 'pending')->count(),
            'pending_payouts'  => 0,
            'open_reports'     => 0,
            'cleaning_queue'   => 0,
        ];

        return view('dashboard.index', [
            'stats'           => $stats,
            'recentOrders'    => Order::with(['renter', 'product'])->latest()->take(6)->get(),
            'pendingProducts' => Product::with('owner')->where('status', 'pending')->latest()->take(5)->get(),
            'newUsers'        => User::latest()->take(5)->get(),
            'topCategories'   => collect(),
            'openReports'     => collect(),
        ]);
    }
}
