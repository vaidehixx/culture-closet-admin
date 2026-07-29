@extends('layouts.admin')
@section('title', 'Message #'.$message->id)
@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.contact') }}" class="cc-btn secondary" style="display:inline-block;margin-bottom:10px;">← Back</a>
    <div class="cc-page-header" style="margin-bottom:0;">
        <h1>Message #{{ $message->id }}</h1>
        <p>Received {{ $message->created_at->format('d M Y, H:i') }} · {{ $message->created_at->diffForHumans() }}</p>
    </div>
</div>

<div class="cc-grid-2">
    <div class="cc-card-pad">
        <div class="cc-section-title" style="margin-bottom:12px;">Message Details</div>
        <div class="cc-user-stat-row"><span class="cc-user-stat-label">From</span><span class="cc-user-stat-val">{{ $message->name }}</span></div>
        <div class="cc-user-stat-row"><span class="cc-user-stat-label">Email</span><span class="cc-user-stat-val">{{ $message->email }}</span></div>
        <div class="cc-user-stat-row"><span class="cc-user-stat-label">Subject</span><span class="cc-user-stat-val">{{ $message->subject ?? '(none)' }}</span></div>
        <div class="cc-user-stat-row"><span class="cc-user-stat-label">Status</span>
            <span class="cc-pill {{ $message->status==='resolved'?'green':'amber' }}">{{ ucfirst($message->status) }}</span>
        </div>
        <div style="margin-top:14px;padding:14px;background:rgba(0,63,95,0.03);border-radius:8px;border:0.5px solid rgba(0,63,95,0.08);">
            <div style="font-size:11px;color:rgba(0,63,95,0.4);margin-bottom:6px;">Message body</div>
            <div style="font-size:13px;color:#003F5F;line-height:1.6;white-space:pre-wrap;">{{ $message->body }}</div>
        </div>
    </div>

    <div class="cc-card-pad">
        <div class="cc-section-title" style="margin-bottom:12px;">Admin Notes &amp; Resolution</div>
        <form method="POST" action="{{ route('admin.contact.resolve', $message) }}">
            @csrf @method('PATCH')
            <div style="margin-bottom:10px;">
                <textarea name="admin_notes" rows="5" placeholder="Add notes or resolution details…"
                          style="width:100%;padding:10px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;resize:vertical;">{{ $message->admin_notes }}</textarea>
            </div>
            <button type="submit" class="cc-btn primary" style="width:100%;">✓ Mark as Resolved</button>
        </form>
        @if($message->resolved_at)
        <div style="margin-top:10px;font-size:11px;color:rgba(0,63,95,0.4);">Resolved {{ $message->resolved_at->diffForHumans() }}</div>
        @endif
        <form method="POST" action="{{ route('admin.contact.destroy', $message) }}" style="margin-top:8px;"
              onsubmit="return confirm('Delete this message permanently?')">
            @csrf @method('DELETE')
            <button type="submit" class="cc-btn danger" style="width:100%;">Delete Message</button>
        </form>
    </div>
</div>
@endsection
