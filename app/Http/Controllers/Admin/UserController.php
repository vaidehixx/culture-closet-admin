<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query->when($request->get('filter') === 'verified',   fn($q) => $q->where('verified', true))
              ->when($request->get('filter') === 'unverified', fn($q) => $q->where('verified', false))
              ->when($request->get('filter') === 'suspended',  fn($q) => $q->where('is_suspended', true));

        $users = $query->paginate(20)->withQueryString();

        $counts = [
            'all'        => User::count(),
            'verified'   => User::where('verified', true)->count(),
            'unverified' => User::where('verified', false)->count(),
            'suspended'  => User::where('is_suspended', true)->count(),
        ];

        return view('users.index', compact('users', 'counts'));
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function verify(User $user): RedirectResponse
    {
        $user->update(['verified' => true]);
        return back()->with('success', "{$user->name} has been verified.");
    }

    public function suspend(User $user): RedirectResponse
    {
        $user->update(['is_suspended' => true]);
        return back()->with('success', "{$user->name} has been suspended.");
    }

    public function unsuspend(User $user): RedirectResponse
    {
        $user->update(['is_suspended' => false]);
        return back()->with('success', "{$user->name}'s account has been reinstated.");
    }

    public function destroy(User $user): RedirectResponse
    {
        $name = $user->name;
        $user->delete();
        return redirect()->route('admin.users')->with('success', "{$name} has been deleted.");
    }
}
