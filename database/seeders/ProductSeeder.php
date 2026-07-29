<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_admin', false)->pluck('id')->toArray();

        if (empty($users)) {
            $this->command->warn('No non-admin users found. Run DatabaseSeeder first.');
            return;
        }

        $listings = [
            ['name' => 'Valentino Rockstud Dress',       'brand' => 'Valentino',     'category' => 'Dresses',    'size' => 'S',  'condition' => 'excellent', 'price' => 180, 'deposit' => 500,  'status' => 'approved',  'featured' => true],
            ['name' => 'Zimmermann Floral Midi',          'brand' => 'Zimmermann',    'category' => 'Dresses',    'size' => 'M',  'condition' => 'excellent', 'price' => 120, 'deposit' => 350,  'status' => 'approved',  'featured' => true],
            ['name' => 'Chanel Tweed Jacket',             'brand' => 'Chanel',        'category' => 'Tops',       'size' => 'S',  'condition' => 'good',      'price' => 250, 'deposit' => 800,  'status' => 'approved',  'featured' => false],
            ['name' => 'Self-Portrait Lace Midi',         'brand' => 'Self-Portrait', 'category' => 'Dresses',    'size' => 'XS', 'condition' => 'excellent', 'price' => 95,  'deposit' => 280,  'status' => 'pending',   'featured' => false],
            ['name' => 'Gucci GG Canvas Belt Bag',        'brand' => 'Gucci',         'category' => 'Bags',       'size' => null, 'condition' => 'excellent', 'price' => 110, 'deposit' => 600,  'status' => 'pending',   'featured' => false],
            ['name' => 'Jacquemus Le Chiquito Bag',       'brand' => 'Jacquemus',     'category' => 'Bags',       'size' => null, 'condition' => 'good',      'price' => 75,  'deposit' => 300,  'status' => 'approved',  'featured' => false],
            ['name' => 'Bottega Veneta Pouch Clutch',     'brand' => 'Bottega Veneta','category' => 'Bags',       'size' => null, 'condition' => 'excellent', 'price' => 130, 'deposit' => 550,  'status' => 'pending',   'featured' => false],
            ['name' => 'Alexander McQueen Blazer',        'brand' => 'Alexander McQueen','category' => 'Tops',   'size' => 'M',  'condition' => 'good',      'price' => 160, 'deposit' => 420,  'status' => 'rejected',  'featured' => false],
            ['name' => 'Prada Re-Edition 2005 Bag',       'brand' => 'Prada',         'category' => 'Bags',       'size' => null, 'condition' => 'excellent', 'price' => 145, 'deposit' => 700,  'status' => 'approved',  'featured' => true],
            ['name' => 'Rixo Gypsy Maxi Dress',          'brand' => 'Rixo',          'category' => 'Dresses',    'size' => 'L',  'condition' => 'fair',      'price' => 55,  'deposit' => 150,  'status' => 'approved',  'featured' => false],
            ['name' => 'Hermes Oran Sandals',             'brand' => 'Hermès',        'category' => 'Shoes',      'size' => '38', 'condition' => 'excellent', 'price' => 90,  'deposit' => 400,  'status' => 'pending',   'featured' => false],
            ['name' => 'Magda Butrym Floral Slip Dress',  'brand' => 'Magda Butrym',  'category' => 'Dresses',    'size' => 'S',  'condition' => 'excellent', 'price' => 105, 'deposit' => 300,  'status' => 'approved',  'featured' => false],
            ['name' => 'Saint Laurent Loulou Bag',        'brand' => 'Saint Laurent', 'category' => 'Bags',       'size' => null, 'condition' => 'good',      'price' => 120, 'deposit' => 650,  'status' => 'rejected',  'featured' => false],
            ['name' => 'Versace Medusa Bodycon Dress',    'brand' => 'Versace',       'category' => 'Dresses',    'size' => 'M',  'condition' => 'excellent', 'price' => 200, 'deposit' => 600,  'status' => 'pending',   'featured' => false],
            ['name' => 'Fendi Baguette Bag',              'brand' => 'Fendi',         'category' => 'Bags',       'size' => null, 'condition' => 'excellent', 'price' => 135, 'deposit' => 700,  'status' => 'approved',  'featured' => true],
        ];

        foreach ($listings as $i => $item) {
            Product::updateOrCreate(
                ['name' => $item['name']],
                [
                    'user_id'      => $users[$i % count($users)],
                    'brand'        => $item['brand'],
                    'category'     => $item['category'],
                    'size'         => $item['size'],
                    'condition'    => $item['condition'],
                    'price_per_day'=> $item['price'],
                    'deposit'      => $item['deposit'],
                    'status'       => $item['status'],
                    'is_featured'  => $item['featured'],
                    'description'  => "Beautiful {$item['brand']} {$item['name']} available for rent. Perfect for special occasions and events.",
                    'reject_reason'=> $item['status'] === 'rejected' ? 'Photos do not meet quality guidelines.' : null,
                    'created_at'   => now()->subDays(rand(1, 90)),
                ]
            );
        }
    }
}
