<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system administrator with access to all features',
            ],
            [
                'name' => 'cashier',
                'display_name' => 'Cashier',
                'description' => 'Cashier role with access to POS and basic functions',
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Manager role with access to reports and user management',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
