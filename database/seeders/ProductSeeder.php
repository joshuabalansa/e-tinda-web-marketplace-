<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $farmer = User::where('role', 'farmer')->first();

        if (!$farmer) {
            $farmer = User::create([
                'name' => 'Sample Farmer',
                'email' => 'farmer@example.com',
                'password' => bcrypt('password'),
                'role' => 'farmer',
                'email_verified_at' => now(),
            ]);
        }

        $products = [
            // Vegetables
            [
                'name' => 'Fresh Tomatoes',
                'description' => 'Juicy, ripe tomatoes perfect for salads, cooking, and fresh eating.',
                'price_per_unit' => 45.00,
                'unit_type' => 'kg',
                'stock_quantity' => 50,
                'harvest_date' => Carbon::now()->subDays(2),
                'image_url' => 'products/tomatoes.jpg',
                'category' => 'Vegetables',
                'status' => 'available'
            ],
            [
                'name' => 'Crisp Lettuce',
                'description' => 'Fresh, crisp lettuce heads perfect for salads and sandwiches.',
                'price_per_unit' => 35.00,
                'unit_type' => 'piece',
                'stock_quantity' => 30,
                'harvest_date' => Carbon::now()->subDays(1),
                'image_url' => 'products/lettuce.jpg',
                'category' => 'Vegetables',
                'status' => 'available'
            ],
            [
                'name' => 'Sweet Bell Peppers',
                'description' => 'Colorful bell peppers in red, yellow, and green.',
                'price_per_unit' => 60.00,
                'unit_type' => 'kg',
                'stock_quantity' => 25,
                'harvest_date' => Carbon::now()->subDays(3),
                'image_url' => 'products/bell-peppers.jpg',
                'category' => 'Vegetables',
                'status' => 'available'
            ],
            [
                'name' => 'Fresh Carrots',
                'description' => 'Sweet, crunchy carrots rich in vitamins.',
                'price_per_unit' => 40.00,
                'unit_type' => 'kg',
                'stock_quantity' => 40,
                'harvest_date' => Carbon::now()->subDays(4),
                'image_url' => 'products/carrots.jpg',
                'category' => 'Vegetables',
                'status' => 'available'
            ],
            [
                'name' => 'Organic Spinach',
                'description' => 'Tender, organic spinach leaves packed with nutrients.',
                'price_per_unit' => 55.00,
                'unit_type' => 'bunch',
                'stock_quantity' => 20,
                'harvest_date' => Carbon::now()->subDays(1),
                'image_url' => 'products/spinach.jpg',
                'category' => 'Vegetables',
                'status' => 'out_of_stock'
            ],
            // Fruits
            [
                'name' => 'Sweet Mangoes',
                'description' => 'Juicy, sweet mangoes from our orchard.',
                'price_per_unit' => 80.00,
                'unit_type' => 'kg',
                'stock_quantity' => 35,
                'harvest_date' => Carbon::now()->subDays(5),
                'image_url' => 'products/mangoes.jpg',
                'category' => 'Fruits',
                'status' => 'available'
            ],
            [
                'name' => 'Fresh Bananas',
                'description' => 'Naturally ripened bananas, rich in potassium.',
                'price_per_unit' => 50.00,
                'unit_type' => 'bunch',
                'stock_quantity' => 15,
                'harvest_date' => Carbon::now()->subDays(3),
                'image_url' => 'products/bananas.jpg',
                'category' => 'Fruits',
                'status' => 'available'
            ],
            [
                'name' => 'Juicy Oranges',
                'description' => 'Sweet, juicy oranges packed with vitamin C.',
                'price_per_unit' => 65.00,
                'unit_type' => 'kg',
                'stock_quantity' => 28,
                'harvest_date' => Carbon::now()->subDays(4),
                'image_url' => 'products/oranges.jpg',
                'category' => 'Fruits',
                'status' => 'available'
            ],
            [
                'name' => 'Fresh Strawberries',
                'description' => 'Sweet, red strawberries perfect for desserts.',
                'price_per_unit' => 120.00,
                'unit_type' => 'box',
                'stock_quantity' => 12,
                'harvest_date' => Carbon::now()->subDays(2),
                'image_url' => 'products/strawberries.jpg',
                'category' => 'Fruits',
                'status' => 'available'
            ],
            [
                'name' => 'Ripe Pineapples',
                'description' => 'Sweet, tropical pineapples perfect for fresh eating.',
                'price_per_unit' => 90.00,
                'unit_type' => 'piece',
                'stock_quantity' => 8,
                'harvest_date' => Carbon::now()->subDays(6),
                'image_url' => 'products/pineapples.jpg',
                'category' => 'Fruits',
                'status' => 'out_of_stock'
            ],
            // Grains
            [
                'name' => 'Premium Rice',
                'description' => 'High-quality, locally grown rice.',
                'price_per_unit' => 45.00,
                'unit_type' => 'kg',
                'stock_quantity' => 100,
                'harvest_date' => Carbon::now()->subDays(30),
                'image_url' => 'products/rice.jpg',
                'category' => 'Grains',
                'status' => 'available'
            ],
            [
                'name' => 'Organic Quinoa',
                'description' => 'Nutritious quinoa grains, perfect for healthy meals.',
                'price_per_unit' => 180.00,
                'unit_type' => 'kg',
                'stock_quantity' => 15,
                'harvest_date' => Carbon::now()->subDays(45),
                'image_url' => 'products/quinoa.jpg',
                'category' => 'Grains',
                'status' => 'available'
            ],
            [
                'name' => 'Whole Wheat Flour',
                'description' => 'Freshly milled whole wheat flour perfect for baking.',
                'price_per_unit' => 35.00,
                'unit_type' => 'kg',
                'stock_quantity' => 25,
                'harvest_date' => Carbon::now()->subDays(15),
                'image_url' => 'products/wheat-flour.jpg',
                'category' => 'Grains',
                'status' => 'available'
            ],
            [
                'name' => 'Oats',
                'description' => 'Rolled oats perfect for breakfast and baking.',
                'price_per_unit' => 55.00,
                'unit_type' => 'kg',
                'stock_quantity' => 20,
                'harvest_date' => Carbon::now()->subDays(20),
                'image_url' => 'products/oats.jpg',
                'category' => 'Grains',
                'status' => 'available'
            ],
            // Dairy
            [
                'name' => 'Fresh Cow Milk',
                'description' => 'Fresh, pasteurized cow milk from our dairy farm.',
                'price_per_unit' => 65.00,
                'unit_type' => 'liter',
                'stock_quantity' => 40,
                'harvest_date' => Carbon::now()->subDays(1),
                'image_url' => 'products/milk.jpg',
                'category' => 'Dairy',
                'status' => 'available'
            ],
            [
                'name' => 'Farm Fresh Eggs',
                'description' => 'Fresh eggs from free-range chickens.',
                'price_per_unit' => 8.00,
                'unit_type' => 'piece',
                'stock_quantity' => 60,
                'harvest_date' => Carbon::now()->subDays(1),
                'image_url' => 'products/eggs.jpg',
                'category' => 'Dairy',
                'status' => 'available'
            ],
            [
                'name' => 'Artisan Cheese',
                'description' => 'Handcrafted cheese made from our farm milk.',
                'price_per_unit' => 150.00,
                'unit_type' => 'kg',
                'stock_quantity' => 10,
                'harvest_date' => Carbon::now()->subDays(7),
                'image_url' => 'products/cheese.jpg',
                'category' => 'Dairy',
                'status' => 'available'
            ],
            [
                'name' => 'Fresh Yogurt',
                'description' => 'Creamy, probiotic-rich yogurt made from our farm milk.',
                'price_per_unit' => 45.00,
                'unit_type' => 'container',
                'stock_quantity' => 25,
                'harvest_date' => Carbon::now()->subDays(2),
                'image_url' => 'products/yogurt.jpg',
                'category' => 'Dairy',
                'status' => 'out_of_stock'
            ],
            // Meat
            [
                'name' => 'Grass-Fed Beef',
                'description' => 'Premium grass-fed beef from our pasture-raised cattle.',
                'price_per_unit' => 350.00,
                'unit_type' => 'kg',
                'stock_quantity' => 15,
                'harvest_date' => Carbon::now()->subDays(3),
                'image_url' => 'products/beef.jpg',
                'category' => 'Meat',
                'status' => 'available'
            ],
            [
                'name' => 'Free-Range Chicken',
                'description' => 'Fresh, free-range chicken from our farm.',
                'price_per_unit' => 180.00,
                'unit_type' => 'kg',
                'stock_quantity' => 20,
                'harvest_date' => Carbon::now()->subDays(2),
                'image_url' => 'products/chicken.jpg',
                'category' => 'Meat',
                'status' => 'available'
            ],
            [
                'name' => 'Fresh Pork',
                'description' => 'Quality pork from our farm-raised pigs.',
                'price_per_unit' => 220.00,
                'unit_type' => 'kg',
                'stock_quantity' => 12,
                'harvest_date' => Carbon::now()->subDays(4),
                'image_url' => 'products/pork.jpg',
                'category' => 'Meat',
                'status' => 'available'
            ],
            [
                'name' => 'Fresh Fish',
                'description' => 'Fresh fish from our local waters.',
                'price_per_unit' => 280.00,
                'unit_type' => 'kg',
                'stock_quantity' => 8,
                'harvest_date' => Carbon::now()->subDays(1),
                'image_url' => 'products/fish.jpg',
                'category' => 'Meat',
                'status' => 'unavailable'
            ],
            // Other
            [
                'name' => 'Raw Honey',
                'description' => 'Pure, raw honey from our beehives.',
                'price_per_unit' => 200.00,
                'unit_type' => 'bottle',
                'stock_quantity' => 18,
                'harvest_date' => Carbon::now()->subDays(10),
                'image_url' => 'products/honey.jpg',
                'category' => 'Other',
                'status' => 'available'
            ],
            [
                'name' => 'Herbal Tea Mix',
                'description' => 'Dried herbs and flowers for making refreshing herbal teas.',
                'price_per_unit' => 75.00,
                'unit_type' => 'pack',
                'stock_quantity' => 30,
                'harvest_date' => Carbon::now()->subDays(5),
                'image_url' => 'products/herbal-tea.jpg',
                'category' => 'Other',
                'status' => 'available'
            ],
            [
                'name' => 'Dried Mushrooms',
                'description' => 'Premium dried mushrooms perfect for soups and stir-fries.',
                'price_per_unit' => 120.00,
                'unit_type' => 'pack',
                'stock_quantity' => 15,
                'harvest_date' => Carbon::now()->subDays(7),
                'image_url' => 'products/mushrooms.jpg',
                'category' => 'Other',
                'status' => 'available'
            ],
            [
                'name' => 'Organic Seeds',
                'description' => 'High-quality organic seeds for growing your own vegetables.',
                'price_per_unit' => 25.00,
                'unit_type' => 'packet',
                'stock_quantity' => 50,
                'harvest_date' => Carbon::now()->subDays(60),
                'image_url' => 'products/seeds.jpg',
                'category' => 'Other',
                'status' => 'available'
            ]
        ];

        foreach ($products as $product) {
            $product['user_id'] = $farmer->id;
            Product::create($product);
        }

        $this->command->info('Created ' . count($products) . ' products with realistic data and images for all categories.');
    }
}
