@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

{{-- PAGE HEADER --}}
<div class="cc-page-header">
    <h1>Dashboard</h1>
    <p>Culture Closet platform overview — {{ now()->format('l, j F Y') }}</p>
</div>

{{-- URGENCY STRIP --}}
<div class="cc-urgency">
    <div class="cc-urgency-title">Needs Attention</div>

    @if(($stats['pending_products'] ?? 0) > 0)
    <div class="cc-urgency-item amber">
        <div>
            <div class="cc-urgency-text">{{ $stats['pending_products'] }} products awaiting approval</div>
            <div class="cc-urgency-sub">Submitted in the last 24 hours</div>
        </div>
        <a href="{{ route('admin.products') }}" class="cc-urgency-btn">Review</a>
    </div>
    @endif

    @if(($stats['pending_payouts'] ?? 0) > 0)
    <div class="cc-urgency-item red">
        <div>
            <div class="cc-urgency-text">{{ $stats['pending_payouts'] }} payout requests pending</div>
            <div class="cc-urgency-sub">Oldest request 2 days ago</div>
        </div>
        <a href="{{ route('admin.transactions') }}" class="cc-urgency-btn">Process</a>
    </div>
    @endif

    @if(($stats['open_reports'] ?? 0) > 0)
    <div class="cc-urgency-item blue">
        <div>
            <div class="cc-urgency-text">{{ $stats['open_reports'] }} open user reports</div>
            <div class="cc-urgency-sub">Trust &amp; safety review required</div>
        </div>
        <a href="{{ route('admin.reports') }}" class="cc-urgency-btn">Review</a>
    </div>
    @endif

    @if(($stats['cleaning_queue'] ?? 0) > 0)
    <div class="cc-urgency-item amber">
        <div>
            <div class="cc-urgency-text">{{ $stats['cleaning_queue'] }} items in cleaning queue</div>
            <div class="cc-urgency-sub">Returned rentals awaiting processing</div>
        </div>
        <a href="{{ route('admin.cleaning') }}" class="cc-urgency-btn">View</a>
    </div>
    @endif
</div>

{{-- KPI STATS --}}
<div class="cc-stats-grid">
    <div class="cc-stat teal">
        <div class="cc-stat-label">Total GMV</div>
        <div class="cc-stat-val">SGD {{ number_format($stats['gmv'] ?? 0) }}</div>
        <div class="cc-stat-trend trend-up">↑ {{ $stats['gmv_growth'] ?? '0' }}% this month</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Active Users</div>
        <div class="cc-stat-val">{{ number_format($stats['active_users'] ?? 0) }}</div>
        <div class="cc-stat-trend trend-up">↑ {{ $stats['user_growth'] ?? '0' }}% vs last month</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Active Listings</div>
        <div class="cc-stat-val">{{ number_format($stats['active_listings'] ?? 0) }}</div>
        <div class="cc-stat-trend trend-neutral">{{ $stats['listings_pending'] ?? 0 }} pending review</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Orders This Month</div>
        <div class="cc-stat-val">{{ number_format($stats['monthly_orders'] ?? 0) }}</div>
        <div class="cc-stat-trend trend-up">↑ {{ $stats['order_growth'] ?? '0' }}% vs last month</div>
    </div>
</div>

<div class="cc-stats-grid-3">
    <div class="cc-stat">
        <div class="cc-stat-label">Platform Revenue</div>
        <div class="cc-stat-val">SGD {{ number_format($stats['revenue'] ?? 0) }}</div>
        <div class="cc-stat-trend trend-up">Commission + fees</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Coins in Circulation</div>
        <div class="cc-stat-val">{{ number_format($stats['coins_total'] ?? 0) }}</div>
        <div class="cc-stat-trend trend-neutral">Across all users</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Avg. Rental Duration</div>
        <div class="cc-stat-val">{{ $stats['avg_rental_days'] ?? '—' }} days</div>
        <div class="cc-stat-trend trend-neutral">Rolling 30 days</div>
    </div>
</div>

{{-- MAIN GRID --}}
<div class="cc-grid-2">

    {{-- RECENT ORDERS --}}
    <div class="cc-card">
        <div style="padding:14px 16px 10px;">
            <div class="cc-section-head">
                <span class="cc-section-title">Recent Orders</span>
                <a href="{{ route('admin.orders') }}" class="cc-btn secondary">View all</a>
            </div>
        </div>
        <table class="cc-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Renter</th>
                    <th>Item</th>
                    <th>Value</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders ?? [] as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->renter->name ?? '—' }}</td>
                    <td>{{ $order->product->name ?? '—' }}</td>
                    <td>SGD {{ number_format($order->total, 0) }}</td>
                    <td>
                        @php
                            $statusMap = [
                                'active'    => 'green',
                                'pending'   => 'amber',
                                'completed' => 'teal',
                                'cancelled' => 'red',
                                'disputed'  => 'red',
                            ];
                            $color = $statusMap[$order->status] ?? 'teal';
                        @endphp
                        <span class="cc-pill {{ $color }}">{{ ucfirst($order->status) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:rgba(0,63,95,0.3); padding:20px;">
                        No orders yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- RIGHT COLUMN --}}
    <div>
        {{-- PENDING PRODUCTS --}}
        <div class="cc-card-pad">
            <div class="cc-section-head">
                <span class="cc-section-title">Pending Approvals</span>
                <a href="{{ route('admin.products') }}" class="cc-btn secondary">View all</a>
            </div>
            @forelse($pendingProducts ?? [] as $product)
            <div class="cc-mini-row">
                <div>
                    <div class="cc-mini-label">{{ $product->name }}</div>
                    <div class="cc-mini-sub">by {{ $product->owner->name ?? '—' }} · {{ $product->created_at->diffForHumans() }}</div>
                </div>
                <a href="{{ route('admin.products.show', $product) }}" class="cc-mini-val">Review</a>
            </div>
            @empty
            <div style="font-size:11px; color:rgba(0,63,95,0.3); padding:8px 0;">All caught up</div>
            @endforelse
        </div>

        {{-- NEW USERS --}}
        <div class="cc-card-pad">
            <div class="cc-section-head">
                <span class="cc-section-title">New Users</span>
                <a href="{{ route('admin.users') }}" class="cc-btn secondary">View all</a>
            </div>
            @forelse($newUsers ?? [] as $user)
            <div class="cc-mini-row">
                <div>
                    <div class="cc-mini-label">{{ $user->name }}</div>
                    <div class="cc-mini-sub">{{ $user->email }} · {{ $user->created_at->diffForHumans() }}</div>
                </div>
                @if(!$user->email_verified_at)
                    <span class="cc-pill amber">Unverified</span>
                @else
                    <span class="cc-pill green">Verified</span>
                @endif
            </div>
            @empty
            <div style="font-size:11px; color:rgba(0,63,95,0.3); padding:8px 0;">No new users today</div>
            @endforelse
        </div>
    </div>

</div>

{{-- BOTTOM ROW --}}
<div class="cc-grid-2-equal">

    {{-- TOP CATEGORIES --}}
    <div class="cc-card-pad">
        <div class="cc-section-head">
            <span class="cc-section-title">Top Categories</span>
            <a href="{{ route('admin.categories') }}" class="cc-btn secondary">Manage</a>
        </div>
        @forelse($topCategories ?? [] as $cat)
        <div class="cc-mini-row">
            <div>
                <div class="cc-mini-label">{{ $cat->name }}</div>
                <div class="cc-mini-sub">{{ $cat->listings_count ?? 0 }} listings</div>
            </div>
            <span class="cc-pill teal">SGD {{ number_format($cat->gmv ?? 0) }}</span>
        </div>
        @empty
        <div style="font-size:11px; color:rgba(0,63,95,0.3); padding:8px 0;">No data yet</div>
        @endforelse
    </div>

    {{-- RECENT REPORTS --}}
    <div class="cc-card-pad">
        <div class="cc-section-head">
            <span class="cc-section-title">Open Reports</span>
            <a href="{{ route('admin.reports') }}" class="cc-btn secondary">View all</a>
        </div>
        @forelse($openReports ?? [] as $report)
        <div class="cc-mini-row">
            <div>
                <div class="cc-mini-label">{{ $report->reason }}</div>
                <div class="cc-mini-sub">Against {{ $report->reported->name ?? '—' }} · {{ $report->created_at->diffForHumans() }}</div>
            </div>
            <span class="cc-pill red">Open</span>
        </div>
        @empty
        <div style="font-size:11px; color:rgba(0,63,95,0.3); padding:8px 0;">No open reports</div>
        @endforelse
    </div>

</div>

@endsection
