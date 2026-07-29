@extends('layouts.admin')
@section('title', 'Transactions')

@section('content')
<div class="cc-page-header">
    <h1>Transactions</h1>
    <p>All financial movements on the platform</p>
</div>

<div class="cc-stats-grid" style="margin-bottom:18px;">
    <div class="cc-stat teal">
        <div class="cc-stat-label">Platform Revenue</div>
        <div class="cc-stat-val">SGD {{ number_format($totals['revenue'], 0) }}</div>
        <div class="cc-stat-trend trend-up">Fees collected</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Total Payouts</div>
        <div class="cc-stat-val">SGD {{ number_format($totals['payouts'], 0) }}</div>
        <div class="cc-stat-trend trend-neutral">To lenders</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Refunds Issued</div>
        <div class="cc-stat-val">SGD {{ number_format($totals['refunds'], 0) }}</div>
        <div class="cc-stat-trend {{ $totals['refunds'] > 0 ? 'trend-down' : 'trend-neutral' }}">To renters</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Pending</div>
        <div class="cc-stat-val">{{ number_format($totals['pending']) }}</div>
        <div class="cc-stat-trend {{ $totals['pending'] > 0 ? 'trend-neutral' : 'trend-up' }}">Transactions</div>
    </div>
</div>

<div class="cc-card-pad" style="margin-bottom:0;border-radius:10px 10px 0 0;border-bottom:none;">
    <form method="GET" action="{{ route('admin.transactions') }}" id="filter-form">
        <div class="cc-search">
            <span class="cc-search-icon">⌕</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by user, reference or ID…"
                   oninput="document.getElementById('filter-form').submit()">
        </div>
        @php $activeFilter = request('filter','all'); @endphp
        <div class="cc-filter-row" style="margin-bottom:0;">
            @foreach(['all'=>'All','payment'=>'Payments','payout'=>'Payouts','refund'=>'Refunds','platform_fee'=>'Fees','deposit'=>'Deposits'] as $key=>$label)
                <a href="{{ route('admin.transactions', array_merge(request()->only('search'),['filter'=>$key])) }}"
                   class="cc-filter-btn {{ $activeFilter===$key?'active':'' }}">{{ $label }}</a>
            @endforeach
        </div>
    </form>
</div>

<div class="cc-card" style="border-radius:0 0 10px 10px;">
    <table class="cc-table">
        <thead>
            <tr><th>#</th><th>User</th><th>Type</th><th>Order</th><th>Amount</th><th>Status</th><th>Date</th></tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
            @php $statusColor = match($tx->status){ 'completed'=>'green','failed'=>'red','refunded'=>'amber',default=>'amber' }; @endphp
            <tr>
                <td style="font-weight:500;">{{ $tx->id }}</td>
                <td>{{ $tx->user->name ?? '—' }}</td>
                <td><span class="cc-pill teal">{{ ucfirst(str_replace('_',' ',$tx->type)) }}</span></td>
                <td>{{ $tx->order_id ? '#'.$tx->order_id : '—' }}</td>
                <td style="font-weight:500;">SGD {{ number_format($tx->amount,0) }}</td>
                <td><span class="cc-pill {{ $statusColor }}">{{ ucfirst($tx->status) }}</span></td>
                <td style="color:rgba(0,63,95,0.5);font-size:10px;">{{ $tx->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:32px;color:rgba(0,63,95,0.3);">No transactions found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($transactions->hasPages())
    <div style="padding:12px 16px;border-top:0.5px solid rgba(0,63,95,0.06);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:11px;color:rgba(0,63,95,0.4);">Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} of {{ $transactions->total() }}</span>
        <div style="display:flex;gap:4px;">
            @if($transactions->onFirstPage()) <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">← Prev</span>
            @else <a href="{{ $transactions->previousPageUrl() }}" class="cc-btn secondary">← Prev</a> @endif
            @if($transactions->hasMorePages()) <a href="{{ $transactions->nextPageUrl() }}" class="cc-btn secondary">Next →</a>
            @else <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">Next →</span> @endif
        </div>
    </div>
    @endif
</div>
@endsection
