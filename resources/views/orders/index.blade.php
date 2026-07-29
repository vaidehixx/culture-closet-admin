@extends('layouts.admin')
@section('title', 'Orders')

@section('content')
<div class="cc-page-header">
    <h1>Orders</h1>
    <p>All rental transactions on the platform</p>
</div>

<div class="cc-stats-grid" style="margin-bottom:18px;">
    <div class="cc-stat teal">
        <div class="cc-stat-label">Total Orders</div>
        <div class="cc-stat-val">{{ number_format($counts['all']) }}</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Pending</div>
        <div class="cc-stat-val">{{ number_format($counts['pending']) }}</div>
        <div class="cc-stat-trend {{ $counts['pending'] > 0 ? 'trend-neutral' : 'trend-up' }}">Awaiting confirmation</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Active</div>
        <div class="cc-stat-val">{{ number_format($counts['active']) }}</div>
        <div class="cc-stat-trend trend-up">Currently rented</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Disputed</div>
        <div class="cc-stat-val">{{ number_format($counts['disputed']) }}</div>
        <div class="cc-stat-trend {{ $counts['disputed'] > 0 ? 'trend-down' : 'trend-neutral' }}">
            {{ $counts['disputed'] > 0 ? 'Needs attention' : 'All clear' }}
        </div>
    </div>
</div>

<div class="cc-card-pad" style="margin-bottom:0; border-radius:10px 10px 0 0; border-bottom:none;">
    <form method="GET" action="{{ route('admin.orders') }}" id="filter-form">
        <div class="cc-search">
            <span class="cc-search-icon">⌕</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by order ID, renter or product…"
                   oninput="document.getElementById('filter-form').submit()">
        </div>
        @php $activeFilter = request('filter', 'all'); @endphp
        <div class="cc-filter-row" style="margin-bottom:0;">
            @foreach(['all'=>'All ('.$counts['all'].')','pending'=>'Pending ('.$counts['pending'].')','active'=>'Active ('.$counts['active'].')','disputed'=>'Disputed ('.$counts['disputed'].')','completed'=>'Completed ('.$counts['completed'].')'] as $key=>$label)
                <a href="{{ route('admin.orders', array_merge(request()->only('search'), ['filter'=>$key])) }}"
                   class="cc-filter-btn {{ $activeFilter===$key?'active':'' }}">{{ $label }}</a>
            @endforeach
        </div>
    </form>
</div>

<div class="cc-card" style="border-radius:0 0 10px 10px;">
    <table class="cc-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Renter</th>
                <th>Dates</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            @php
                $statusColor = match($order->status) {
                    'completed' => 'green', 'active' => 'teal',
                    'disputed'  => 'red',   'cancelled' => 'red',
                    'confirmed' => 'green', default => 'amber',
                };
            @endphp
            <tr>
                <td style="font-weight:500;">#{{ $order->id }}</td>
                <td>
                    <div style="font-weight:500;">{{ $order->product->name ?? '—' }}</div>
                </td>
                <td>{{ $order->borrower->name ?? '—' }}</td>
                <td style="color:rgba(0,63,95,0.55); font-size:10px;">
                    {{ $order->start_date->format('d M') }} → {{ $order->end_date->format('d M Y') }}<br>
                    <span style="color:rgba(0,63,95,0.35);">{{ $order->days }} days</span>
                </td>
                <td style="font-weight:500;">SGD {{ number_format($order->total, 0) }}</td>
                <td><span class="cc-pill {{ $statusColor }}">{{ ucfirst($order->status) }}</span></td>
                <td>
                    <div class="cc-btn-wrap">
                        <a href="{{ route('admin.orders.show', $order) }}" class="cc-btn secondary">View</a>
                        @if($order->status === 'disputed')
                            <form method="POST" action="{{ route('admin.orders.resolve', $order) }}" style="display:inline;"
                                  data-confirm="Mark order #{{ $order->id }} as completed? This will close the dispute." data-confirm-ok="Resolve" data-confirm-danger="false">
                                @csrf @method('PATCH')
                                <button class="cc-btn primary" type="submit">Resolve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.orders.refund', $order) }}" style="display:inline;"
                                  data-confirm="Issue a full refund for order #{{ $order->id }}? Funds will be returned to the renter." data-confirm-ok="Refund">
                                @csrf @method('PATCH')
                                <button class="cc-btn danger" type="submit">Refund</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:32px;color:rgba(0,63,95,0.3);">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
    <div style="padding:12px 16px;border-top:0.5px solid rgba(0,63,95,0.06);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:11px;color:rgba(0,63,95,0.4);">Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}</span>
        <div style="display:flex;gap:4px;">
            @if($orders->onFirstPage()) <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">← Prev</span>
            @else <a href="{{ $orders->previousPageUrl() }}" class="cc-btn secondary">← Prev</a> @endif
            @if($orders->hasMorePages()) <a href="{{ $orders->nextPageUrl() }}" class="cc-btn secondary">Next →</a>
            @else <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">Next →</span> @endif
        </div>
    </div>
    @endif
</div>
@endsection
