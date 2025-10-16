<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting POS System Database Seeding...');

        // Run seeders in order
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        $this->command->info('✅ POS System Database Seeding Completed!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   • Permissions: ' . \App\Models\Permission::count() . ' permissions created');
        $this->command->info('   • Roles: ' . \App\Models\Role::count() . ' roles created');
        $this->command->info('   • Users: ' . \App\Models\User::count() . ' users created');
        $this->command->info('');
        $this->command->info('👤 Default Login Credentials:');
        $this->command->info('   • Admin: ahmad@ajnusa.com / password');
        $this->command->info('   • Cashier: sarah@pos.com / cashier123');
    }
}
