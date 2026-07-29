@extends('layouts.admin')
@section('title', 'Push Notifications')

@section('content')
<div class="cc-page-header">
    <h1>Push Notifications</h1>
    <p>Send announcements and alerts to your users</p>
</div>

<div class="cc-grid-2">
    {{-- SEND FORM --}}
    <div>
        <div class="cc-card-pad">
            <div class="cc-section-title" style="margin-bottom:16px;">Compose Notification</div>
            <form method="POST" action="{{ route('admin.notifications.send') }}">
                @csrf
                <div style="margin-bottom:12px;">
                    <div class="cc-stat-label" style="margin-bottom:5px;">Title *</div>
                    <input type="text" name="title" placeholder="e.g. New arrivals this week!" required maxlength="100"
                           value="{{ old('title') }}"
                           style="width:100%;padding:9px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                    @error('title') <div style="font-size:11px;color:#c0392b;margin-top:3px;">{{ $message }}</div> @enderror
                </div>
                <div style="margin-bottom:12px;">
                    <div class="cc-stat-label" style="margin-bottom:5px;">Message *</div>
                    <textarea name="body" placeholder="Write your message here…" required maxlength="500" rows="4"
                              style="width:100%;padding:9px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;resize:vertical;">{{ old('body') }}</textarea>
                    @error('body') <div style="font-size:11px;color:#c0392b;margin-top:3px;">{{ $message }}</div> @enderror
                </div>
                <div style="margin-bottom:18px;">
                    <div class="cc-stat-label" style="margin-bottom:5px;">Audience *</div>
                    <select name="audience" style="width:100%;padding:9px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                        <option value="all">All Users</option>
                        <option value="verified">Verified Users Only</option>
                        <option value="unverified">Unverified Users Only</option>
                    </select>
                </div>
                <button type="submit" class="cc-btn primary" style="width:100%;padding:10px;font-size:13px;">
                    ➤ Send Notification
                </button>
            </form>
        </div>

        <div class="cc-info-box" style="margin-top:12px;">
            <span>Note:</span> Notifications are sent via <strong>Expo Push API</strong> directly to users' devices. Recipients must have the app installed and notifications enabled. Only users with a registered push token receive the notification.
        </div>
    </div>

    {{-- HISTORY --}}
    <div>
        <div class="cc-card-pad">
            <div class="cc-section-title" style="margin-bottom:14px;">Sent History</div>
            @forelse($history as $item)
            <div class="cc-mod-item">
                <div class="cc-mod-head">
                    <div>
                        <div style="font-size:12px;font-weight:500;color:#003F5F;margin-bottom:2px;">{{ $item['title'] }}</div>
                        <div style="font-size:11px;color:rgba(0,63,95,0.6);">{{ $item['body'] }}</div>
                    </div>
                    <span class="cc-pill teal">{{ ucfirst($item['audience']) }}</span>
                </div>
                <div style="font-size:10px;color:rgba(0,63,95,0.38);margin-top:6px;display:flex;gap:12px;">
                    <span>{{ $item['sent_at'] }}</span>
                    @isset($item['tokens'])
                    <span style="color:#27ae60;">✓ {{ $item['sent'] ?? 0 }} sent</span>
                    @if(($item['failed'] ?? 0) > 0)
                    <span style="color:#c0392b;">✗ {{ $item['failed'] }} failed</span>
                    @endif
                    <span style="color:rgba(0,63,95,0.45);">{{ $item['tokens'] }} token(s) targeted</span>
                    @endisset
                </div>
            </div>
            @empty
            <div style="font-size:11px;color:rgba(0,63,95,0.3);padding:8px 0;">No notifications sent yet.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
