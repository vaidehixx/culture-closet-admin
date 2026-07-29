@extends('layouts.admin')
@section('title', 'Reward Coins')
@section('content')
<div class="cc-page-header">
    <h1>Reward Coins</h1>
    <p>Manage user coin balances and transactions</p>
</div>

<div class="cc-stats-grid" style="margin-bottom:18px;">
    <div class="cc-stat teal">
        <div class="cc-stat-label">Coins in Circulation</div>
        <div class="cc-stat-val">{{ number_format($stats['total_coins']) }}</div>
        <div class="cc-stat-trend trend-neutral">Across all users</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Total Awarded</div>
        <div class="cc-stat-val">{{ number_format($stats['total_awarded']) }}</div>
        <div class="cc-stat-trend trend-up">By admins</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Total Spent</div>
        <div class="cc-stat-val">{{ number_format(abs($stats['total_spent'])) }}</div>
        <div class="cc-stat-trend trend-neutral">Redeemed by users</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Transactions</div>
        <div class="cc-stat-val">{{ number_format($stats['transactions']) }}</div>
    </div>
</div>

<div class="cc-grid-2">
    {{-- Award / Deduct Form --}}
    <div class="cc-card-pad" style="margin-bottom:14px;">
        <div class="cc-section-title" style="margin-bottom:14px;">Award or Deduct Coins</div>
        <form method="POST" action="{{ route('admin.coins.award') }}" style="margin-bottom:14px;">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">User *</div>
                    <select name="user_id" required style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                        <option value="">Select user…</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->coin_balance }} coins)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">Amount *</div>
                    <input type="number" name="amount" placeholder="50" min="1" required
                           style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
            </div>
            <div style="margin-bottom:10px;">
                <div class="cc-stat-label" style="margin-bottom:4px;">Note</div>
                <input type="text" name="description" placeholder="Reason for award…"
                       style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
            </div>
            <button type="submit" class="cc-btn primary" style="width:100%;">+ Award Coins</button>
        </form>
        <form method="POST" action="{{ route('admin.coins.deduct') }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">User *</div>
                    <select name="user_id" required style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                        <option value="">Select user…</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->coin_balance }} coins)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">Amount *</div>
                    <input type="number" name="amount" placeholder="50" min="1" required
                           style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
            </div>
            <div style="margin-bottom:10px;">
                <div class="cc-stat-label" style="margin-bottom:4px;">Note</div>
                <input type="text" name="description" placeholder="Reason for deduction…"
                       style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
            </div>
            <button type="submit" class="cc-btn danger" style="width:100%;">- Deduct Coins</button>
        </form>
    </div>

    {{-- Recent Transactions --}}
    <div class="cc-card-pad">
        <div class="cc-section-title" style="margin-bottom:12px;">Recent Transactions</div>
        @forelse($recentTx as $tx)
        <div class="cc-mini-row">
            <div>
                <div class="cc-mini-label">{{ $tx->user->name ?? '—' }}</div>
                <div class="cc-mini-sub">{{ $tx->description ?? ucfirst($tx->type) }} · {{ $tx->created_at->diffForHumans() }}</div>
            </div>
            <span class="cc-pill {{ $tx->amount > 0 ? 'green' : 'red' }}">
                {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }}
            </span>
        </div>
        @empty
        <div style="font-size:11px;color:rgba(0,63,95,0.3);padding:8px 0;">No transactions yet.</div>
        @endforelse
    </div>
</div>

{{-- User Balances Table --}}
<div class="cc-card" style="margin-top:14px;">
    <div style="padding:14px 16px 10px;">
        <div class="cc-section-head">
            <span class="cc-section-title">User Coin Balances</span>
            <form method="GET" style="display:flex;gap:8px;">
                <div class="cc-search" style="margin-bottom:0;">
                    <span class="cc-search-icon">⌕</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users…"
                           style="border:none;outline:none;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;background:transparent;width:160px;"
                           onchange="this.form.submit()">
                </div>
            </form>
        </div>
    </div>
    <table class="cc-table">
        <thead><tr><th>User</th><th>Email</th><th>Balance</th><th>Joined</th></tr></thead>
        <tbody>
        @forelse($users as $u)
        <tr>
            <td style="font-weight:500;">{{ $u->name }}</td>
            <td style="color:rgba(0,63,95,0.5);font-size:11px;">{{ $u->email }}</td>
            <td><span class="cc-pill {{ $u->coin_balance > 0 ? 'teal' : 'amber' }}">{{ number_format($u->coin_balance) }} coins</span></td>
            <td style="color:rgba(0,63,95,0.4);font-size:10px;">{{ $u->created_at->format('d M Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;padding:32px;color:rgba(0,63,95,0.3);">No users found.</td></tr>
        @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div style="padding:12px 16px;border-top:0.5px solid rgba(0,63,95,0.06);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:11px;color:rgba(0,63,95,0.4);">Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}</span>
        <div style="display:flex;gap:4px;">
            @if($users->onFirstPage()) <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">← Prev</span>
            @else <a href="{{ $users->previousPageUrl() }}" class="cc-btn secondary">← Prev</a> @endif
            @if($users->hasMorePages()) <a href="{{ $users->nextPageUrl() }}" class="cc-btn secondary">Next →</a>
            @else <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">Next →</span> @endif
        </div>
    </div>
    @endif
</div>
@endsection
