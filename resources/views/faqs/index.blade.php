@extends('layouts.admin')
@section('title', 'FAQs')
@section('content')
<div class="cc-page-header">
    <h1>FAQs</h1>
    <p>Manage frequently asked questions</p>
</div>

<div class="cc-grid-2">
    {{-- Add FAQ Form --}}
    <div class="cc-card-pad" style="margin-bottom:14px;">
        <div class="cc-section-title" style="margin-bottom:14px;">Add New FAQ</div>
        <form method="POST" action="{{ route('admin.faqs.store') }}">
            @csrf
            <div style="margin-bottom:10px;">
                <div class="cc-stat-label" style="margin-bottom:4px;">Category *</div>
                <input type="text" name="category" required placeholder="e.g. General, Renting, Payments…"
                       style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
            </div>
            <div style="margin-bottom:10px;">
                <div class="cc-stat-label" style="margin-bottom:4px;">Question *</div>
                <input type="text" name="question" required placeholder="Type the FAQ question…"
                       style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
            </div>
            <div style="margin-bottom:10px;">
                <div class="cc-stat-label" style="margin-bottom:4px;">Answer *</div>
                <textarea name="answer" rows="4" required placeholder="Type the answer…"
                          style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;resize:vertical;"></textarea>
            </div>
            <div style="margin-bottom:12px;">
                <div class="cc-stat-label" style="margin-bottom:4px;">Sort Order</div>
                <input type="number" name="sort_order" value="0" min="0"
                       style="width:100%;padding:7px 10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:7px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
            </div>
            <button type="submit" class="cc-btn primary" style="width:100%;">+ Add FAQ</button>
        </form>
    </div>

    {{-- Filter by category --}}
    <div>
        <div class="cc-card-pad" style="margin-bottom:14px;">
            <div class="cc-section-head" style="margin-bottom:10px;">
                <span class="cc-section-title">Filter by Category</span>
            </div>
            <div class="cc-filter-row" style="flex-wrap:wrap;margin-bottom:0;">
                <a href="{{ route('admin.faqs') }}" class="cc-filter-btn {{ $category==='all'?'active':'' }}">All ({{ $faqs->count() }})</a>
                @foreach($categories as $cat)
                <a href="{{ route('admin.faqs', ['category'=>$cat]) }}"
                   class="cc-filter-btn {{ $category===$cat?'active':'' }}">{{ $cat }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- FAQ List --}}
@php $currentCat = null; @endphp
@forelse($faqs as $faq)
    @if($faq->category !== $currentCat)
        @if($currentCat !== null) </div> @endif
        @php $currentCat = $faq->category; @endphp
        <div class="cc-card-pad" style="margin-bottom:14px;">
        <div class="cc-section-title" style="margin-bottom:12px;">{{ $faq->category }}</div>
    @endif

    <div style="border:0.5px solid rgba(0,63,95,0.1);border-radius:8px;padding:12px;margin-bottom:8px;background:{{ $faq->is_active ? 'transparent' : 'rgba(0,63,95,0.03)' }};">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
            <div style="flex:1;">
                <div style="font-size:12px;font-weight:600;color:#003F5F;margin-bottom:5px;">{{ $faq->question }}</div>
                <div style="font-size:11px;color:rgba(0,63,95,0.6);line-height:1.5;">{{ $faq->answer }}</div>
            </div>
            <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                <span class="cc-pill {{ $faq->is_active ? 'green' : 'amber' }}" style="font-size:9px;">{{ $faq->is_active ? 'Active' : 'Hidden' }}</span>
                <button onclick="document.getElementById('edit-faq-{{ $faq->id }}').classList.toggle('hidden')"
                        class="cc-btn secondary" style="font-size:10px;padding:4px 8px;">Edit</button>
                <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" style="display:inline;"
                      onsubmit="return confirm('Delete this FAQ?')">
                    @csrf @method('DELETE')
                    <button class="cc-btn danger" type="submit" style="font-size:10px;padding:4px 8px;">Delete</button>
                </form>
            </div>
        </div>
        <div id="edit-faq-{{ $faq->id }}" class="hidden" style="margin-top:10px;padding-top:10px;border-top:0.5px solid rgba(0,63,95,0.08);">
            <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
                @csrf @method('PATCH')
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px;">
                    <div>
                        <div class="cc-stat-label" style="margin-bottom:3px;">Category</div>
                        <input type="text" name="category" value="{{ $faq->category }}" required
                               style="width:100%;padding:6px 8px;border:0.5px solid rgba(0,63,95,0.18);border-radius:6px;font-size:11px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                    </div>
                    <div>
                        <div class="cc-stat-label" style="margin-bottom:3px;">Sort Order</div>
                        <input type="number" name="sort_order" value="{{ $faq->sort_order }}" min="0"
                               style="width:100%;padding:6px 8px;border:0.5px solid rgba(0,63,95,0.18);border-radius:6px;font-size:11px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                    </div>
                </div>
                <div style="margin-bottom:8px;">
                    <div class="cc-stat-label" style="margin-bottom:3px;">Question</div>
                    <input type="text" name="question" value="{{ $faq->question }}" required
                           style="width:100%;padding:6px 8px;border:0.5px solid rgba(0,63,95,0.18);border-radius:6px;font-size:11px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
                <div style="margin-bottom:8px;">
                    <div class="cc-stat-label" style="margin-bottom:3px;">Answer</div>
                    <textarea name="answer" rows="3" required
                              style="width:100%;padding:6px 8px;border:0.5px solid rgba(0,63,95,0.18);border-radius:6px;font-size:11px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;resize:vertical;">{{ $faq->answer }}</textarea>
                </div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                    <label style="display:flex;align-items:center;gap:6px;font-size:11px;color:#003F5F;cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ $faq->is_active ? 'checked' : '' }}> Active
                    </label>
                </div>
                <button type="submit" class="cc-btn primary" style="font-size:11px;padding:6px 14px;">Save</button>
            </form>
        </div>
    </div>
@empty
<div class="cc-card-pad" style="color:rgba(0,63,95,0.3);font-size:12px;">No FAQs yet. Add one using the form.</div>
@endforelse
@if($faqs->isNotEmpty()) </div> @endif

@push('scripts')
<script>
document.querySelectorAll('.hidden').forEach(el => el.style.display = 'none');
document.querySelectorAll('[onclick]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.getElementById(this.getAttribute('onclick').match(/'([^']+)'/)[1]);
        if (target) target.style.display = target.style.display === 'none' ? 'block' : 'none';
    });
});
</script>
@endpush
@endsection
