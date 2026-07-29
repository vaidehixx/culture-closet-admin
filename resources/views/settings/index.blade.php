@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<div class="cc-page-header">
    <h1>Settings</h1>
    <p>Platform configuration and policies</p>
</div>

<div class="cc-grid-2">

    {{-- FEES & LIMITS --}}
    <div class="cc-card-pad">
        <div class="cc-section-title" style="margin-bottom:16px;">Fees &amp; Limits</div>
        <form method="POST" action="{{ route('admin.settings.fees') }}">
            @csrf @method('PATCH')
            <div style="margin-bottom:12px;">
                <div class="cc-stat-label" style="margin-bottom:5px;">Platform Fee — Owners (%)</div>
                <input type="number" name="platform_fee_percent" min="0" max="50" step="0.5"
                       value="{{ $settings['platform_fee_percent'] }}"
                       style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                <div style="font-size:10px;color:rgba(0,63,95,0.4);margin-top:3px;">Deducted from owner payout per rental (currently 10%)</div>
            </div>
            <div style="margin-bottom:12px;">
                <div class="cc-stat-label" style="margin-bottom:5px;">Service Fee — Renters (%)</div>
                <input type="number" name="service_fee_percent" min="0" max="50" step="0.5"
                       value="{{ $settings['service_fee_percent'] }}"
                       style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                <div style="font-size:10px;color:rgba(0,63,95,0.4);margin-top:3px;">Added to renter total per transaction (currently 4%)</div>
            </div>
            <div style="margin-bottom:12px;">
                <div class="cc-stat-label" style="margin-bottom:5px;">Flat Cleaning Fee (SGD)</div>
                <input type="number" name="cleaning_fee" min="0" step="0.10"
                       value="{{ $settings['cleaning_fee'] }}"
                       style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                <div style="font-size:10px;color:rgba(0,63,95,0.4);margin-top:3px;">Added to all rentals to cover garment cleaning (currently $5.90)</div>
            </div>
            <div style="margin-bottom:12px;">
                <div class="cc-stat-label" style="margin-bottom:5px;">Late Return Fee per Day (SGD)</div>
                <input type="number" name="late_fee_per_day" min="0" step="1"
                       value="{{ $settings['late_fee_per_day'] }}"
                       style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                <div style="font-size:10px;color:rgba(0,63,95,0.4);margin-top:3px;">Charged per day for late returns (currently $50/day, max 200% retail value)</div>
            </div>
            <div style="margin-bottom:12px;">
                <div class="cc-stat-label" style="margin-bottom:5px;">Min Listing Price (SGD)</div>
                <input type="number" name="min_listing_price" min="0" step="1"
                       value="{{ $settings['min_listing_price'] }}"
                       style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
            </div>
            <div style="margin-bottom:18px;">
                <div class="cc-stat-label" style="margin-bottom:5px;">Max Rental Duration (days)</div>
                <input type="number" name="max_rental_days" min="1" max="365"
                       value="{{ $settings['max_rental_days'] }}"
                       style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
            </div>
            <button type="submit" class="cc-btn primary" style="padding:8px 20px;font-size:12px;">Save Fee Settings</button>
        </form>
    </div>

    {{-- REWARD COINS --}}
    <div class="cc-card-pad">
        <div class="cc-section-title" style="margin-bottom:16px;">Reward Coins</div>
        <form method="POST" action="{{ route('admin.settings.coins') }}">
            @csrf @method('PATCH')
            <div style="margin-bottom:12px;">
                <div class="cc-stat-label" style="margin-bottom:5px;">Coins Earned per Action</div>
                <div class="cc-info-box" style="margin-bottom:10px;font-size:11px;">
                    Refer a friend: <span>1000</span> · Complete profile: <span>150</span> · Complete rental: <span>100</span><br>
                    Upload garment: <span>500</span> · Review (base): <span>30</span> · 5-star review received: <span>50</span>
                </div>
            </div>
            <div style="margin-bottom:12px;">
                <div class="cc-stat-label" style="margin-bottom:5px;">Coins Earned per Rental (base)</div>
                <input type="number" name="coin_earn_rate" min="0" step="1"
                       value="{{ $settings['coin_earn_rate'] }}"
                       style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                <div style="font-size:10px;color:rgba(0,63,95,0.4);margin-top:3px;">Default: 500 coins per completed rental</div>
            </div>
            <div style="margin-bottom:12px;">
                <div class="cc-stat-label" style="margin-bottom:5px;">Coins Required per SGD 1 Discount</div>
                <input type="number" name="coin_redeem_rate" min="1" step="1"
                       value="{{ $settings['coin_redeem_rate'] }}"
                       style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
                <div style="font-size:10px;color:rgba(0,63,95,0.4);margin-top:3px;">e.g. 500 = redeem 500 coins for SGD 1 off</div>
            </div>
            <div style="margin-bottom:16px;">
                <div class="cc-stat-label" style="margin-bottom:6px;">Membership Tiers</div>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <div style="display:flex;justify-content:space-between;padding:8px 12px;background:rgba(205,127,50,0.06);border-radius:7px;border:0.5px solid rgba(205,127,50,0.2);">
                        <span style="font-size:12px;font-weight:500;color:#8B6914;">Bronze</span>
                        <span style="font-size:11px;color:rgba(0,63,95,0.5);">0 – 4,999 coins</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 12px;background:rgba(192,192,192,0.08);border-radius:7px;border:0.5px solid rgba(128,128,128,0.2);">
                        <span style="font-size:12px;font-weight:500;color:#555;">Silver (1.5x coins)</span>
                        <span style="font-size:11px;color:rgba(0,63,95,0.5);">5,000 – 9,999 coins</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:8px 12px;background:rgba(255,200,87,0.08);border-radius:7px;border:0.5px solid rgba(255,200,87,0.3);">
                        <span style="font-size:12px;font-weight:500;color:#B8860B;">Gold (2x coins)</span>
                        <span style="font-size:11px;color:rgba(0,63,95,0.5);">10,000+ coins</span>
                    </div>
                </div>
            </div>
            <button type="submit" class="cc-btn primary" style="padding:8px 20px;font-size:12px;">Save Coin Settings</button>
        </form>
    </div>

    {{-- CONTENT POLICY --}}
    <div class="cc-card-pad">
        <div class="cc-section-title" style="margin-bottom:16px;">Content Policy — Prohibited Words</div>
        <form method="POST" action="{{ route('admin.settings.content-policy') }}">
            @csrf @method('PATCH')
            <div style="margin-bottom:8px;">
                <div class="cc-stat-label" style="margin-bottom:8px;">Current prohibited words</div>
                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px;">
                    @foreach(array_filter(array_map('trim', $settings['prohibited_words'])) as $word)
                        <span class="cc-word-chip">{{ $word }}</span>
                    @endforeach
                </div>
            </div>
            <div style="margin-bottom:14px;">
                <div class="cc-stat-label" style="margin-bottom:5px;">Update list (comma-separated)</div>
                <input type="text" name="prohibited_words"
                       value="{{ implode(', ', array_filter(array_map('trim', $settings['prohibited_words']))) }}"
                       style="width:100%;padding:8px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:13px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;">
            </div>
            <button type="submit" class="cc-btn primary" style="padding:8px 20px;font-size:12px;">Save Content Policy</button>
        </form>
    </div>

    {{-- INSURANCE PLANS --}}
    <div class="cc-card-pad">
        <div class="cc-section-title" style="margin-bottom:16px;">Insurance Plans</div>
        <div class="cc-info-box" style="margin-bottom:14px;font-size:11px;">Insurance fees are fixed by policy — update in code if changed.</div>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <div style="padding:12px;background:rgba(0,63,95,0.03);border-radius:8px;border:0.5px solid rgba(0,63,95,0.1);">
                <div style="font-size:12px;font-weight:600;color:#003F5F;margin-bottom:4px;">Standard Plan</div>
                <div style="font-size:11px;color:rgba(0,63,95,0.6);">10% of rental fee · Covers up to 20% of assessed damage costs</div>
            </div>
            <div style="padding:12px;background:rgba(255,200,87,0.06);border-radius:8px;border:0.5px solid rgba(255,200,87,0.3);">
                <div style="font-size:12px;font-weight:600;color:#003F5F;margin-bottom:4px;">Enhanced Plan</div>
                <div style="font-size:11px;color:rgba(0,63,95,0.6);">15% of rental fee · Covers up to 70% of assessed damage costs</div>
            </div>
        </div>
    </div>

</div>

{{-- LEGAL CONTENT --}}
<div class="cc-card-pad" style="margin-top:14px;">
    <div class="cc-section-title" style="margin-bottom:16px;">Legal Content</div>
    <form method="POST" action="{{ route('admin.settings.legal') }}">
        @csrf @method('PATCH')
        <div class="cc-grid-2">
            <div>
                <div class="cc-stat-label" style="margin-bottom:8px;">Terms &amp; Conditions</div>
                <textarea name="terms_and_conditions" rows="20"
                          style="width:100%;padding:10px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;resize:vertical;line-height:1.6;">{{ $settings['terms_and_conditions'] }}</textarea>
            </div>
            <div>
                <div class="cc-stat-label" style="margin-bottom:8px;">Privacy Policy</div>
                <textarea name="privacy_policy" rows="20"
                          style="width:100%;padding:10px 12px;border:0.5px solid rgba(0,63,95,0.18);border-radius:8px;font-size:12px;font-family:'DM Sans',sans-serif;color:#003F5F;outline:none;resize:vertical;line-height:1.6;">{{ $settings['privacy_policy'] }}</textarea>
            </div>
        </div>
        <div style="margin-top:14px;">
            <button type="submit" class="cc-btn primary" style="padding:8px 24px;font-size:12px;">Save Legal Content</button>
        </div>
    </form>
</div>
@endsection
