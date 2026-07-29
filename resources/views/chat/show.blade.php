@extends('layouts.admin')
@section('title', 'Conversation #'.$conversation->id)
@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.chat') }}" class="cc-btn secondary" style="display:inline-block;margin-bottom:10px;">← Back to Chat</a>
    <div class="cc-page-header" style="margin-bottom:0;">
        <h1>Conversation #{{ $conversation->id }}</h1>
        <p>{{ $conversation->user1->name ?? '—' }} ↔ {{ $conversation->user2->name ?? '—' }}
        @if($conversation->product) · Re: {{ $conversation->product->name }} @endif</p>
    </div>
</div>

<div class="cc-card-pad">
    @forelse($messages as $msg)
    <div style="display:flex;gap:12px;margin-bottom:14px;{{ $msg->is_flagged ? 'background:rgba(192,57,43,0.04);padding:8px;border-radius:8px;border:0.5px solid rgba(192,57,43,0.15);' : '' }}">
        <div style="width:32px;height:32px;border-radius:50%;background:#003F5F;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#EEE5CD;flex-shrink:0;">
            {{ strtoupper(substr($msg->sender->name ?? '?', 0, 1)) }}
        </div>
        <div style="flex:1;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px;">
                <span style="font-size:12px;font-weight:600;color:#003F5F;">{{ $msg->sender->name ?? '—' }}</span>
                <span style="font-size:10px;color:rgba(0,63,95,0.4);">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                @if($msg->is_flagged) <span class="cc-pill red" style="font-size:9px;">Flagged</span> @endif
            </div>
            <div style="font-size:12px;color:#003F5F;line-height:1.5;">{{ $msg->body }}</div>
            <form method="POST" action="{{ route('admin.chat.destroy', $msg) }}" style="display:inline;margin-top:4px;">
                @csrf @method('DELETE')
                <button type="submit" style="font-size:10px;color:rgba(192,57,43,0.6);background:none;border:none;cursor:pointer;padding:0;">Remove</button>
            </form>
        </div>
    </div>
    @empty
    <div style="font-size:11px;color:rgba(0,63,95,0.3);padding:8px 0;">No messages.</div>
    @endforelse
</div>
@endsection
