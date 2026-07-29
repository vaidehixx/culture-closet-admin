@extends('layouts.admin')
@section('title', 'Promo Codes')

@section('content')
<div class="cc-page-header">
    <h1>Promo Codes</h1>
    <p>Manage discount codes for the platform</p>
</div>

<div class="cc-grid-2">

    {{-- LEFT: TABLE --}}
    <div style="grid-column: span 2;">

        <div class="cc-stats-grid-3" style="margin-bottom:18px;">
            <div class="cc-stat"><div class="cc-stat-label">Total Codes</div><div class="cc-stat-val">{{ $counts['all'] }}</div></div>
            <div class="cc-stat"><div class="cc-stat-label">Active</div><div class="cc-stat-val">{{ $counts['active'] }}</div><div class="cc-stat-trend trend-up">Available to use</div></div>
            <div class="cc-stat"><div class="cc-stat-label">Inactive</div><div class="cc-stat-val">{{ $counts['inactive'] }}</div><div class="cc-stat-trend trend-neutral">Disabled</div></div>
        </div>

        <div class="cc-card-pad" style="margin-bottom:16px;">
            <div class="cc-section-title" style="margin-bottom:14px;">Create New Code</div>
            <form method="POST" action="{{ route('admin.promocodes.store') }}" style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:10px;align-items:end;">
                @csrf
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">Code *</div>
                    <input type="text" name="code" placeholder="SUMMER25" required
                           style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;text-transform:uppercase;">
                </div>
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">Type *</div>
                    <select name="type" style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                        <option value="percent">Percent (%)</option>
                        <option value="fixed">Fixed (SGD)</option>
                    </select>
                </div>
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">Value *</div>
                    <input type="number" name="value" placeholder="20" min="0" step="0.01" required
                           style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">Max Uses</div>
                    <input type="number" name="max_uses" placeholder="Unlimited" min="1"
                           style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">Min Order (SGD)</div>
                    <input type="number" name="min_order" placeholder="0" min="0"
                           style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">Expires</div>
                    <input type="date" name="expires_at"
                           style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">Description</div>
                    <input type="text" name="description" placeholder="Summer campaign"
                           style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
                <div style="display:flex;align-items:flex-end;">
                    <button type="submit" class="cc-btn primary" style="padding:8px 18px;font-size:12px;">+ Create Code</button>
                </div>
            </form>
        </div>

        <div class="cc-card-pad" style="margin-bottom:0;border-radius:10px 10px 0 0;border-bottom:none;">
            <div class="cc-filter-row" style="margin-bottom:0;">
                @php $activeFilter = request('filter','all'); @endphp
                @foreach(['all'=>'All ('.$counts['all'].')','active'=>'Active ('.$counts['active'].')','inactive'=>'Inactive ('.$counts['inactive'].')'] as $key=>$label)
                    <a href="{{ route('admin.promocodes',['filter'=>$key]) }}" class="cc-filter-btn {{ $activeFilter===$key?'active':'' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>
        <div class="cc-card" style="border-radius:0 0 10px 10px;">
            <table class="cc-table">
                <thead>
                    <tr><th>Code</th><th>Type</th><th>Value</th><th>Used</th><th>Min Order</th><th>Expires</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($codes as $code)
                    <tr>
                        <td style="font-weight:600;letter-spacing:0.05em;">{{ $code->code }}</td>
                        <td><span class="cc-pill teal">{{ ucfirst($code->type) }}</span></td>
                        <td style="font-weight:500;">{{ $code->type==='percent' ? $code->value.'%' : 'SGD '.number_format($code->value,0) }}</td>
                        <td>{{ $code->used_count }}{{ $code->max_uses ? ' / '.$code->max_uses : '' }}</td>
                        <td>{{ $code->min_order > 0 ? 'SGD '.number_format($code->min_order,0) : '—' }}</td>
                        <td style="color:rgba(0,63,95,0.5);font-size:10px;">{{ $code->expires_at ? $code->expires_at->format('d M Y') : 'Never' }}</td>
                        <td>
                            @if($code->isExpired()) <span class="cc-pill red">Expired</span>
                            @elseif($code->isExhausted()) <span class="cc-pill amber">Exhausted</span>
                            @elseif($code->is_active) <span class="cc-pill green">Active</span>
                            @else <span class="cc-pill red">Inactive</span> @endif
                        </td>
                        <td>
                            <div class="cc-btn-wrap">
                                <form method="POST" action="{{ route('admin.promocodes.update', $code) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button class="cc-btn {{ $code->is_active ? 'danger' : 'primary' }}" type="submit">
                                        {{ $code->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.promocodes.destroy', $code) }}" style="display:inline;"
                                      onsubmit="return confirm('Delete code {{ $code->code }}?')">
                                    @csrf @method('DELETE')
                                    <button class="cc-btn danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center;padding:32px;color:rgba(0,63,95,0.3);">No promo codes yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
