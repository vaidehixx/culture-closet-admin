@extends('layouts.admin')
@section('title', 'Blog Posts')
@section('content')
<div class="cc-page-header">
    <h1>Blog</h1>
    <p>Manage editorial content on the platform</p>
</div>

<div class="cc-stats-grid-3" style="margin-bottom:18px;">
    <div class="cc-stat teal">
        <div class="cc-stat-label">Total Posts</div>
        <div class="cc-stat-val">{{ number_format($counts['all']) }}</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Published</div>
        <div class="cc-stat-val">{{ number_format($counts['published']) }}</div>
        <div class="cc-stat-trend trend-up">Live</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Drafts</div>
        <div class="cc-stat-val">{{ number_format($counts['draft']) }}</div>
        <div class="cc-stat-trend trend-neutral">Unpublished</div>
    </div>
</div>

<div class="cc-card-pad" style="margin-bottom:0;border-radius:10px 10px 0 0;border-bottom:none;">
    <div class="cc-section-head">
        <div class="cc-filter-row" style="margin-bottom:0;">
            @foreach(['all'=>'All','published'=>'Published','draft'=>'Drafts'] as $key=>$label)
            <a href="{{ route('admin.blog', ['filter'=>$key]) }}"
               class="cc-filter-btn {{ $filter===$key?'active':'' }}">{{ $label }}</a>
            @endforeach
        </div>
        <a href="{{ route('admin.blog.create') }}" class="cc-btn primary">+ New Post</a>
    </div>
</div>

<div class="cc-card" style="border-radius:0 0 10px 10px;">
    <table class="cc-table">
        <thead>
            <tr><th>#</th><th>Title</th><th>Category</th><th>Status</th><th>Published</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @forelse($posts as $post)
        <tr>
            <td style="font-weight:500;">#{{ $post->id }}</td>
            <td>
                <div style="font-weight:500;font-size:12px;">{{ $post->title }}</div>
                @if($post->excerpt)<div style="font-size:10px;color:rgba(0,63,95,0.4);max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $post->excerpt }}</div>@endif
            </td>
            <td style="font-size:11px;">{{ $post->category ?? '—' }}</td>
            <td><span class="cc-pill {{ $post->status==='published'?'green':'amber' }}">{{ ucfirst($post->status) }}</span></td>
            <td style="font-size:10px;color:rgba(0,63,95,0.4);">
                {{ $post->published_at ? $post->published_at->format('d M Y') : '—' }}
            </td>
            <td>
                <div class="cc-btn-wrap">
                    <a href="{{ route('admin.blog.edit', $post) }}" class="cc-btn secondary">Edit</a>
                    <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" style="display:inline;"
                          onsubmit="return confirm('Delete this post? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button class="cc-btn danger" type="submit">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:rgba(0,63,95,0.3);">No posts yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    @if($posts->hasPages())
    <div style="padding:12px 16px;border-top:0.5px solid rgba(0,63,95,0.06);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:11px;color:rgba(0,63,95,0.4);">Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }} of {{ $posts->total() }}</span>
        <div style="display:flex;gap:4px;">
            @if($posts->onFirstPage()) <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">← Prev</span>
            @else <a href="{{ $posts->previousPageUrl() }}" class="cc-btn secondary">← Prev</a> @endif
            @if($posts->hasMorePages()) <a href="{{ $posts->nextPageUrl() }}" class="cc-btn secondary">Next →</a>
            @else <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">Next →</span> @endif
        </div>
    </div>
    @endif
</div>
@endsection
