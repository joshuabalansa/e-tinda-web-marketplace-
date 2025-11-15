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
        // Create multiple farmers for variety
        $farmers = $this->createFarmers();

        // Define Filipino vegetables
        $vegetables = $this->getFilipinoVegetables();

        $totalProducts = 0;

        foreach ($vegetables as $vegetableData) {
            // Assign random farmer to each product
            $farmer = $farmers[array_rand($farmers)];
            $vegetableData['user_id'] = $farmer->id;
            $vegetableData['category'] = 'Vegetables';

            Product::create($vegetableData);
            $totalProducts++;
        }

        $this->command->info("Created {$totalProducts} Filipino vegetables with " . count($farmers) . " farmers.");
    }

    private function createFarmers(): array
    {
        $farmers = [];

        $farmerData = [
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@farm.com',
                'role' => 'farmer'
            ],
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@farm.com',
                'role' => 'farmer'
            ],
            [
                'name' => 'Ana Rodriguez',
                'email' => 'ana.rodriguez@farm.com',
                'role' => 'farmer'
            ],
            [
                'name' => 'Carlos Mendoza',
                'email' => 'carlos.mendoza@farm.com',
                'role' => 'farmer'
            ],
            [
                'name' => 'Elena Garcia',
                'email' => 'elena.garcia@farm.com',
                'role' => 'farmer'
            ]
        ];

        foreach ($farmerData as $data) {
            $farmer = User::where('email', $data['email'])->first();
            if (!$farmer) {
                $farmer = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt('password'),
                    'role' => $data['role'],
                    'email_verified_at' => now(),
                ]);
            }
            $farmers[] = $farmer;
        }

        return $farmers;
    }

    private function getFilipinoVegetables(): array
    {
        return [
            [
                'name' => 'Kamote (Sweet Potato)',
                'description' => 'Fresh kamote tubers, rich in fiber and vitamins. Perfect for boiling, roasting, or making desserts.',
                'price_per_unit' => 35.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(20, 60),
                'harvest_date' => Carbon::now()->subDays(rand(1, 5)),
                'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Talong (Eggplant)',
                'description' => 'Fresh purple eggplants perfect for pinakbet, tortang talong, and other Filipino dishes.',
                'price_per_unit' => 40.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(15, 45),
                'harvest_date' => Carbon::now()->subDays(rand(1, 4)),
                'image_url' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Okra',
                'description' => 'Fresh okra pods perfect for sinigang, pinakbet, and other Filipino stews.',
                'price_per_unit' => 45.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(10, 35),
                'harvest_date' => Carbon::now()->subDays(rand(1, 3)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Ampalaya (Bitter Gourd)',
                'description' => 'Fresh ampalaya perfect for ginataang ampalaya and other Filipino dishes.',
                'price_per_unit' => 50.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(8, 25),
                'harvest_date' => Carbon::now()->subDays(rand(2, 5)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Kalabasa (Squash)',
                'description' => 'Fresh kalabasa perfect for ginataang kalabasa and other Filipino recipes.',
                'price_per_unit' => 30.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(12, 40),
                'harvest_date' => Carbon::now()->subDays(rand(2, 6)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Sitaw (String Beans)',
                'description' => 'Fresh sitaw perfect for pinakbet, adobong sitaw, and other Filipino dishes.',
                'price_per_unit' => 35.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(15, 50),
                'harvest_date' => Carbon::now()->subDays(rand(1, 3)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Kangkong (Water Spinach)',
                'description' => 'Fresh kangkong leaves perfect for adobong kangkong and other Filipino recipes.',
                'price_per_unit' => 25.00,
                'unit_type' => 'bunch',
                'stock_quantity' => rand(20, 60),
                'harvest_date' => Carbon::now()->subDays(rand(1, 2)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Pechay (Chinese Cabbage)',
                'description' => 'Fresh pechay perfect for soups, stir-fries, and Filipino dishes.',
                'price_per_unit' => 30.00,
                'unit_type' => 'bunch',
                'stock_quantity' => rand(18, 45),
                'harvest_date' => Carbon::now()->subDays(rand(1, 3)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Repolyo (Cabbage)',
                'description' => 'Fresh cabbage perfect for lumpia, salads, and Filipino dishes.',
                'price_per_unit' => 40.00,
                'unit_type' => 'piece',
                'stock_quantity' => rand(10, 30),
                'harvest_date' => Carbon::now()->subDays(rand(2, 5)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Carrots',
                'description' => 'Fresh carrots perfect for Filipino dishes, soups, and salads.',
                'price_per_unit' => 45.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(15, 40),
                'harvest_date' => Carbon::now()->subDays(rand(2, 6)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Patatas (Potatoes)',
                'description' => 'Fresh potatoes perfect for Filipino dishes, fries, and various recipes.',
                'price_per_unit' => 35.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(20, 55),
                'harvest_date' => Carbon::now()->subDays(rand(3, 8)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Sibuyas (Onions)',
                'description' => 'Fresh red onions essential for Filipino cooking and flavoring dishes.',
                'price_per_unit' => 60.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(25, 70),
                'harvest_date' => Carbon::now()->subDays(rand(5, 15)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Bawang (Garlic)',
                'description' => 'Fresh garlic cloves essential for Filipino cooking and seasoning.',
                'price_per_unit' => 80.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(15, 40),
                'harvest_date' => Carbon::now()->subDays(rand(7, 20)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Kamatis (Tomatoes)',
                'description' => 'Fresh red tomatoes perfect for Filipino dishes, salads, and sauces.',
                'price_per_unit' => 50.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(12, 35),
                'harvest_date' => Carbon::now()->subDays(rand(1, 4)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Labanos (Radish)',
                'description' => 'Fresh white radish perfect for Filipino dishes and pickling.',
                'price_per_unit' => 25.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(10, 30),
                'harvest_date' => Carbon::now()->subDays(rand(2, 5)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Mustasa (Mustard Greens)',
                'description' => 'Fresh mustard greens perfect for Filipino dishes and soups.',
                'price_per_unit' => 20.00,
                'unit_type' => 'bunch',
                'stock_quantity' => rand(15, 40),
                'harvest_date' => Carbon::now()->subDays(rand(1, 3)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Alugbati (Malabar Spinach)',
                'description' => 'Fresh alugbati leaves perfect for Filipino dishes and soups.',
                'price_per_unit' => 22.00,
                'unit_type' => 'bunch',
                'stock_quantity' => rand(12, 35),
                'harvest_date' => Carbon::now()->subDays(rand(1, 3)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Talong (Eggplant) - Long Variety',
                'description' => 'Long variety eggplant perfect for tortang talong and other Filipino dishes.',
                'price_per_unit' => 45.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(8, 25),
                'harvest_date' => Carbon::now()->subDays(rand(1, 4)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => rand(0, 1) ? 'available' : 'out_of_stock'
            ],
            [
                'name' => 'Upo (Bottle Gourd)',
                'description' => 'Fresh upo perfect for ginataang upo and other Filipino dishes.',
                'price_per_unit' => 35.00,
                'unit_type' => 'piece',
                'stock_quantity' => rand(6, 20),
                'harvest_date' => Carbon::now()->subDays(rand(2, 5)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Patola (Sponge Gourd)',
                'description' => 'Fresh patola perfect for Filipino soups and dishes.',
                'price_per_unit' => 30.00,
                'unit_type' => 'piece',
                'stock_quantity' => rand(8, 25),
                'harvest_date' => Carbon::now()->subDays(rand(1, 4)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Sayote (Chayote)',
                'description' => 'Fresh sayote perfect for Filipino dishes and stir-fries.',
                'price_per_unit' => 25.00,
                'unit_type' => 'piece',
                'stock_quantity' => rand(10, 30),
                'harvest_date' => Carbon::now()->subDays(rand(2, 6)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Baguio Beans (Green Beans)',
                'description' => 'Fresh Baguio beans perfect for Filipino dishes and stir-fries.',
                'price_per_unit' => 40.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(12, 35),
                'harvest_date' => Carbon::now()->subDays(rand(1, 3)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Lettuce',
                'description' => 'Fresh lettuce perfect for Filipino salads and dishes.',
                'price_per_unit' => 35.00,
                'unit_type' => 'piece',
                'stock_quantity' => rand(15, 40),
                'harvest_date' => Carbon::now()->subDays(rand(1, 3)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Cucumber',
                'description' => 'Fresh cucumber perfect for Filipino salads and dishes.',
                'price_per_unit' => 30.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(10, 30),
                'harvest_date' => Carbon::now()->subDays(rand(1, 3)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Bell Pepper (Red)',
                'description' => 'Fresh red bell pepper perfect for Filipino dishes and stir-fries.',
                'price_per_unit' => 80.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(8, 20),
                'harvest_date' => Carbon::now()->subDays(rand(2, 5)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Bell Pepper (Green)',
                'description' => 'Fresh green bell pepper perfect for Filipino dishes and stir-fries.',
                'price_per_unit' => 70.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(10, 25),
                'harvest_date' => Carbon::now()->subDays(rand(2, 5)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Broccoli',
                'description' => 'Fresh broccoli perfect for Filipino dishes and stir-fries.',
                'price_per_unit' => 60.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(8, 20),
                'harvest_date' => Carbon::now()->subDays(rand(2, 5)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Cauliflower',
                'description' => 'Fresh cauliflower perfect for Filipino dishes and stir-fries.',
                'price_per_unit' => 55.00,
                'unit_type' => 'piece',
                'stock_quantity' => rand(6, 18),
                'harvest_date' => Carbon::now()->subDays(rand(2, 5)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Corn (Sweet Corn)',
                'description' => 'Fresh sweet corn perfect for Filipino dishes and grilling.',
                'price_per_unit' => 25.00,
                'unit_type' => 'piece',
                'stock_quantity' => rand(20, 50),
                'harvest_date' => Carbon::now()->subDays(rand(1, 4)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Ginger (Luya)',
                'description' => 'Fresh ginger root essential for Filipino cooking and flavoring.',
                'price_per_unit' => 120.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(10, 30),
                'harvest_date' => Carbon::now()->subDays(rand(5, 15)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ],
            [
                'name' => 'Turmeric (Luyang Dilaw)',
                'description' => 'Fresh turmeric root perfect for Filipino dishes and health benefits.',
                'price_per_unit' => 100.00,
                'unit_type' => 'kg',
                'stock_quantity' => rand(8, 25),
                'harvest_date' => Carbon::now()->subDays(rand(5, 15)),
                'image_url' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80',
                'status' => 'available'
            ]
        ];
    }
}