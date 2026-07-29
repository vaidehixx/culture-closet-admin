@extends('layouts.admin')
@section('title', 'Reports')

@section('content')
<div class="cc-page-header">
    <h1>Reports</h1>
    <p>User-submitted trust &amp; safety flags</p>
</div>

<div class="cc-stats-grid" style="margin-bottom:18px;">
    <div class="cc-stat teal"><div class="cc-stat-label">Total Reports</div><div class="cc-stat-val">{{ $counts['all'] }}</div></div>
    <div class="cc-stat"><div class="cc-stat-label">Open</div><div class="cc-stat-val">{{ $counts['open'] }}</div><div class="cc-stat-trend {{ $counts['open']>0?'trend-down':'trend-up' }}">{{ $counts['open']>0?'Needs review':'All clear' }}</div></div>
    <div class="cc-stat"><div class="cc-stat-label">Actioned</div><div class="cc-stat-val">{{ $counts['actioned'] }}</div><div class="cc-stat-trend trend-neutral">Resolved with action</div></div>
    <div class="cc-stat"><div class="cc-stat-label">Dismissed</div><div class="cc-stat-val">{{ $counts['dismissed'] }}</div><div class="cc-stat-trend trend-neutral">No action needed</div></div>
</div>

<div class="cc-card-pad" style="margin-bottom:0;border-radius:10px 10px 0 0;border-bottom:none;">
    <form method="GET" action="{{ route('admin.reports') }}" id="filter-form">
        <div class="cc-search">
            <span class="cc-search-icon">⌕</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by reported user or reason…"
                   oninput="document.getElementById('filter-form').submit()">
        </div>
        @php $activeFilter = request('filter','open'); @endphp
        <div class="cc-filter-row" style="margin-bottom:0;">
            @foreach(['open'=>'Open ('.$counts['open'].')','all'=>'All ('.$counts['all'].')','reviewed'=>'Reviewed ('.$counts['reviewed'].')','actioned'=>'Actioned ('.$counts['actioned'].')','dismissed'=>'Dismissed ('.$counts['dismissed'].')'] as $key=>$label)
                <a href="{{ route('admin.reports',array_merge(request()->only('search'),['filter'=>$key])) }}"
                   class="cc-filter-btn {{ $activeFilter===$key?'active':'' }}">{{ $label }}</a>
            @endforeach
        </div>
    </form>
</div>

<div class="cc-card" style="border-radius:0 0 10px 10px;">
    @forelse($reports as $report)
    <div class="cc-mod-item">
        <div class="cc-mod-head">
            <div>
                <div style="font-size:12px;font-weight:500;color:#003F5F;margin-bottom:2px;">
                    {{ $report->reason }}
                </div>
                <div style="font-size:10px;color:rgba(0,63,95,0.45);">
                    Reported by <strong>{{ $report->reporter->name ?? '—' }}</strong>
                    against <strong>{{ $report->reported->name ?? '—' }}</strong>
                    @if($report->product) · re: {{ $report->product->name }} @endif
                    · {{ $report->created_at->diffForHumans() }}
                </div>
                @if($report->details)
                <div style="font-size:11px;color:rgba(0,63,95,0.6);margin-top:5px;">{{ $report->details }}</div>
                @endif
            </div>
            <span class="cc-pill {{ match($report->status){ 'open'=>'amber','actioned'=>'green','dismissed'=>'teal',default=>'teal'} }}">
                {{ ucfirst($report->status) }}
            </span>
        </div>

        @if($report->status === 'open')
        <div class="cc-mod-actions">
            <form method="POST" action="{{ route('admin.reports.dismiss', $report) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button class="cc-btn secondary" type="submit">Dismiss</button>
            </form>
            <form method="POST" action="{{ route('admin.reports.action', $report) }}" style="display:inline;display:flex;gap:6px;align-items:center;">
                @csrf @method('PATCH')
                <input type="text" name="notes" placeholder="Action notes (optional)"
                       style="padding:4px 9px;border:0.5px solid rgba(0,63,95,0.15);border-radius:6px;font-size:11px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;width:220px;">
                <label style="display:flex;align-items:center;gap:4px;font-size:11px;color:rgba(0,63,95,0.6);cursor:pointer;">
                    <input type="checkbox" name="suspend_user" value="1"> Suspend user
                </label>
                <button class="cc-btn danger" type="submit">Take Action</button>
            </form>
        </div>
        @elseif($report->admin_notes)
        <div style="font-size:11px;color:rgba(0,63,95,0.5);margin-top:6px;">Admin notes: {{ $report->admin_notes }}</div>
        @endif
    </div>
    @empty
    <div style="padding:32px;text-align:center;color:rgba(0,63,95,0.3);">
        No reports {{ request('filter','open') === 'open' ? 'open' : 'found' }}.
    </div>
    @endforelse

    @if($reports->hasPages())
    <div style="padding:12px 16px;border-top:0.5px solid rgba(0,63,95,0.06);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:11px;color:rgba(0,63,95,0.4);">Showing {{ $reports->firstItem() }}–{{ $reports->lastItem() }} of {{ $reports->total() }}</span>
        <div style="display:flex;gap:4px;">
            @if($reports->onFirstPage()) <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">← Prev</span>
            @else <a href="{{ $reports->previousPageUrl() }}" class="cc-btn secondary">← Prev</a> @endif
            @if($reports->hasMorePages()) <a href="{{ $reports->nextPageUrl() }}" class="cc-btn secondary">Next →</a>
            @else <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">Next →</span> @endif
        </div>
    </div>
    @endif
</div>
@endsection
