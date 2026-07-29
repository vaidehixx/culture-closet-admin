<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['renter', 'lender', 'product'])->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('renter',  fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('product', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $status = $request->get('filter');
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20)->withQueryString();

        $counts = [
            'all'                => Order::count(),
            'pending_acceptance' => Order::where('status', 'pending_acceptance')->count(),
            'confirmed'          => Order::where('status', 'confirmed')->count(),
            'active'             => Order::where('status', 'active')->count(),
            'returning'          => Order::where('status', 'returning')->count(),
            'completed'          => Order::where('status', 'completed')->count(),
            'disputed'           => Order::where('status', 'disputed')->count(),
            'cancelled'          => Order::where('status', 'cancelled')->count(),
        ];

        return view('orders.index', compact('orders', 'counts'));
    }

    public function show(Order $order): View
    {
        $order->load(['renter', 'lender', 'product', 'reviews']);
        return view('orders.show', compact('order'));
    }

    public function resolve(Order $order): RedirectResponse
    {
        $order->update(['status' => 'completed']);
        return back()->with('success', "Rental #{$order->id} marked as completed.");
    }

    public function refund(Order $order): RedirectResponse
    {
        $order->update(['status' => 'cancelled']);
        return back()->with('success', "Rental #{$order->id} has been cancelled and refund initiated.");
    }
}
