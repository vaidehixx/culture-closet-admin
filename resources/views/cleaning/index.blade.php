@extends('layouts.admin')
@section('title', 'Cleaning Queue')
@section('content')
<div class="cc-page-header">
    <h1>Cleaning Queue</h1>
    <p>Returned rentals awaiting inspection and cleaning</p>
</div>

<div class="cc-stats-grid" style="margin-bottom:18px;">
    <div class="cc-stat teal">
        <div class="cc-stat-label">All Items</div>
        <div class="cc-stat-val">{{ number_format($counts['all']) }}</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Pending</div>
        <div class="cc-stat-val">{{ number_format($counts['pending']) }}</div>
        <div class="cc-stat-trend {{ $counts['pending'] > 0 ? 'trend-down' : 'trend-up' }}">
            {{ $counts['pending'] > 0 ? 'Needs attention' : 'All clear' }}
        </div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">In Progress</div>
        <div class="cc-stat-val">{{ number_format($counts['in_progress']) }}</div>
        <div class="cc-stat-trend trend-neutral">Being cleaned</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Completed</div>
        <div class="cc-stat-val">{{ number_format($counts['completed']) }}</div>
        <div class="cc-stat-trend trend-up">Done</div>
    </div>
</div>

<div class="cc-card-pad" style="margin-bottom:0;border-radius:10px 10px 0 0;border-bottom:none;">
    <div class="cc-filter-row" style="margin-bottom:0;">
        @foreach(['all'=>'All ('.$counts['all'].')','pending'=>'Pending ('.$counts['pending'].')','in_progress'=>'In Progress ('.$counts['in_progress'].')','completed'=>'Completed ('.$counts['completed'].')'] as $key=>$label)
        <a href="{{ route('admin.cleaning', ['filter'=>$key]) }}"
           class="cc-filter-btn {{ $filter===$key?'active':'' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="cc-card" style="border-radius:0 0 10px 10px;">
    <table class="cc-table">
        <thead>
            <tr><th>#</th><th>Product</th><th>Renter</th><th>Status</th><th>Assigned To</th><th>Notes</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @forelse($items as $item)
        @php $statusColor = match($item->status) { 'completed'=>'green','in_progress'=>'teal',default=>'amber' }; @endphp
        <tr>
            <td style="font-weight:500;">#{{ $item->id }}</td>
            <td>{{ $item->product->name ?? '—' }}</td>
            <td>{{ $item->order->borrower->name ?? '—' }}</td>
            <td><span class="cc-pill {{ $statusColor }}">{{ ucfirst(str_replace('_',' ',$item->status)) }}</span></td>
            <td style="color:rgba(0,63,95,0.5);font-size:11px;">{{ $item->assigned_to ?? '—' }}</td>
            <td style="color:rgba(0,63,95,0.5);font-size:11px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item->notes ?? '—' }}</td>
            <td>
                <div class="cc-btn-wrap">
                    @if($item->status !== 'completed')
                    <form method="POST" action="{{ route('admin.cleaning.complete', $item) }}" style="display:inline;">
                        @csrf @method('PATCH')
                        <button class="cc-btn primary" type="submit" style="font-size:11px;padding:5px 10px;">✓ Done</button>
                    </form>
                    @endif
                    @if($item->status === 'pending')
                    <form method="POST" action="{{ route('admin.cleaning.assign', $item) }}" style="display:inline;display:flex;gap:4px;">
                        @csrf @method('PATCH')
                        <input type="text" name="assigned_to" placeholder="Assign to…"
                               style="padding:5px 8px;border:0.5px solid rgba(0,63,95,0.18);border-radius:6px;font-size:11px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;width:110px;">
                        <button class="cc-btn secondary" type="submit" style="font-size:11px;padding:5px 8px;">Assign</button>
                    </form>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:32px;color:rgba(0,63,95,0.3);">No items in this queue.</td></tr>
        @endforelse
        </tbody>
    </table>
    @if($items->hasPages())
    <div style="padding:12px 16px;border-top:0.5px solid rgba(0,63,95,0.06);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:11px;color:rgba(0,63,95,0.4);">Showing {{ $items->firstItem() }}–{{ $items->lastItem() }} of {{ $items->total() }}</span>
        <div style="display:flex;gap:4px;">
            @if($items->onFirstPage()) <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">← Prev</span>
            @else <a href="{{ $items->previousPageUrl() }}" class="cc-btn secondary">← Prev</a> @endif
            @if($items->hasMorePages()) <a href="{{ $items->nextPageUrl() }}" class="cc-btn secondary">Next →</a>
            @else <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">Next →</span> @endif
        </div>
    </div>
    @endif
</div>
@endsection
