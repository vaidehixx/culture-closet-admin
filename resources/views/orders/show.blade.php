@extends('layouts.admin')
@section('title', 'Order #'.$order->id)

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.orders') }}" class="cc-btn secondary" style="margin-bottom:10px;display:inline-block;">← Back to Orders</a>
    <div class="cc-page-header" style="margin-bottom:0;">
        <h1>Order #{{ $order->id }}</h1>
        <p>Placed {{ $order->created_at->format('d M Y, H:i') }} · {{ $order->created_at->diffForHumans() }}</p>
    </div>
</div>

<div class="cc-grid-2">
    <div>
        <div class="cc-card-pad" style="margin-bottom:14px;">
            <div class="cc-section-title" style="margin-bottom:12px;">Rental Details</div>
            @php $statusColor = match($order->status) { 'completed'=>'green','active'=>'teal','disputed'=>'red','cancelled'=>'red','confirmed'=>'green',default=>'amber' }; @endphp
            <div class="cc-user-stat-row"><span class="cc-user-stat-label">Status</span><span class="cc-pill {{ $statusColor }}">{{ ucfirst($order->status) }}</span></div>
            <div class="cc-user-stat-row"><span class="cc-user-stat-label">Product</span><span class="cc-user-stat-val">{{ $order->product->name ?? '—' }}</span></div>
            <div class="cc-user-stat-row"><span class="cc-user-stat-label">Renter</span><span class="cc-user-stat-val">{{ $order->borrower->name ?? '—' }}</span></div>
            <div class="cc-user-stat-row"><span class="cc-user-stat-label">Owner</span><span class="cc-user-stat-val">{{ $order->lender->name ?? '—' }}</span></div>
            <div class="cc-user-stat-row"><span class="cc-user-stat-label">Start Date</span><span class="cc-user-stat-val">{{ $order->start_date->format('d M Y') }}</span></div>
            <div class="cc-user-stat-row"><span class="cc-user-stat-label">End Date</span><span class="cc-user-stat-val">{{ $order->end_date->format('d M Y') }}</span></div>
            <div class="cc-user-stat-row"><span class="cc-user-stat-label">Duration</span><span class="cc-user-stat-val">{{ $order->days }} days</span></div>
            <div class="cc-user-stat-row"><span class="cc-user-stat-label">Price/Day</span><span class="cc-user-stat-val">SGD {{ number_format($order->price_per_day, 0) }}</span></div>
            <div class="cc-user-stat-row"><span class="cc-user-stat-label">Subtotal</span><span class="cc-user-stat-val">SGD {{ number_format($order->subtotal, 0) }}</span></div>
            @if($order->discount > 0)
            <div class="cc-user-stat-row"><span class="cc-user-stat-label">Discount ({{ $order->promo_code }})</span><span class="cc-user-stat-val" style="color:#c0392b;">-SGD {{ number_format($order->discount, 0) }}</span></div>
            @endif
            <div class="cc-user-stat-row"><span class="cc-user-stat-label">Platform Fee</span><span class="cc-user-stat-val">SGD {{ number_format($order->platform_fee, 0) }}</span></div>
            <div class="cc-user-stat-row" style="border-top:0.5px solid rgba(0,63,95,0.1);margin-top:4px;padding-top:8px;">
                <span class="cc-user-stat-label" style="font-weight:600;">Total</span>
                <span class="cc-user-stat-val" style="font-size:14px;">SGD {{ number_format($order->total, 0) }}</span>
            </div>
        </div>

        @if($order->reviews->count())
        <div class="cc-card-pad">
            <div class="cc-section-title" style="margin-bottom:12px;">Reviews</div>
            @foreach($order->reviews as $review)
            <div class="cc-mini-row">
                <div>
                    <div class="cc-mini-label">{{ $review->reviewer->name ?? '—' }} → {{ $review->reviewee->name ?? '—' }}</div>
                    <div class="cc-mini-sub">{{ $review->body }}</div>
                </div>
                <span class="cc-pill {{ $review->rating >= 4 ? 'green' : ($review->rating >= 3 ? 'amber' : 'red') }}">
                    {{ $review->rating }}/5
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div>
        <div class="cc-card-pad" style="margin-bottom:14px;">
            <div class="cc-section-title" style="margin-bottom:12px;">Admin Actions</div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                @if($order->status === 'disputed')
                <form method="POST" action="{{ route('admin.orders.resolve', $order) }}"
                      data-confirm="Mark order #{{ $order->id }} as completed? This will close the dispute." data-confirm-ok="Mark Completed" data-confirm-danger="false">
                    @csrf @method('PATCH')
                    <button type="submit" class="cc-btn primary" style="width:100%;padding:8px;font-size:12px;">✓ Mark Completed</button>
                </form>
                <form method="POST" action="{{ route('admin.orders.refund', $order) }}"
                      data-confirm="Issue a full refund for order #{{ $order->id }} and cancel it? This will transfer funds back to the renter." data-confirm-ok="Issue Refund">
                    @csrf @method('PATCH')
                    <button type="submit" class="cc-btn danger" style="width:100%;padding:8px;font-size:12px;">↩ Issue Refund & Cancel</button>
                </form>
                @else
                <div class="cc-info-box">Order status is <span>{{ $order->status }}</span>. No actions available.</div>
                @endif
            </div>
        </div>

        <div class="cc-card-pad">
            <div class="cc-section-title" style="margin-bottom:12px;">Transactions</div>
            @forelse($order->transactions as $tx)
            <div class="cc-mini-row">
                <div>
                    <div class="cc-mini-label">{{ ucfirst(str_replace('_',' ',$tx->type)) }}</div>
                    <div class="cc-mini-sub">{{ $tx->reference ?? 'ref-'.$tx->id }}</div>
                </div>
                <span class="cc-pill {{ $tx->status==='completed'?'green':($tx->status==='failed'?'red':'amber') }}">
                    SGD {{ number_format($tx->amount,0) }}
                </span>
            </div>
            @empty
            <div style="font-size:11px;color:rgba(0,63,95,0.3);padding:8px 0;">No transactions recorded.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
