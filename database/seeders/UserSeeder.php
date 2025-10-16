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
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        if ($adminRole) {
            User::create([
                'name' => 'Administrator',
                'email' => 'ahmad@ajnusa.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]);
        } else {
            $this->command->error('Admin role not found!');
        }

        // Cashier users
        $cashierRole = \App\Models\Role::where('name', 'cashier')->first();
        if ($cashierRole) {
            User::create([
                'name' => 'Sarah Cashier',
                'email' => 'sarah@pos.com',
                'password' => Hash::make('cashier123'),
                'role_id' => $cashierRole->id,
                'email_verified_at' => now(),
            ]);
        } else {
            $this->command->error('Cashier role not found!');
        }

        $this->command->info('✅ Users created successfully!');
        $this->command->info('📧 Admin: ahmad@ajnusa.com / password');
        $this->command->info('👤 Cashier 2: sarah@pos.com / cashier123');
    }
}