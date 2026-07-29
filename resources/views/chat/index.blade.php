@extends('layouts.admin')
@section('title', 'Chat / Messages')
@section('content')
<div class="cc-page-header">
    <h1>Chat</h1>
    <p>User-to-user conversations on the platform</p>
</div>

<div class="cc-stats-grid-3" style="margin-bottom:18px;">
    <div class="cc-stat teal">
        <div class="cc-stat-label">Total Conversations</div>
        <div class="cc-stat-val">{{ number_format($stats['total']) }}</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Flagged Messages</div>
        <div class="cc-stat-val">{{ number_format($stats['flagged']) }}</div>
        <div class="cc-stat-trend {{ $stats['flagged'] > 0 ? 'trend-down' : 'trend-up' }}">
            {{ $stats['flagged'] > 0 ? 'Needs review' : 'All clear' }}
        </div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">New Today</div>
        <div class="cc-stat-val">{{ number_format($stats['today']) }}</div>
        <div class="cc-stat-trend trend-neutral">Conversations started</div>
    </div>
</div>

<div class="cc-card-pad" style="margin-bottom:0;border-radius:10px 10px 0 0;border-bottom:none;">
    <form method="GET" id="filter-form">
        <div class="cc-search">
            <span class="cc-search-icon">⌕</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by user name…"
                   oninput="document.getElementById('filter-form').submit()">
        </div>
        <div class="cc-filter-row" style="margin-bottom:0;">
            <a href="{{ route('admin.chat', ['filter'=>'all']) }}" class="cc-filter-btn {{ $filter==='all'?'active':'' }}">All</a>
            <a href="{{ route('admin.chat', ['filter'=>'flagged']) }}" class="cc-filter-btn {{ $filter==='flagged'?'active':'' }}">Flagged ({{ $stats['flagged'] }})</a>
        </div>
    </form>
</div>

<div class="cc-card" style="border-radius:0 0 10px 10px;">
    <table class="cc-table">
        <thead>
            <tr><th>#</th><th>Between</th><th>Related to</th><th>Last Message</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @forelse($conversations as $conv)
        <tr>
            <td style="font-weight:500;">#{{ $conv->id }}</td>
            <td>
                <div style="font-weight:500;font-size:12px;">{{ $conv->user1->name ?? '—' }}</div>
                <div style="font-size:10px;color:rgba(0,63,95,0.4);">↔ {{ $conv->user2->name ?? '—' }}</div>
            </td>
            <td style="font-size:11px;color:rgba(0,63,95,0.5);">{{ $conv->product->name ?? '—' }}</td>
            <td style="font-size:10px;color:rgba(0,63,95,0.4);">
                @if($conv->latestMessage)
                    <div style="font-size:11px;color:#003F5F;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $conv->latestMessage->body }}</div>
                    {{ $conv->latestMessage->created_at->diffForHumans() }}
                @else —
                @endif
            </td>
            <td>
                <a href="{{ route('admin.chat.show', $conv) }}" class="cc-btn secondary">View</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:32px;color:rgba(0,63,95,0.3);">No conversations found.</td></tr>
        @endforelse
        </tbody>
    </table>
    @if($conversations->hasPages())
    <div style="padding:12px 16px;border-top:0.5px solid rgba(0,63,95,0.06);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:11px;color:rgba(0,63,95,0.4);">Showing {{ $conversations->firstItem() }}–{{ $conversations->lastItem() }} of {{ $conversations->total() }}</span>
        <div style="display:flex;gap:4px;">
            @if($conversations->onFirstPage()) <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">← Prev</span>
            @else <a href="{{ $conversations->previousPageUrl() }}" class="cc-btn secondary">← Prev</a> @endif
            @if($conversations->hasMorePages()) <a href="{{ $conversations->nextPageUrl() }}" class="cc-btn secondary">Next →</a>
            @else <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">Next →</span> @endif
        </div>
    </div>
    @endif
</div>
@endsection
