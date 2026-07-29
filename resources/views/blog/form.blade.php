@extends('layouts.admin')
@section('title', $post ? 'Edit Post' : 'New Post')
@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.blog') }}" class="cc-btn secondary" style="display:inline-block;margin-bottom:10px;">← Back to Blog</a>
    <div class="cc-page-header" style="margin-bottom:0;">
        <h1>{{ $post ? 'Edit Post' : 'New Post' }}</h1>
    </div>
</div>

<form method="POST" action="{{ $post ? route('admin.blog.update', $post) : route('admin.blog.store') }}">
    @csrf @if($post) @method('PATCH') @endif
    <div class="cc-grid-2">
        <div>
            <div class="cc-card-pad" style="margin-bottom:14px;">
                <div class="cc-section-title" style="margin-bottom:14px;">Post Content</div>
                <div style="margin-bottom:12px;">
                    <div class="cc-stat-label" style="margin-bottom:4px;">Title *</div>
                    <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" required
                           style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
                <div style="margin-bottom:12px;">
                    <div class="cc-stat-label" style="margin-bottom:4px;">Excerpt</div>
                    <input type="text" name="excerpt" value="{{ old('excerpt', $post->excerpt ?? '') }}"
                           placeholder="Short summary shown in listings…"
                           style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
                <div>
                    <div class="cc-stat-label" style="margin-bottom:4px;">Body *</div>
                    <textarea name="body" rows="16" required
                              style="width:100%;padding:10px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;resize:vertical;line-height:1.6;">{{ old('body', $post->body ?? '') }}</textarea>
                </div>
            </div>
        </div>
        <div>
            <div class="cc-card-pad">
                <div class="cc-section-title" style="margin-bottom:14px;">Post Settings</div>
                <div style="margin-bottom:12px;">
                    <div class="cc-stat-label" style="margin-bottom:4px;">Category</div>
                    <input type="text" name="category" value="{{ old('category', $post->category ?? '') }}"
                           placeholder="e.g. Style Tips, News, Sustainability…"
                           style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                </div>
                <div style="margin-bottom:16px;">
                    <div class="cc-stat-label" style="margin-bottom:4px;">Status</div>
                    <select name="status" style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                        <option value="draft" {{ old('status', $post->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <button type="submit" class="cc-btn primary" style="width:100%;">
                    {{ $post ? 'Save Changes' : 'Create Post' }}
                </button>
                @if($post)
                <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" style="margin-top:8px;"
                      onsubmit="return confirm('Delete this post permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="cc-btn danger" style="width:100%;">Delete Post</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</form>
@endsection
