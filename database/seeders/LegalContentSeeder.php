<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\PlatformSetting;

class LegalContentSeeder extends Seeder
{
    public function run(): void
    {
        $tac = <<<'TEXT'
Terms and Conditions for Culture Closet Private Limited
Last Updated: 25th July 2025

Welcome to Culture Closet Private Limited ("Culture Closet," "we," "us," or "our"). These Terms and Conditions ("Terms") govern your use of our platform, website, mobile application, and services (collectively, the "Platform"). By accessing or using the Platform, you agree to be bound by these Terms. If you do not agree to these Terms, you may not use the Platform.

1. Acceptance of Terms
By registering an account, browsing, or using the Platform, you confirm that you are at least 18 years old and have the legal capacity to enter into a binding agreement. If you are using the Platform on behalf of an organization, you represent that you have the authority to bind that organization to these Terms.

2. Description of Services
Culture Closet provides a peer-to-peer rental platform where users can list, rent, and borrow Indian ethnic wear ("Items") in Singapore. We act solely as an intermediary and do not own, rent, or control the Items listed on the Platform.

3. User Accounts
3.1 Registration
To use the Platform, you must create an account using Singpass, Apple ID, Google, or Facebook. An email or SMS OTP (One-Time Password) will be issued to verify your identity. You must provide accurate, complete, and up-to-date information during registration.

3.2 Account Responsibility
You are responsible for maintaining the confidentiality of your account credentials and for all activities under your account. Notify us immediately of any unauthorized use of your account.

3.3 Account Suspension or Termination
We reserve the right to suspend or terminate your account if you violate these Terms or engage in fraudulent or harmful activities.

4. Listings and Rentals
4.1 Listing Items
You may list Items for rent on the Platform, provided they are clean, in good condition, accurately described, and comply with all applicable laws. Prohibited Items include those that are not clean, severely damaged, or violate any laws or regulations. You are solely responsible for the accuracy, quality, and legality of your listings.

4.2 Renting Items
Renters agree to use Items responsibly and return them in the same condition as received, barring normal wear and tear. Renters are responsible for any damage, loss, or theft of Items during the rental period.

4.3 Insurance Coverage
Renters may elect to purchase either a Standard Insurance Plan or an Enhanced Insurance Plan for each rental transaction. The Standard Insurance Plan provides coverage for up to 20% of the assessed damage costs and is priced at 10% of the applicable rental fee. The Enhanced Insurance Plan provides coverage for up to 70% of the assessed damage costs and is priced at 15% of the applicable rental fee. Insurance coverage applies only to damage, loss, or theft of Items during the rental period and is subject to verification by Culture Closet.

5. Fees and Payments
5.1 Platform Fees
Culture Closet charges a 10% platform fee on all transactions made through the Platform. A 4% service fee is added to renter transactions. A flat $5.90 cleaning fee is included in all rentals. All fees will be clearly disclosed before confirmation of any rental.

5.2 Late Returns
Late returns incur an automatic fee of $50 per day, up to a maximum of 200% of the garment's retail value.

5.3 Refunds
All fees are non-refundable unless otherwise stated in these Terms or required by law. In-store credits may be issued. Refunds for rentals are subject to the owner's discretion and Culture Closet's dispute resolution process (if applicable).

6. User Responsibilities
6.1 Prohibited Activities
You agree not to: list or rent counterfeit, stolen, or illegal Items; use the Platform for any unlawful purpose; harass, defraud, or harm other users; or misrepresent yourself or your Items.

6.2 Liability for Items
Owners are solely responsible for ensuring their Items are safe, clean, and fit for use. Renters assume all risks associated with the use of rented Items.

7. Limitation of Liability
To the fullest extent permitted by law, Culture Closet shall not be liable for any indirect, incidental, or consequential damages; loss or damage to Items during rental; or user interactions or transactions.

8. Intellectual Property
All content on the Platform, including logos, text, and graphics, is owned by Culture Closet or its licensors and is protected by intellectual property laws. You may not use, copy, or distribute any content without our prior written consent.

9. Termination
We may terminate or suspend your access to the Platform at any time, with or without notice, for any reason, including violation of these Terms.

10. Governing Law
These Terms are governed by the laws of Singapore. Any disputes arising from these Terms shall be resolved exclusively in the courts of Singapore.

11. Amendments
We reserve the right to modify these Terms at any time. Continued use of the Platform after changes constitutes acceptance of the revised Terms.

12. Contact Us
Support@culturecloset.site
TEXT;

        $privacy = <<<'TEXT'
Privacy Policy for Culture Closet Private Limited
Last Updated: 25th July 2025

12.1 Information We Collect
We collect the following types of information:
- Personal Information: Name, email address, phone number, Singpass/Apple ID/Google/Facebook login details, and payment information.
- Usage Data: Information about how you use the Platform, including IP address, device type, and browsing behavior.

12.2 How We Use Your Information
We use your information to:
- Provide, maintain, and improve the Platform.
- Process transactions and send transaction notifications.
- Verify user identity and prevent fraud.
- Communicate with you about updates, promotions, and customer support.

12.3 Sharing Your Information
We do not sell your personal information. We may share your information with:
- Service Providers: Third-party vendors who assist us in operating the Platform (e.g., Stripe for payment processing).
- Legal Authorities: When required by law or to protect our rights and safety.

12.4 Data Security
We implement industry-standard security measures to protect your information. However, no method of transmission over the internet or electronic storage is 100% secure.

12.5 Your Rights
You have the right to:
- Access, update, or delete your personal information.
- Opt-out of marketing communications.
- Withdraw consent for data processing (where applicable).

12.6 Cookies and Tracking
We use cookies and similar technologies to enhance your experience on the Platform. You can disable cookies in your browser settings, but this may affect Platform functionality.

12.7 Children's Privacy
The Platform is not intended for users under the age of 18. We do not knowingly collect personal information from children.

12.8 Contact Us
If you have any questions about our Privacy Policy, please contact us at: Support@culturecloset.site
TEXT;

        PlatformSetting::set('terms_and_conditions', $tac);
        PlatformSetting::set('privacy_policy', $privacy);
        PlatformSetting::set('platform_fee_percent', 10);
        PlatformSetting::set('service_fee_percent', 4);
        PlatformSetting::set('cleaning_fee', 5.90);
        PlatformSetting::set('late_fee_per_day', 50);
        PlatformSetting::set('coin_earn_rate', 500);
        PlatformSetting::set('coin_redeem_rate', 500);
        PlatformSetting::set('max_rental_days', 30);
        PlatformSetting::set('min_listing_price', 10);
    }
}
