@extends('layouts.admin')
@section('title', 'Analytics')

@section('content')
<div class="cc-page-header">
    <h1>Analytics</h1>
    <p>Platform performance overview</p>
</div>

<div class="cc-stats-grid" style="margin-bottom:18px;">
    <div class="cc-stat teal">
        <div class="cc-stat-label">Total GMV</div>
        <div class="cc-stat-val">SGD {{ number_format($stats['total_gmv'], 0) }}</div>
        <div class="cc-stat-trend trend-up">All completed rentals</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Platform Revenue</div>
        <div class="cc-stat-val">SGD {{ number_format($stats['platform_rev'], 0) }}</div>
        <div class="cc-stat-trend trend-up">Net fees</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Total Orders</div>
        <div class="cc-stat-val">{{ number_format($stats['total_orders']) }}</div>
        <div class="cc-stat-trend trend-neutral">All time</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Avg Order Value</div>
        <div class="cc-stat-val">SGD {{ number_format($stats['avg_order'], 0) }}</div>
        <div class="cc-stat-trend trend-neutral">Per rental</div>
    </div>
</div>

<div class="cc-stats-grid-3" style="margin-bottom:18px;">
    <div class="cc-stat">
        <div class="cc-stat-label">Registered Users</div>
        <div class="cc-stat-val">{{ number_format($stats['total_users']) }}</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Active Listings</div>
        <div class="cc-stat-val">{{ number_format($stats['total_listings']) }}</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Categories</div>
        <div class="cc-stat-val">{{ $categoryBreakdown->count() }}</div>
    </div>
</div>

<div class="cc-grid-2">
    {{-- GMV CHART --}}
    <div class="cc-card-pad">
        <div class="cc-section-head">
            <span class="cc-section-title">GMV — Last 6 Months</span>
        </div>
        <div class="cc-chart-wrap">
            <canvas id="gmvChart"></canvas>
        </div>
    </div>

    {{-- USER SIGNUPS CHART --}}
    <div class="cc-card-pad">
        <div class="cc-section-head">
            <span class="cc-section-title">New Users — Last 6 Months</span>
        </div>
        <div class="cc-chart-wrap">
            <canvas id="usersChart"></canvas>
        </div>
    </div>
</div>

{{-- CATEGORY BREAKDOWN --}}
<div class="cc-card-pad">
    <div class="cc-section-title" style="margin-bottom:14px;">Listings by Category</div>
    @if($categoryBreakdown->isEmpty())
        <div style="font-size:11px;color:rgba(0,63,95,0.3);">No approved listings yet.</div>
    @else
    @php $maxCount = $categoryBreakdown->max('count'); @endphp
    @foreach($categoryBreakdown as $cat)
    <div style="margin-bottom:10px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
            <span style="font-size:12px;font-weight:500;color:#003F5F;">{{ $cat->category }}</span>
            <span style="font-size:11px;color:rgba(0,63,95,0.5);">{{ $cat->count }} listing{{ $cat->count>1?'s':'' }}</span>
        </div>
        <div style="height:6px;background:rgba(0,63,95,0.07);border-radius:3px;overflow:hidden;">
            <div style="height:100%;width:{{ $maxCount > 0 ? round(($cat->count/$maxCount)*100) : 0 }}%;background:#003F5F;border-radius:3px;transition:width 0.3s;"></div>
        </div>
    </div>
    @endforeach
    @endif
</div>

@endsection

@push('scripts')
<script>
const teal = '#003F5F';
const gold = '#FFC857';
const chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: {
        x: { grid: { display: false }, ticks: { font: { size: 10, family: "'DM Sans'" }, color: 'rgba(0,63,95,0.4)' } },
        y: { grid: { color: 'rgba(0,63,95,0.06)' }, ticks: { font: { size: 10, family: "'DM Sans'" }, color: 'rgba(0,63,95,0.4)' } }
    }
};

new Chart(document.getElementById('gmvChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($monthlyGmv->pluck('label')) !!},
        datasets: [{ data: {!! json_encode($monthlyGmv->pluck('amount')) !!}, backgroundColor: teal, borderRadius: 5 }]
    },
    options: chartDefaults
});

new Chart(document.getElementById('usersChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyUsers->pluck('label')) !!},
        datasets: [{
            data: {!! json_encode($monthlyUsers->pluck('count')) !!},
            borderColor: gold, backgroundColor: 'rgba(255,200,87,0.1)',
            tension: 0.4, fill: true, pointBackgroundColor: gold, pointRadius: 4
        }]
    },
    options: chartDefaults
});
</script>
@endpush
