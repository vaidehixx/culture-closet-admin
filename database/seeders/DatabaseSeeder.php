<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin account
        User::updateOrCreate(
            ['email' => 'vai@culturecloset.site'],
            [
                'name'               => 'Vai',
                'password'           => \Illuminate\Support\Facades\Hash::make('admin1234'),
                'is_admin'           => true,
                'email_verified_at'  => now(),
            ]
        );

        // Test members
        $members = [
            ['name' => 'Sara Al Mansoori',  'email' => 'sara@example.com',    'verified' => true,  'suspended' => false],
            ['name' => 'Layla Hassan',      'email' => 'layla@example.com',   'verified' => true,  'suspended' => false],
            ['name' => 'Noura Khalid',      'email' => 'noura@example.com',   'verified' => false, 'suspended' => false],
            ['name' => 'Fatima Al Zaabi',   'email' => 'fatima@example.com',  'verified' => true,  'suspended' => true],
            ['name' => 'Hessa Al Blooshi',  'email' => 'hessa@example.com',   'verified' => true,  'suspended' => false],
            ['name' => 'Mariam Juma',       'email' => 'mariam@example.com',  'verified' => false, 'suspended' => false],
            ['name' => 'Shaikha Obaid',     'email' => 'shaikha@example.com', 'verified' => true,  'suspended' => false],
            ['name' => 'Aisha Al Nuaimi',   'email' => 'aisha@example.com',   'verified' => true,  'suspended' => false],
            ['name' => 'Reem Al Shamsi',    'email' => 'reem@example.com',    'verified' => false, 'suspended' => true],
            ['name' => 'Dana Al Maktoum',   'email' => 'dana@example.com',    'verified' => true,  'suspended' => false],
        ];

        foreach ($members as $member) {
            User::updateOrCreate(
                ['email' => $member['email']],
                [
                    'name'              => $member['name'],
                    'password'          => \Illuminate\Support\Facades\Hash::make('password'),
                    'is_admin'          => false,
                    'is_suspended'      => $member['suspended'],
                    'email_verified_at' => $member['verified'] ? now()->subDays(rand(1, 60)) : null,
                    'created_at'        => now()->subDays(rand(5, 120)),
                ]
            );
        }

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            PromoCodeSeeder::class,
            OrderSeeder::class,
            ReportSeeder::class,
            FaqSeeder::class,
            LegalContentSeeder::class,
        ]);
    }
}
