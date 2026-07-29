@extends('layouts.admin')
@section('title', 'Categories')

@section('content')
<div class="cc-page-header">
    <h1>Categories</h1>
    <p>Manage the product catalogue structure</p>
</div>

<div class="cc-grid-2">
    {{-- CREATE FORM --}}
    <div>
        <div class="cc-card-pad">
            <div class="cc-section-title" style="margin-bottom:14px;">Add Category</div>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div style="margin-bottom:10px;">
                    <div class="cc-stat-label" style="margin-bottom:4px;">Name *</div>
                    <input type="text" name="name" placeholder="e.g. Evening Gowns" required
                           value="{{ old('name') }}"
                           style="width:100%;padding:8px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                    @error('name') <div style="font-size:11px;color:#c0392b;margin-top:3px;">{{ $message }}</div> @enderror
                </div>
                <div style="margin-bottom:10px;">
                    <div class="cc-stat-label" style="margin-bottom:4px;">Icon (emoji)</div>
                    <input type="text" name="icon" placeholder="👗" maxlength="4" value="{{ old('icon') }}"
                           style="width:100%;padding:8px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
                <div style="margin-bottom:14px;">
                    <div class="cc-stat-label" style="margin-bottom:4px;">Description</div>
                    <input type="text" name="description" placeholder="Short description…" value="{{ old('description') }}"
                           style="width:100%;padding:8px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
                <button type="submit" class="cc-btn primary" style="width:100%;padding:9px;font-size:12px;">+ Add Category</button>
            </form>
        </div>
    </div>

    {{-- CATEGORY LIST --}}
    <div>
        <div class="cc-card">
            <table class="cc-table">
                <thead>
                    <tr><th>Category</th><th>Listings</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                @if($cat->icon) <span style="font-size:16px;">{{ $cat->icon }}</span> @endif
                                <div>
                                    <div style="font-weight:500;">{{ $cat->name }}</div>
                                    @if($cat->description) <div style="font-size:10px;color:rgba(0,63,95,0.4);">{{ $cat->description }}</div> @endif
                                </div>
                            </div>
                        </td>
                        <td style="font-weight:500;">{{ $cat->product_count }}</td>
                        <td><span class="cc-pill {{ $cat->is_active?'green':'red' }}">{{ $cat->is_active?'Active':'Hidden' }}</span></td>
                        <td>
                            <div class="cc-btn-wrap">
                                <form method="POST" action="{{ route('admin.categories.update', $cat) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button class="cc-btn {{ $cat->is_active?'secondary':'primary' }}" type="submit">
                                        {{ $cat->is_active ? 'Hide' : 'Show' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" style="display:inline;"
                                      onsubmit="return confirm('Delete {{ $cat->name }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button class="cc-btn danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:32px;color:rgba(0,63,95,0.3);">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
