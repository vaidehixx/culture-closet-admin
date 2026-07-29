@extends('layouts.admin')

@section('title', $product->name)

@section('content')

<div style="margin-bottom:16px;">
    <a href="{{ route('admin.products') }}" class="cc-btn secondary" style="margin-bottom:10px; display:inline-block;">← Back to Products</a>
    <div class="cc-page-header" style="margin-bottom:0;">
        <h1>
            {{ $product->name }}
            @if($product->is_featured) <span style="color:#FFC857; font-size:16px;">★</span> @endif
        </h1>
        <p>Submitted {{ $product->created_at->format('d M Y') }} · {{ $product->created_at->diffForHumans() }}</p>
    </div>
</div>

<div class="cc-grid-2">

    {{-- LEFT: DETAILS --}}
    <div>
        <div class="cc-card-pad" style="margin-bottom:14px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px; padding-bottom:14px; border-bottom:0.5px solid rgba(0,63,95,0.07);">
                <div>
                    <div style="font-family:'Playfair Display',serif; font-size:16px; color:#003F5F; margin-bottom:4px;">{{ $product->name }}</div>
                    @if($product->brand)
                        <div style="font-size:11px; color:rgba(0,63,95,0.45);">{{ $product->brand }}</div>
                    @endif
                </div>
                @php
                    $statusColor = match($product->status) { 'approved' => 'green', 'rejected' => 'red', default => 'amber' };
                @endphp
                <span class="cc-pill {{ $statusColor }}" style="font-size:11px; padding:4px 12px;">{{ ucfirst($product->status) }}</span>
            </div>

            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Category</span>
                <span class="cc-user-stat-val">{{ $product->category }}</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Size</span>
                <span class="cc-user-stat-val">{{ $product->size ?? '—' }}</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Condition</span>
                <span class="cc-user-stat-val">{{ ucfirst($product->condition) }}</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Price per Day</span>
                <span class="cc-user-stat-val">SGD {{ number_format($product->price_per_day, 0) }}</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Deposit</span>
                <span class="cc-user-stat-val">SGD {{ number_format($product->deposit, 0) }}</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Featured</span>
                <span class="cc-user-stat-val">{{ $product->is_featured ? 'Yes ★' : 'No' }}</span>
            </div>

            @if($product->description)
            <div style="margin-top:12px; padding-top:12px; border-top:0.5px solid rgba(0,63,95,0.07);">
                <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.08em; color:rgba(0,63,95,0.4); margin-bottom:6px;">Description</div>
                <div style="font-size:12px; color:#003F5F; line-height:1.6;">{{ $product->description }}</div>
            </div>
            @endif

            @if($product->reject_reason)
            <div class="cc-urgency-item red" style="margin-top:12px; border-radius:8px; padding:10px 14px;">
                <div>
                    <div class="cc-urgency-text">Rejection reason</div>
                    <div class="cc-urgency-sub">{{ $product->reject_reason }}</div>
                </div>
            </div>
            @endif
        </div>

        {{-- OWNER --}}
        <div class="cc-card-pad">
            <div class="cc-section-title" style="margin-bottom:12px;">Listed By</div>
            <div style="display:flex; align-items:center; gap:10px;">
                <div class="cc-user-av-lg">{{ strtoupper(substr($product->owner->name ?? 'U', 0, 2)) }}</div>
                <div>
                    <div class="cc-user-name">{{ $product->owner->name ?? '—' }}</div>
                    <div class="cc-user-sub">{{ $product->owner->email ?? '—' }}</div>
                </div>
                @if($product->owner)
                    <a href="{{ route('admin.users.show', $product->owner) }}" class="cc-btn secondary" style="margin-left:auto;">View Profile</a>
                @endif
            </div>
        </div>
    </div>

    {{-- RIGHT: ACTIONS --}}
    <div>
        <div class="cc-card-pad" style="margin-bottom:14px;">
            <div class="cc-section-title" style="margin-bottom:12px;">Admin Actions</div>
            <div style="display:flex; flex-direction:column; gap:8px;">

                @if($product->status === 'pending')
                    <form method="POST" action="{{ route('admin.products.approve', $product) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="cc-btn primary" style="width:100%; padding:8px; font-size:12px;">
                            ✓ Approve Listing
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.products.reject', $product) }}" id="reject-form">
                        @csrf @method('PATCH')
                        <div style="margin-bottom:6px;">
                            <input type="text" name="reason" placeholder="Rejection reason (optional)"
                                   style="width:100%; padding:7px 10px; border:0.5px solid rgba(0,63,95,0.18); border-radius:7px; font-size:11px; font-family:'DM Sans',sans-serif; color:#003F5F; outline:none;">
                        </div>
                        <button type="submit" class="cc-btn danger" style="width:100%; padding:8px; font-size:12px;">
                            ✕ Reject Listing
                        </button>
                    </form>

                @elseif($product->status === 'approved')
                    <form method="POST" action="{{ route('admin.products.feature', $product) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="cc-btn {{ $product->is_featured ? 'secondary' : 'primary' }}" style="width:100%; padding:8px; font-size:12px;">
                            {{ $product->is_featured ? '★ Remove from Featured' : '★ Add to Featured' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.products.reject', $product) }}" id="reject-form">
                        @csrf @method('PATCH')
                        <div style="margin-bottom:6px;">
                            <input type="text" name="reason" placeholder="Rejection reason (optional)"
                                   style="width:100%; padding:7px 10px; border:0.5px solid rgba(0,63,95,0.18); border-radius:7px; font-size:11px; font-family:'DM Sans',sans-serif; color:#003F5F; outline:none;">
                        </div>
                        <button type="submit" class="cc-btn danger" style="width:100%; padding:8px; font-size:12px;">
                            ✕ Reject Listing
                        </button>
                    </form>

                @elseif($product->status === 'rejected')
                    <form method="POST" action="{{ route('admin.products.approve', $product) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="cc-btn primary" style="width:100%; padding:8px; font-size:12px;">
                            ↑ Re-approve Listing
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                      onsubmit="return confirm('Permanently delete this listing? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="cc-btn danger" style="width:100%; padding:8px; font-size:12px;">
                        ✕ Delete Listing
                    </button>
                </form>
            </div>
        </div>

        <div class="cc-card-pad">
            <div class="cc-section-title" style="margin-bottom:12px;">Listing Stats</div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Total Rentals</span>
                <span class="cc-user-stat-val">—</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Revenue Generated</span>
                <span class="cc-user-stat-val">—</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Avg. Rating</span>
                <span class="cc-user-stat-val">—</span>
            </div>
            <div class="cc-info-box" style="margin-top:10px;">
                Stats will populate once the <span>orders module</span> is connected.
            </div>
        </div>
    </div>

</div>

@endsection
