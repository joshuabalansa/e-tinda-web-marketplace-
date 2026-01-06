<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user with default credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = 'admin2@example.com';
        $password = 'admin123';
        $name = 'Administrator';

        // Check if admin already exists
        $existingAdmin = User::where('email', $email)->first();

        if ($existingAdmin) {
            $this->error('An admin user with email ' . $email . ' already exists!');
            $this->info('User ID: ' . $existingAdmin->id);
            $this->info('Name: ' . $existingAdmin->name);
            $this->info('Role: ' . $existingAdmin->role->value);
            return 1;
        }

        // Create the admin user
        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => UserRole::Admin,
            'is_active' => true,
        ]);

        $this->info('Admin user created successfully!');
        $this->info('Email: ' . $email);
        $this->info('Password: ' . $password);
        $this->warn('Please change the password after first login for security purposes.');

        return 0;
    }
}

