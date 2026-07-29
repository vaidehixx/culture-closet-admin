<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CoinTransaction;
use Illuminate\Http\Request;

class CoinController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $usersQ = User::orderByDesc('coins');
        if ($search) {
            $usersQ->where(fn($q) => $q->where('name','like',"%$search%")->orWhere('email','like',"%$search%"));
        }
        $users = $usersQ->paginate(20)->withQueryString();

        $stats = [
            'total_coins'   => User::sum('coins'),
            'total_awarded' => CoinTransaction::where('amount', '>', 0)->sum('amount'),
            'total_spent'   => CoinTransaction::where('amount', '<', 0)->sum('amount'),
            'transactions'  => CoinTransaction::count(),
        ];

        $recentTx = CoinTransaction::with('user')->latest()->take(20)->get();

        return view('coins.index', compact('users', 'stats', 'recentTx'));
    }

    public function award(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:profiles,id',
            'amount'      => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);
        $user = User::findOrFail($request->user_id);
        $newBalance = ($user->coins ?? 0) + $request->amount;
        $user->update(['coins' => $newBalance]);
        CoinTransaction::create([
            'user_id'     => $user->id,
            'amount'      => $request->amount,
            'action'      => $request->description ?? 'Admin award',
            'action_type' => 'award',
        ]);
        return back()->with('success', 'Awarded '.$request->amount.' coins to '.$user->name.'.');
    }

    public function deduct(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:profiles,id',
            'amount'      => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);
        $user = User::findOrFail($request->user_id);
        $newBalance = max(0, ($user->coins ?? 0) - $request->amount);
        $user->update(['coins' => $newBalance]);
        CoinTransaction::create([
            'user_id'     => $user->id,
            'amount'      => -$request->amount,
            'action'      => $request->description ?? 'Admin deduction',
            'action_type' => 'deduct',
        ]);
        return back()->with('success', 'Deducted '.$request->amount.' coins from '.$user->name.'.');
    }
}
