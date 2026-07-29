<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::truncate();
        $faqs = [
            // General
            ['category'=>'General','sort_order'=>1,'question'=>'What is Culture Closet?','answer'=>'We\'re a platform where you can rent stunning ethnic wear from others or list your own outfits for rent. Whether it\'s for a wedding, festival, or just because, we\'ve got you covered!'],
            ['category'=>'General','sort_order'=>2,'question'=>'Who can use Culture Closet?','answer'=>'Anyone who is looking to rent a stunning outfit for a special occasion, or wants to share their wardrobe and earn from it! We\'re here for both renters and owners who believe in fashion with purpose and sustainability.'],
            ['category'=>'General','sort_order'=>3,'question'=>'How does Culture Closet promote sustainability?','answer'=>'By renting and reusing outfits, we reduce waste and give beautiful clothes a second (or third, or fourth!) life. Over US$20 Billion is spent on ethnic wear annually, and the world sees more than 92 million tonnes of textile waste every year. We are actively on a mission to reduce this through circular fashion.'],

            // Renting Outfits
            ['category'=>'Renting','sort_order'=>1,'question'=>'How do I rent an outfit?','answer'=>"Browse our collection and find something you love.\nCheck the calendar for availability and select your rental dates.\nSubmit a request and wait for the owner to confirm your booking. You'll only be charged once the owner confirms."],
            ['category'=>'Renting','sort_order'=>2,'question'=>'How do I know if the outfit will fit me?','answer'=>'We encourage owners to provide detailed sizing information in their product descriptions. If you\'re unsure, you can always message the owner for more details.'],
            ['category'=>'Renting','sort_order'=>3,'question'=>'I rented an outfit but it doesn\'t fit me! What should I do?','answer'=>'No worries — we\'ve made returns hassle-free. If your outfit doesn\'t fit, you can choose a Culture Closet-facilitated return for just $1.90, or go for a free self drop-off arrangement with the owner. The return process must be initiated within three hours of confirming garment receival.'],
            ['category'=>'Renting','sort_order'=>4,'question'=>'What if I damage or lose the outfit?','answer'=>'Accidents can happen to anyone — don\'t worry, we\'ve got your back. While checking out, you can opt for damage protection. The Standard Plan covers up to 20% of damages (10% of rental fee), while the Enhanced Plan covers up to 70% (15% of rental fee).'],
            ['category'=>'Renting','sort_order'=>5,'question'=>'Can I cancel my rental?','answer'=>'Yes, but refunds will be issued as in-app credits for your next rental. Check out our Cancellation Policy for more details.'],
            ['category'=>'Renting','sort_order'=>6,'question'=>'What if I go past the return deadline?','answer'=>'Late returns incur an automatic fee of $50 per day, up to a maximum of 200% of the garment\'s retail value. Timely returns ensure the next renter gets their outfit in time for their special moment.'],

            // Listing Your Outfits
            ['category'=>'Listing','sort_order'=>1,'question'=>'How do I put my outfits up for rent?','answer'=>'Simply create an account, upload photos of your outfit, add a description (including sizing details), and set your rental price. And that\'s it — sit back and earn!'],
            ['category'=>'Listing','sort_order'=>2,'question'=>'How do I ensure my outfit is clean and ready for the next rental?','answer'=>'A flat $5.90 cleaning fee is included in every rental. This helps cover cleaning supplies and supports ongoing garment care between rentals.'],
            ['category'=>'Listing','sort_order'=>3,'question'=>'What if my outfit gets damaged or lost?','answer'=>'Our support team will assess the damage before providing a quote to the renter. If your garment cannot be repaired, you will be reimbursed for the entire cost. That\'s why we request the retail price when you upload a listing!'],
            ['category'=>'Listing','sort_order'=>4,'question'=>'What if my outfit gets returned late?','answer'=>'If a renter returns your outfit late, they\'ll be charged $50 per day (up to 200% of retail value). This fee is passed on to you (excluding service fee) as compensation for the inconvenience.'],

            // Delivery & Pickup
            ['category'=>'Delivery','sort_order'=>1,'question'=>'How does delivery/pickup work?','answer'=>'Once your rental is confirmed, you and the owner can coordinate delivery or pickup directly within the app through the in-app messaging feature. Whether you prefer to meet in person or arrange an external courier, the choice is yours.'],
            ['category'=>'Delivery','sort_order'=>2,'question'=>'Who covers the delivery/pickup costs?','answer'=>'This is agreed upon between the renter and the owner. Make sure to discuss this before confirming the rental!'],

            // Pricing & Fees
            ['category'=>'Pricing & Fees','sort_order'=>1,'question'=>'How much does it cost to rent an outfit?','answer'=>'Prices are set by the owners, so you\'ll find a range of options to fit your budget.'],
            ['category'=>'Pricing & Fees','sort_order'=>2,'question'=>'Are there any hidden fees?','answer'=>"No hidden charges — we believe in transparency. Here's a breakdown:\n\nFor renters: A 4% service fee is added to all transactions.\nFor owners: A 10% platform fee is deducted from each rental.\nA flat $5.90 cleaning fee is added to all rentals.\n\nAll fees are clearly displayed before you confirm any transaction — no surprises."],
            ['category'=>'Pricing & Fees','sort_order'=>3,'question'=>'How does the Culture Closet membership work?','answer'=>'We offer Bronze, Silver, and Gold memberships with exclusive perks! Bronze: 0–4,999 coins. Silver (1.5x coins): 5,000–9,999 coins. Gold (2x coins): 10,000+ coins. Each tier unlocks additional perks like birthday bonuses and listing boosts for owners.'],

            // Insurance
            ['category'=>'Insurance','sort_order'=>1,'question'=>'How does the insurance plan work?','answer'=>'During checkout, renters can opt into our insurance plan. The Standard Plan covers up to 20% of damage costs at 10% of the rental fee. The Enhanced Plan covers up to 70% at 15% of the rental fee.'],
            ['category'=>'Insurance','sort_order'=>2,'question'=>'Is insurance mandatory?','answer'=>'Nope, it\'s optional — but we highly recommend it for peace of mind. Accidents like curry and wine can happen, so it\'s always better to be on the side of caution!'],
            ['category'=>'Insurance','sort_order'=>3,'question'=>'What if I have an issue with an owner or renter?','answer'=>'Our team is here to help! Reach out to us through the app, and we\'ll resolve the issue as quickly as possible.'],

            // Support
            ['category'=>'Support','sort_order'=>1,'question'=>'How do I contact Culture Closet support?','answer'=>'Reach out to us at support@culturecloset.site or through the app\'s "Customer Support" feature found in the "Help Center". We\'re dedicated to making your ethnic wear experience seamless, joyful, and stress-free.'],
        ];
        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['is_active' => true]));
        }
    }
}
