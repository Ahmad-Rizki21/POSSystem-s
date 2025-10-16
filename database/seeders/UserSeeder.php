<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'ahmad@ajnusa.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Cashier users

        User::create([
            'name' => 'Sarah Cashier',
            'email' => 'sarah@pos.com',
            'password' => Hash::make('cashier123'),
            'role' => 'cashier',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Users created successfully!');
        $this->command->info('📧 Admin: ahmad@ajnusa.com / password');
        $this->command->info('👤 Cashier 2: sarah@pos.com / cashier123');
    }
}