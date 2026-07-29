@extends('layouts.admin')
@section('title', 'Reviews')

@section('content')
<div class="cc-page-header">
    <h1>Reviews</h1>
    <p>Community ratings and feedback</p>
</div>

<div class="cc-stats-grid" style="margin-bottom:18px;">
    <div class="cc-stat teal"><div class="cc-stat-label">Total Reviews</div><div class="cc-stat-val">{{ number_format($counts['all']) }}</div></div>
    <div class="cc-stat"><div class="cc-stat-label">Pending</div><div class="cc-stat-val">{{ $counts['pending'] }}</div><div class="cc-stat-trend {{ $counts['pending']>0?'trend-neutral':'trend-up' }}">Awaiting moderation</div></div>
    <div class="cc-stat"><div class="cc-stat-label">Approved</div><div class="cc-stat-val">{{ $counts['approved'] }}</div><div class="cc-stat-trend trend-up">Live</div></div>
    <div class="cc-stat"><div class="cc-stat-label">Low Ratings</div><div class="cc-stat-val">{{ $counts['low'] }}</div><div class="cc-stat-trend {{ $counts['low']>0?'trend-down':'trend-neutral' }}">1–2 stars</div></div>
</div>

<div class="cc-card-pad" style="margin-bottom:0;border-radius:10px 10px 0 0;border-bottom:none;">
    <form method="GET" action="{{ route('admin.reviews') }}" id="filter-form">
        <div class="cc-search">
            <span class="cc-search-icon">⌕</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search reviewer, product or review text…"
                   oninput="document.getElementById('filter-form').submit()">
        </div>
        @php $activeFilter = request('filter','all'); @endphp
        <div class="cc-filter-row" style="margin-bottom:0;">
            @foreach(['all'=>'All ('.$counts['all'].')','pending'=>'Pending ('.$counts['pending'].')','approved'=>'Approved ('.$counts['approved'].')','low'=>'Low Ratings ('.$counts['low'].')'] as $key=>$label)
                <a href="{{ route('admin.reviews',array_merge(request()->only('search'),['filter'=>$key])) }}"
                   class="cc-filter-btn {{ $activeFilter===$key?'active':'' }}">{{ $label }}</a>
            @endforeach
        </div>
    </form>
</div>

<div class="cc-card" style="border-radius:0 0 10px 10px;">
    <table class="cc-table">
        <thead>
            <tr><th>Reviewer</th><th>Product</th><th>Rating</th><th>Review</th><th>Date</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td>
                    <div style="font-weight:500;">{{ $review->reviewer->name ?? '—' }}</div>
                    <div style="font-size:10px;color:rgba(0,63,95,0.4);">→ {{ $review->reviewee->name ?? '—' }}</div>
                </td>
                <td style="font-size:11px;">{{ $review->product->name ?? '—' }}</td>
                <td>
                    <span class="cc-pill {{ $review->rating>=4?'green':($review->rating>=3?'amber':'red') }}">
                        {{ $review->rating }}/5 {{ str_repeat('★', $review->rating) }}
                    </span>
                </td>
                <td style="max-width:200px;font-size:11px;color:rgba(0,63,95,0.7);">
                    {{ $review->body ? Str::limit($review->body,80) : '—' }}
                </td>
                <td style="color:rgba(0,63,95,0.5);font-size:10px;">{{ $review->created_at->format('d M Y') }}</td>
                <td><span class="cc-pill {{ $review->status==='approved'?'green':($review->status==='rejected'?'red':'amber') }}">{{ ucfirst($review->status) }}</span></td>
                <td>
                    <div class="cc-btn-wrap">
                        @if($review->status !== 'approved')
                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button class="cc-btn primary" type="submit">Approve</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" style="display:inline;"
                              data-confirm="Remove this review from {{ $review->reviewer->name ?? 'this user' }}? It will be permanently deleted." data-confirm-ok="Remove">
                            @csrf @method('DELETE')
                            <button class="cc-btn danger" type="submit">Remove</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:32px;color:rgba(0,63,95,0.3);">No reviews found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
