<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed admin user
        $this->call(AdminSeeder::class);

        // Seed products with categories and multiple farmers
        $this->call(ProductSeeder::class);

        $this->command->info('Database seeding completed successfully!');
    }
}
