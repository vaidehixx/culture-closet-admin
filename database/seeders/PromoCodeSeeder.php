<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\PromoCode;

class PromoCodeSeeder extends Seeder
{
    public function run(): void
    {
        PromoCode::truncate();
        $codes = [
            // First-Time Rental
            ['code'=>'WELCOME10','description'=>'10% off your first rental (capped at $20). First rental only, verified account required.','type'=>'percent','value'=>10,'min_order'=>0,'max_uses'=>null,'is_active'=>true,'expires_at'=>null],
            // Seasonal / Festive
            ['code'=>'DIWALI20','description'=>'20% off during Diwali campaign (capped at $25). Limited-time, cannot stack.','type'=>'percent','value'=>20,'min_order'=>0,'max_uses'=>500,'is_active'=>true,'expires_at'=>now()->addDays(7)],
            // Minimum Spend
            ['code'=>'SAVE15','description'=>'$15 off orders above $100. Valid across all categories.','type'=>'fixed','value'=>15,'min_order'=>100,'max_uses'=>null,'is_active'=>true,'expires_at'=>null],
            // Loyalty / Repeat Renter
            ['code'=>'THANKYOU15','description'=>'15% off (capped at $25) for users with 3+ completed rentals. Auto-applied.','type'=>'percent','value'=>15,'min_order'=>0,'max_uses'=>null,'is_active'=>true,'expires_at'=>null],
            // Bundle / Group
            ['code'=>'BRIDESQUAD','description'=>'25% off bundles (capped at $40). Must rent 3+ outfits within 3 days. Selected categories.','type'=>'percent','value'=>25,'min_order'=>0,'max_uses'=>200,'is_active'=>true,'expires_at'=>null],
            // BOGO Free
            ['code'=>'BOGOFREE','description'=>'Rent 1, get 1 free (capped at $40). Free garment must be of equal or lesser value. Same day only.','type'=>'fixed','value'=>40,'min_order'=>0,'max_uses'=>100,'is_active'=>true,'expires_at'=>null],
            // BOGO 50% Off
            ['code'=>'BOGO50','description'=>'Rent 1, get 2nd at 50% off (capped at $25). Both garments rented same day. Selected categories.','type'=>'percent','value'=>50,'min_order'=>0,'max_uses'=>200,'is_active'=>true,'expires_at'=>null],
            // Free Delivery
            ['code'=>'FREE10','description'=>'$10 off delivery (no min. spend). Only for users with 5+ completed rentals.','type'=>'fixed','value'=>10,'min_order'=>0,'max_uses'=>null,'is_active'=>true,'expires_at'=>null],
            // Cashback Coins
            ['code'=>'CASHBACK20','description'=>'5,000 coins cashback on orders above $50.','type'=>'fixed','value'=>0,'min_order'=>50,'max_uses'=>null,'is_active'=>true,'expires_at'=>null],
        ];
        foreach ($codes as $code) {
            PromoCode::create(array_merge($code, ['used_count'=>0]));
        }
    }
}
