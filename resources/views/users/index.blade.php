@extends('layouts.admin')

@section('title', 'Users')

@section('content')

<div class="cc-page-header">
    <h1>Users</h1>
    <p>{{ number_format($counts['all']) }} registered members on the platform</p>
</div>

{{-- STAT STRIP --}}
<div class="cc-stats-grid" style="margin-bottom:18px;">
    <div class="cc-stat">
        <div class="cc-stat-label">Total Users</div>
        <div class="cc-stat-val">{{ number_format($counts['all']) }}</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Verified</div>
        <div class="cc-stat-val">{{ number_format($counts['verified']) }}</div>
        <div class="cc-stat-trend trend-up">Email confirmed</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Unverified</div>
        <div class="cc-stat-val">{{ number_format($counts['unverified']) }}</div>
        <div class="cc-stat-trend trend-neutral">Pending confirmation</div>
    </div>
    <div class="cc-stat">
        <div class="cc-stat-label">Suspended</div>
        <div class="cc-stat-val">{{ number_format($counts['suspended']) }}</div>
        <div class="cc-stat-trend {{ $counts['suspended'] > 0 ? 'trend-down' : 'trend-neutral' }}">
            {{ $counts['suspended'] > 0 ? 'Needs review' : 'All clear' }}
        </div>
    </div>
</div>

{{-- SEARCH + FILTERS --}}
<div class="cc-card-pad" style="margin-bottom:0; border-radius:10px 10px 0 0; border-bottom:none;">
    <form method="GET" action="{{ route('admin.users') }}" id="filter-form">
        <div class="cc-search">
            <span class="cc-search-icon">⌕</span>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or email…"
                oninput="document.getElementById('filter-form').submit()"
            >
            @if(request('search'))
                <a href="{{ route('admin.users', ['filter' => request('filter')]) }}" style="font-size:11px; color:rgba(0,63,95,0.4); text-decoration:none;">✕ Clear</a>
            @endif
        </div>

        @php $activeFilter = request('filter', 'all'); @endphp
        <div class="cc-filter-row" style="margin-bottom:0;">
            @foreach(['all' => 'All ('.$counts['all'].')', 'verified' => 'Verified ('.$counts['verified'].')', 'unverified' => 'Unverified ('.$counts['unverified'].')', 'suspended' => 'Suspended ('.$counts['suspended'].')'] as $key => $label)
                <a href="{{ route('admin.users', array_merge(request()->only('search'), ['filter' => $key])) }}"
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
                <th>User</th>
                <th>Email</th>
                <th>Joined</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:9px;">
                        <div class="cc-avatar-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                        <div>
                            <div style="font-weight:500;">{{ $user->name }}</div>
                        </div>
                    </div>
                </td>
                <td style="color:rgba(0,63,95,0.6);">{{ $user->email }}</td>
                <td style="color:rgba(0,63,95,0.5);">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    @if($user->is_suspended)
                        <span class="cc-pill red">Suspended</span>
                    @elseif($user->email_verified_at)
                        <span class="cc-pill green">Verified</span>
                    @else
                        <span class="cc-pill amber">Unverified</span>
                    @endif
                </td>
                <td>
                    <div class="cc-btn-wrap">
                        <a href="{{ route('admin.users.show', $user) }}" class="cc-btn secondary">View</a>

                        @if(!$user->email_verified_at)
                            <form method="POST" action="{{ route('admin.users.verify', $user) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button class="cc-btn primary" type="submit">Verify</button>
                            </form>
                        @endif

                        @if($user->is_suspended)
                            <form method="POST" action="{{ route('admin.users.unsuspend', $user) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button class="cc-btn secondary" type="submit">Reinstate</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.suspend', $user) }}" style="display:inline;"
                                  data-confirm="Suspend {{ $user->name }}? They will lose access to the platform." data-confirm-ok="Suspend">
                                @csrf @method('PATCH')
                                <button class="cc-btn danger" type="submit">Suspend</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;"
                              data-confirm="Permanently delete {{ $user->name }}? All their data will be removed." data-confirm-ok="Delete">
                            @csrf @method('DELETE')
                            <button class="cc-btn danger" type="submit">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:32px; color:rgba(0,63,95,0.3);">
                    No users found{{ request('search') ? ' for "'.request('search').'"' : '' }}.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    @if($users->hasPages())
    <div style="padding:12px 16px; border-top:0.5px solid rgba(0,63,95,0.06); display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:11px; color:rgba(0,63,95,0.4);">
            Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}
        </span>
        <div style="display:flex; gap:4px;">
            @if($users->onFirstPage())
                <span class="cc-btn secondary" style="opacity:0.4; cursor:default;">← Prev</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="cc-btn secondary">← Prev</a>
            @endif
            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="cc-btn secondary">Next →</a>
            @else
                <span class="cc-btn secondary" style="opacity:0.4; cursor:default;">Next →</span>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection
