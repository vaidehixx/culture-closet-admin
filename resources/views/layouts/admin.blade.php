<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — Culture Closet Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">

    <!-- Culture Closet Admin CSS -->
    <link href="{{ asset('admin.css') }}" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>

    @stack('styles')
</head>
<body>

<div class="cc-wrapper">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="cc-sidebar">
        <div class="cc-logo">
            <div class="cc-logo-name">Culture Closet</div>
            <div class="cc-logo-tag">Admin Panel</div>
        </div>

        <nav class="cc-nav">
            {{-- OVERVIEW --}}
            <div class="cc-nav-section">Overview</div>
            <a href="{{ route('admin.dashboard') }}"
               class="cc-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Dashboard</div>
            </a>
            <a href="{{ route('admin.analytics') }}"
               class="cc-nav-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Analytics</div>
            </a>

            {{-- CATALOGUE --}}
            <div class="cc-nav-section">Catalogue</div>
            <a href="{{ route('admin.products') }}"
               class="cc-nav-item {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Products</div>
                <span class="cc-nav-badge amber">22</span>
            </a>
            <a href="{{ route('admin.categories') }}"
               class="cc-nav-item {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Categories</div>
            </a>

            {{-- COMMERCE --}}
            <div class="cc-nav-section">Commerce</div>
            <a href="{{ route('admin.orders') }}"
               class="cc-nav-item {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Orders</div>
                <span class="cc-nav-badge">3</span>
            </a>
            <a href="{{ route('admin.transactions') }}"
               class="cc-nav-item {{ request()->routeIs('admin.transactions') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Transactions</div>
            </a>
            <a href="{{ route('admin.promocodes') }}"
               class="cc-nav-item {{ request()->routeIs('admin.promocodes') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Promo Codes</div>
            </a>
            <a href="{{ route('admin.coins') }}"
               class="cc-nav-item {{ request()->routeIs('admin.coins') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Reward Coins</div>
            </a>

            {{-- COMMUNITY --}}
            <div class="cc-nav-section">Community</div>
            <a href="{{ route('admin.users') }}"
               class="cc-nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Users</div>
                <span class="cc-nav-badge amber">5</span>
            </a>
            <a href="{{ route('admin.reviews') }}"
               class="cc-nav-item {{ request()->routeIs('admin.reviews') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Reviews</div>
                <span class="cc-nav-badge">1</span>
            </a>
            <a href="{{ route('admin.cleaning') }}"
               class="cc-nav-item {{ request()->routeIs('admin.cleaning') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Cleaning Queue</div>
                <span class="cc-nav-badge">4</span>
            </a>
            <a href="{{ route('admin.chat') }}"
               class="cc-nav-item {{ request()->routeIs('admin.chat') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Chat</div>
                <span class="cc-nav-badge">2</span>
            </a>
            <a href="{{ route('admin.contact') }}"
               class="cc-nav-item {{ request()->routeIs('admin.contact') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Contact Messages</div>
                <span class="cc-nav-badge">2</span>
            </a>
            <a href="{{ route('admin.reports') }}"
               class="cc-nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Reports</div>
                <span class="cc-nav-badge">1</span>
            </a>

            {{-- CONTENT --}}
            <div class="cc-nav-section">Content</div>
            <a href="{{ route('admin.notifications') }}"
               class="cc-nav-item {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Push Notifications</div>
            </a>
            <a href="{{ route('admin.blog') }}"
               class="cc-nav-item {{ request()->routeIs('admin.blog') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Blog Posts</div>
            </a>
            <a href="{{ route('admin.faqs') }}"
               class="cc-nav-item {{ request()->routeIs('admin.faqs') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>FAQs</div>
            </a>

            {{-- SYSTEM --}}
            <div class="cc-nav-section">System</div>
            <a href="{{ route('admin.settings') }}"
               class="cc-nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <div class="cc-nav-item-left"><div class="cc-nav-dot"></div>Settings</div>
            </a>
        </nav>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="cc-main">

        {{-- TOPBAR --}}
        <header class="cc-topbar">
            <div class="cc-topbar-title">{{ $title ?? 'Dashboard' }}</div>
            <div class="cc-topbar-right">
                <div class="cc-live-dot"></div>
                <span class="cc-live-text">Live</span>
                <div class="cc-admin-badge">Admin</div>
                <div class="cc-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}</div>
                <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" style="background:none;border:none;cursor:pointer;font-size:10px;color:rgba(0,63,95,0.4);padding:0 2px;" title="Sign out">⏻</button>
                </form>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="cc-content">
            @yield('content')
        </main>

    </div>
</div>

@stack('scripts')
<script>
// Global confirmation modal for destructive / money actions
(function () {
    const overlay = document.createElement('div');
    overlay.id = 'cc-confirm-overlay';
    overlay.innerHTML = `
        <div id="cc-confirm-box">
            <div id="cc-confirm-title">Are you sure?</div>
            <div id="cc-confirm-msg"></div>
            <div class="cc-confirm-btns">
                <button id="cc-confirm-cancel">Cancel</button>
                <button id="cc-confirm-ok">Proceed</button>
            </div>
        </div>`;
    const style = document.createElement('style');
    style.textContent = `
        #cc-confirm-overlay{display:none;position:fixed;inset:0;background:rgba(0,63,95,0.45);z-index:9999;align-items:center;justify-content:center;}
        #cc-confirm-overlay.active{display:flex;}
        #cc-confirm-box{background:#fff;border-radius:14px;padding:28px 28px 22px;max-width:400px;width:90%;box-shadow:0 8px 40px rgba(0,63,95,0.18);}
        #cc-confirm-title{font-family:'Playfair Display',serif;font-size:18px;color:#003F5F;margin-bottom:10px;}
        #cc-confirm-msg{font-size:13px;color:rgba(0,63,95,0.65);line-height:1.5;margin-bottom:22px;}
        .cc-confirm-btns{display:flex;gap:10px;justify-content:flex-end;}
        #cc-confirm-cancel{padding:8px 18px;border-radius:8px;border:0.5px solid rgba(0,63,95,0.25);background:transparent;color:#003F5F;font-family:'DM Sans',sans-serif;font-size:13px;cursor:pointer;}
        #cc-confirm-ok{padding:8px 18px;border-radius:8px;border:none;background:#c0392b;color:#fff;font-family:'DM Sans',sans-serif;font-size:13px;cursor:pointer;font-weight:500;}
        #cc-confirm-ok.safe{background:#003F5F;}`;
    document.head.appendChild(style);
    document.addEventListener('DOMContentLoaded', () => { document.body.appendChild(overlay); });

    let _resolve;
    window.ccConfirm = function(msg, okLabel = 'Proceed', danger = true) {
        return new Promise(res => {
            _resolve = res;
            document.getElementById('cc-confirm-msg').textContent = msg;
            const ok = document.getElementById('cc-confirm-ok');
            ok.textContent = okLabel;
            ok.className = danger ? '' : 'safe';
            overlay.classList.add('active');
        });
    };

    document.addEventListener('click', e => {
        if (e.target.id === 'cc-confirm-cancel' || e.target === overlay) { overlay.classList.remove('active'); _resolve && _resolve(false); }
        if (e.target.id === 'cc-confirm-ok') { overlay.classList.remove('active'); _resolve && _resolve(true); }
    });

    // Wire up all data-confirm forms/buttons
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-confirm]').forEach(el => {
            const tag = el.tagName.toLowerCase();
            if (tag === 'form') {
                el.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const ok = await ccConfirm(this.dataset.confirm, this.dataset.confirmOk || 'Proceed', this.dataset.confirmDanger !== 'false');
                    if (ok) this.submit();
                });
            } else {
                el.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const ok = await ccConfirm(this.dataset.confirm, this.dataset.confirmOk || 'Proceed', this.dataset.confirmDanger !== 'false');
                    if (ok) this.closest('form').submit();
                });
            }
        });
    });
})();
</script>
</body>
</html>