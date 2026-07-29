<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users    = User::where('is_admin', false)->pluck('id')->toArray();
        $products = Product::where('status', 'approved')->get();

        if ($products->isEmpty() || count($users) < 2) {
            $this->command->warn('Need at least 2 users and 1 approved product.');
            return;
        }

        $statuses = ['completed', 'completed', 'completed', 'active', 'active', 'pending', 'disputed', 'cancelled'];

        foreach ($products->take(12) as $i => $product) {
            $borrowerId = $users[array_rand($users)];
            $lenderId   = $product->user_id;
            if ($borrowerId === $lenderId) {
                $lenderId = $users[($i + 1) % count($users)];
            }

            $days      = rand(2, 7);
            $startDate = now()->subDays(rand(5, 60));
            $status    = $statuses[$i % count($statuses)];
            $subtotal  = $product->price_per_day * $days;
            $fee       = round($subtotal * 0.15, 2);
            $total     = $subtotal + $fee;

            $order = Order::create([
                'borrower_id'  => $borrowerId,
                'lender_id'    => $lenderId,
                'product_id'   => $product->id,
                'start_date'   => $startDate,
                'end_date'     => $startDate->copy()->addDays($days),
                'days'         => $days,
                'price_per_day'=> $product->price_per_day,
                'subtotal'     => $subtotal,
                'platform_fee' => $fee,
                'total'        => $total,
                'status'       => $status,
                'created_at'   => $startDate->copy()->subDays(2),
            ]);

            // Transactions for completed orders
            if ($status === 'completed') {
                Transaction::create([
                    'order_id'  => $order->id,
                    'user_id'   => $borrowerId,
                    'type'      => 'payment',
                    'amount'    => $total,
                    'status'    => 'completed',
                    'reference' => 'PAY-' . strtoupper(substr(md5($order->id), 0, 8)),
                    'created_at'=> $order->created_at,
                ]);
                Transaction::create([
                    'order_id'  => $order->id,
                    'user_id'   => $lenderId,
                    'type'      => 'payout',
                    'amount'    => $subtotal - $fee,
                    'status'    => 'completed',
                    'reference' => 'PAY-OUT-' . strtoupper(substr(md5($order->id.'p'), 0, 8)),
                    'created_at'=> $order->created_at->addDays($days + 2),
                ]);
                Transaction::create([
                    'order_id'  => $order->id,
                    'user_id'   => $borrowerId,
                    'type'      => 'platform_fee',
                    'amount'    => $fee,
                    'status'    => 'completed',
                    'created_at'=> $order->created_at,
                ]);

                // Reviews for completed orders
                Review::create([
                    'order_id'    => $order->id,
                    'reviewer_id' => $borrowerId,
                    'reviewee_id' => $lenderId,
                    'product_id'  => $product->id,
                    'rating'      => rand(3, 5),
                    'body'        => collect(['Gorgeous piece, exactly as described!', 'Loved renting this, would definitely rent again.', 'Great condition and smooth process.', 'Beautiful item, very fast communication.', 'Perfect for my event, highly recommend.'])->random(),
                    'status'      => 'approved',
                    'created_at'  => $order->created_at->addDays($days + 1),
                ]);
            }
        }
    }
}
