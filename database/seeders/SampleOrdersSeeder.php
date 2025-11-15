<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class SampleOrdersSeeder extends Seeder
{
    public function run()
    {
        // Get a farmer and their products
        $farmer = User::where('role', 'farmer')->where('id', 2)->first();
        if (!$farmer) {
            $this->command->info('No farmer found. Please run the database seeder first.');
            return;
        }

        $products = Product::where('user_id', $farmer->id)->take(5)->get();
        if ($products->isEmpty()) {
            $this->command->info('No products found for farmer. Please run the product seeder first.');
            return;
        }

        // Create sample orders for the last 3 months
        $statuses = ['pending', 'processing', 'completed', 'shipped'];

        for ($i = 0; $i < 20; $i++) {
            // Create orders in the last 3 months
            $orderDate = Carbon::now()->subDays(rand(1, 90));

            $order = Order::create([
                'user_id' => $farmer->id, // Using farmer as buyer for demo
                'first_name' => 'Sample',
                'last_name' => 'Customer',
                'email' => 'customer@example.com',
                'phone' => '1234567890',
                'address' => '123 Sample Street',
                'city' => 'Sample City',
                'state' => 'Sample State',
                'zip' => '12345',
                'subtotal' => 0,
                'shipping' => 0,
                'total' => 0,
                'status' => $statuses[array_rand($statuses)],
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Create 1-3 order items per order
            $itemCount = rand(1, 3);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 5);
                $price = $product->price_per_unit;
                $itemTotal = $price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $subtotal += $itemTotal;
            }

            // Update order totals
            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);
        }

        $this->command->info('Created 20 sample orders with order items.');
    }
}
