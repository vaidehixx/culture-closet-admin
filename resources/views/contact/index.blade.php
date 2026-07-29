@extends('layouts.admin')
@section('title', 'Contact Messages')
@section('content')
<div class="cc-page-header">
    <h1>Contact Messages</h1>
    <p>Inbound messages from the contact form</p>
</div>

<div class="cc-stats-grid-3" style="margin-bottom:18px;">
    <div class="cc-stat teal">
        <div class="cc-stat-label">Total</div>
        <div class="cc-stat-val">{{ number_format($counts['all']) }}</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Open</div>
        <div class="cc-stat-val">{{ number_format($counts['open']) }}</div>
        <div class="cc-stat-trend {{ $counts['open'] > 0 ? 'trend-down' : 'trend-up' }}">
            {{ $counts['open'] > 0 ? 'Needs reply' : 'All clear' }}
        </div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Resolved</div>
        <div class="cc-stat-val">{{ number_format($counts['resolved']) }}</div>
        <div class="cc-stat-trend trend-up">Done</div>
    </div>
</div>

<div class="cc-card-pad" style="margin-bottom:0;border-radius:10px 10px 0 0;border-bottom:none;">
    <div class="cc-filter-row" style="margin-bottom:0;">
        @foreach(['all'=>'All ('.$counts['all'].')','open'=>'Open ('.$counts['open'].')','resolved'=>'Resolved ('.$counts['resolved'].')'] as $key=>$label)
        <a href="{{ route('admin.contact', ['filter'=>$key]) }}"
           class="cc-filter-btn {{ $filter===$key?'active':'' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="cc-card" style="border-radius:0 0 10px 10px;">
    <table class="cc-table">
        <thead>
            <tr><th>#</th><th>From</th><th>Subject</th><th>Status</th><th>Date</th><th>Actions</th></tr>
        </thead>
        <tbody>
        @forelse($messages as $msg)
        <tr>
            <td style="font-weight:500;">#{{ $msg->id }}</td>
            <td>
                <div style="font-weight:500;font-size:12px;">{{ $msg->name }}</div>
                <div style="font-size:10px;color:rgba(0,63,95,0.4);">{{ $msg->email }}</div>
            </td>
            <td style="font-size:12px;">{{ $msg->subject ?? '(no subject)' }}</td>
            <td><span class="cc-pill {{ $msg->status==='resolved'?'green':'amber' }}">{{ ucfirst($msg->status) }}</span></td>
            <td style="font-size:10px;color:rgba(0,63,95,0.4);">{{ $msg->created_at->format('d M Y') }}</td>
            <td>
                <div class="cc-btn-wrap">
                    <a href="{{ route('admin.contact.show', $msg) }}" class="cc-btn secondary">View</a>
                    <form method="POST" action="{{ route('admin.contact.destroy', $msg) }}" style="display:inline;"
                          onsubmit="return confirm('Delete this message? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button class="cc-btn danger" type="submit">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:rgba(0,63,95,0.3);">No messages found.</td></tr>
        @endforelse
        </tbody>
    </table>
    @if($messages->hasPages())
    <div style="padding:12px 16px;border-top:0.5px solid rgba(0,63,95,0.06);display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:11px;color:rgba(0,63,95,0.4);">Showing {{ $messages->firstItem() }}–{{ $messages->lastItem() }} of {{ $messages->total() }}</span>
        <div style="display:flex;gap:4px;">
            @if($messages->onFirstPage()) <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">← Prev</span>
            @else <a href="{{ $messages->previousPageUrl() }}" class="cc-btn secondary">← Prev</a> @endif
            @if($messages->hasMorePages()) <a href="{{ $messages->nextPageUrl() }}" class="cc-btn secondary">Next →</a>
            @else <span class="cc-btn secondary" style="opacity:0.4;cursor:default;">Next →</span> @endif
        </div>
    </div>
    @endif
</div>
@endsection
