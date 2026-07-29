@extends('layouts.admin')

@section('title', $user->name)

@section('content')

{{-- BACK + HEADER --}}
<div style="margin-bottom:16px;">
    <a href="{{ route('admin.users') }}" class="cc-btn secondary" style="margin-bottom:10px; display:inline-block;">← Back to Users</a>
    <div class="cc-page-header" style="margin-bottom:0;">
        <h1>{{ $user->name }}</h1>
        <p>Member since {{ $user->created_at->format('d M Y') }} · {{ $user->created_at->diffForHumans() }}</p>
    </div>
</div>

<div class="cc-grid-2">

    {{-- LEFT: PROFILE CARD --}}
    <div>
        <div class="cc-card-pad" style="margin-bottom:14px;">
            <div style="display:flex; align-items:center; gap:14px; margin-bottom:16px; padding-bottom:14px; border-bottom:0.5px solid rgba(0,63,95,0.07);">
                <div class="cc-user-av-lg" style="width:48px;height:48px;font-size:16px;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <div class="cc-user-name" style="font-size:14px;">{{ $user->name }}</div>
                    <div class="cc-user-sub">{{ $user->email }}</div>
                    <div style="margin-top:5px; display:flex; gap:5px;">
                        @if($user->is_suspended)
                            <span class="cc-pill red">Suspended</span>
                        @elseif($user->email_verified_at)
                            <span class="cc-pill green">Verified</span>
                        @else
                            <span class="cc-pill amber">Unverified</span>
                        @endif
                        @if($user->is_admin)
                            <span class="cc-pill teal">Admin</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Email</span>
                <span class="cc-user-stat-val">{{ $user->email }}</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Joined</span>
                <span class="cc-user-stat-val">{{ $user->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Email Verified</span>
                <span class="cc-user-stat-val">
                    {{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Not verified' }}
                </span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Account Status</span>
                <span class="cc-user-stat-val">{{ $user->is_suspended ? 'Suspended' : 'Active' }}</span>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="cc-card-pad">
            <div class="cc-section-title" style="margin-bottom:12px;">Admin Actions</div>

            <div style="display:flex; flex-direction:column; gap:8px;">

                @if(!$user->email_verified_at)
                <form method="POST" action="{{ route('admin.users.verify', $user) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="cc-btn primary" style="width:100%; padding:8px; font-size:12px;">
                        ✓ Mark as Verified
                    </button>
                </form>
                @endif

                @if($user->is_suspended)
                <form method="POST" action="{{ route('admin.users.unsuspend', $user) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="cc-btn secondary" style="width:100%; padding:8px; font-size:12px;">
                        ↑ Reinstate Account
                    </button>
                </form>
                @else
                    @if(!$user->is_admin)
                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}"
                          data-confirm="Suspend {{ $user->name }}? They will lose access to the platform until reinstated." data-confirm-ok="Suspend">
                        @csrf @method('PATCH')
                        <button type="submit" class="cc-btn danger" style="width:100%; padding:8px; font-size:12px;">
                            ⊘ Suspend Account
                        </button>
                    </form>
                    @endif
                @endif

                @if(!$user->is_admin)
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      data-confirm="Permanently delete {{ $user->name }}? All their listings, orders and data will be removed. This cannot be undone." data-confirm-ok="Delete Account">
                    @csrf @method('DELETE')
                    <button type="submit" class="cc-btn danger" style="width:100%; padding:8px; font-size:12px;">
                        ✕ Delete Account
                    </button>
                </form>
                @endif

            </div>

            @if($user->is_admin)
                <div class="cc-info-box" style="margin-top:12px;">
                    This is an <span>admin account</span>. Suspend and delete actions are disabled.
                </div>
            @endif
        </div>
    </div>

    {{-- RIGHT: ACTIVITY --}}
    <div>
        <div class="cc-card-pad" style="margin-bottom:14px;">
            <div class="cc-section-title" style="margin-bottom:12px;">Account Summary</div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Listings</span>
                <span class="cc-user-stat-val">—</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Rentals as Borrower</span>
                <span class="cc-user-stat-val">—</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Rentals as Lender</span>
                <span class="cc-user-stat-val">—</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Reviews Received</span>
                <span class="cc-user-stat-val">—</span>
            </div>
            <div class="cc-user-stat-row">
                <span class="cc-user-stat-label">Reward Coins</span>
                <span class="cc-user-stat-val">—</span>
            </div>
            <div class="cc-info-box" style="margin-top:10px;">
                Activity data will populate once <span>listings and orders</span> are connected.
            </div>
        </div>

        <div class="cc-card-pad">
            <div class="cc-section-title" style="margin-bottom:12px;">Recent Listings</div>
            <div style="font-size:11px; color:rgba(0,63,95,0.3); padding:8px 0;">
                No listings yet — will show here once the products module is built.
            </div>
        </div>
    </div>

</div>

@endsection
