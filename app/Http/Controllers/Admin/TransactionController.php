<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::with(['user', 'order'])->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        $type = $request->get('filter');
        if ($type && $type !== 'all') {
            $query->where('type', $type);
        }

        $transactions = $query->paginate(25)->withQueryString();

        $totals = [
            'revenue'  => Transaction::where('type', 'platform_fee')->where('status', 'completed')->sum('amount'),
            'payouts'  => Transaction::where('type', 'payout')->where('status', 'completed')->sum('amount'),
            'refunds'  => Transaction::where('type', 'refund')->where('status', 'completed')->sum('amount'),
            'pending'  => Transaction::where('status', 'pending')->count(),
        ];

        return view('transactions.index', compact('transactions', 'totals'));
    }

    public function show(Transaction $transaction): View
    {
        $transaction->load(['user', 'order.borrower', 'order.product']);
        return view('transactions.show', compact('transaction'));
    }
}
