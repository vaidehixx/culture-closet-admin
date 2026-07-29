@extends('layouts.admin')

@section('title', 'Products')

@section('content')

<div class="cc-page-header">
    <h1>Products</h1>
    <p>All listings submitted to the platform</p>
</div>

{{-- STAT STRIP --}}
<div class="cc-stats-grid" style="margin-bottom:18px;">
    <div class="cc-stat">
        <div class="cc-stat-label">Total Listings</div>
        <div class="cc-stat-val">{{ number_format($counts['all']) }}</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Pending Review</div>
        <div class="cc-stat-val">{{ number_format($counts['pending']) }}</div>
        <div class="cc-stat-trend {{ $counts['pending'] > 0 ? 'trend-down' : 'trend-neutral' }}">
            {{ $counts['pending'] > 0 ? 'Needs attention' : 'All reviewed' }}
        </div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Approved</div>
        <div class="cc-stat-val">{{ number_format($counts['approved']) }}</div>
        <div class="cc-stat-trend trend-up">Live on platform</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Featured</div>
        <div class="cc-stat-val">{{ number_format($counts['featured']) }}</div>
        <div class="cc-stat-trend trend-neutral">Boosted listings</div>
    </div>
</div>

{{-- SEARCH + FILTERS --}}
<div class="cc-card-pad" style="margin-bottom:0; border-radius:10px 10px 0 0; border-bottom:none;">
    <form method="GET" action="{{ route('admin.products') }}" id="filter-form">
        <div class="cc-search">
            <span class="cc-search-icon">⌕</span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name, brand, category or owner…"
                oninput="document.getElementById('filter-form').submit()"
            >
            @if(request('search'))
                <a href="{{ route('admin.products', ['filter' => request('filter')]) }}" style="font-size:11px; color:rgba(0,63,95,0.4); text-decoration:none;">✕ Clear</a>
            @endif
        </div>

        @php $activeFilter = request('filter', 'all'); @endphp
        <div class="cc-filter-row" style="margin-bottom:0;">
            @foreach([
                'all'      => 'All ('.$counts['all'].')',
                'pending'  => 'Pending ('.$counts['pending'].')',
                'approved' => 'Approved ('.$counts['approved'].')',
                'rejected' => 'Rejected ('.$counts['rejected'].')',
                'featured' => '★ Featured ('.$counts['featured'].')',
            ] as $key => $label)
                <a href="{{ route('admin.products', array_merge(request()->only('search'), ['filter' => $key])) }}"
                   class="cc-filter-btn {{ $activeFilter === $key ? 'active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="cc-card" style="border-radius:0 0 10px 10px;">
    <table class="cc-table">
        <thead>
            <tr>
                <th>Listing</th>
                <th>Owner</th>
                <th>Category</th>
                <th>Price/day</th>
                <th>Submitted</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>
                    <div>
                        <div style="font-weight:500;">
                            {{ $product->name }}
                            @if($product->is_featured)
                                <span style="color:#FFC857; font-size:10px;">★</span>
                            @endif
                        </div>
                        @if($product->brand)
                            <div style="font-size:10px; color:rgba(0,63,95,0.4);">{{ $product->brand }}</div>
                        @endif
                    </div>
                </td>
                <td>
                    <div style="font-weight:500;">{{ $product->owner->name ?? '—' }}</div>
                </td>
                <td>
                    <span class="cc-pill teal">{{ $product->category }}</span>
                </td>
                <td style="font-weight:500;">SGD {{ number_format($product->price_per_day, 0) }}</td>
                <td style="color:rgba(0,63,95,0.5);">{{ $product->created_at->format('d M Y') }}</td>
                <td>
                    @php
                        $statusColor = match($product->status) {
                            'approved' => 'green',
                            'rejected' => 'red',
                            default    => 'amber',
                        };
                    @endphp
                    <span class="cc-pill {{ $statusColor }}">{{ ucfirst($product->status) }}</span>
                </td>
                <td>
                    <div class="cc-btn-wrap">
                        <a href="{{ route('admin.products.show', $product) }}" class="cc-btn secondary">View</a>

                        @if($product->status === 'pending')
                            <form method="POST" action="{{ route('admin.products.approve', $product) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button class="cc-btn primary" type="submit">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.products.reject', $product) }}" style="display:inline;"
                                  data-confirm="Reject '{{ $product->name }}'? The owner will be notified." data-confirm-ok="Reject">
                                @csrf @method('PATCH')
                                <button class="cc-btn danger" type="submit">Reject</button>
                            </form>
                        @elseif($product->status === 'approved')
                            <form method="POST" action="{{ route('admin.products.feature', $product) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button class="cc-btn {{ $product->is_featured ? 'secondary' : 'primary' }}" type="submit">
                                    {{ $product->is_featured ? 'Unfeature' : '★ Feature' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.products.reject', $product) }}" style="display:inline;"
                                  data-confirm="Reject '{{ $product->name }}'? The owner will be notified." data-confirm-ok="Reject">
                                @csrf @method('PATCH')
                                <button class="cc-btn danger" type="submit">Reject</button>
                            </form>
                        @elseif($product->status === 'rejected')
                            <form method="POST" action="{{ route('admin.products.approve', $product) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button class="cc-btn primary" type="submit">Re-approve</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;"
                              data-confirm="Permanently delete '{{ $product->name }}'? This cannot be undone." data-confirm-ok="Delete">
                            @csrf @method('DELETE')
                            <button class="cc-btn danger" type="submit">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:32px; color:rgba(0,63,95,0.3);">
                    No products found{{ request('search') ? ' for "'.request('search').'"' : '' }}.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    @if($products->hasPages())
    <div style="padding:12px 16px; border-top:0.5px solid rgba(0,63,95,0.06); display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:11px; color:rgba(0,63,95,0.4);">
            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}
        </span>
        <div style="display:flex; gap:4px;">
            @if($products->onFirstPage())
                <span class="cc-btn secondary" style="opacity:0.4; cursor:default;">← Prev</span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="cc-btn secondary">← Prev</a>
            @endif
            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="cc-btn secondary">Next →</a>
            @else
                <span class="cc-btn secondary" style="opacity:0.4; cursor:default;">Next →</span>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection
