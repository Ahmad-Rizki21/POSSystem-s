<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Users Management
            ['name' => 'users.view', 'display_name' => 'View Users', 'description' => 'View users list and details', 'group' => 'Users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'description' => 'Create new users', 'group' => 'Users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'description' => 'Edit user information', 'group' => 'Users'],
            ['name' => 'users.delete',  'display_name' => 'Delete Users', 'description' => 'Delete users', 'group' => 'Users'],

            // Products Management
            ['name' => 'products.view', 'display_name' => 'View Products', 'description' => 'View products list and details', 'group' => 'Products'],
            ['name' => 'products.create', 'display_name' => 'Create Products', 'description' => 'Create new products', 'group' => 'Products'],
            ['name' => 'products.edit', 'display_name' => 'Edit Products', 'description' => 'Edit product information', 'group' => 'Products'],
            ['name' => 'products.delete', 'display_name' => 'Delete Products', 'description' => 'Delete products', 'group' => 'Products'],

            // Categories Management
            ['name' => 'categories.view', 'display_name' => 'View Categories', 'description' => 'View categories list and details', 'group' => 'Categories'],
            ['name' => 'categories.create', 'display_name' => 'Create Categories', 'description' => 'Create new categories', 'group' => 'Categories'],
            ['name' => 'categories.edit', 'display_name' => 'Edit Categories', 'description' => 'Edit category information', 'group' => 'Categories'],
            ['name' => 'categories.delete', 'display_name' => 'Delete Categories', 'description' => 'Delete categories', 'group' => 'Categories'],

            // Transactions
            ['name' => 'transactions.view', 'display_name' => 'View Transactions', 'description' => 'View transaction history', 'group' => 'Transactions'],
            ['name' => 'transactions.create', 'display_name' => 'Create Transactions', 'description' => 'Create new transactions (POS)', 'group' => 'Transactions'],
            ['name' => 'transactions.edit', 'display_name' => 'Edit Transactions', 'description' => 'Edit transaction information', 'group' => 'Transactions'],
            ['name' => 'transactions.delete', 'display_name' => 'Delete Transactions', 'description' => 'Delete transactions', 'group' => 'Transactions'],

            // Reports
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'description' => 'View sales and inventory reports', 'group' => 'Reports'],

            // Roles & Permissions Management
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'description' => 'View roles list and details', 'group' => 'Roles'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'description' => 'Create new roles', 'group' => 'Roles'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'description' => 'Edit role information and permissions', 'group' => 'Roles'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'description' => 'Delete roles', 'group' => 'Roles'],
            ['name' => 'permissions.view', 'display_name' => 'View Permissions', 'description' => 'View permissions list', 'group' => 'Roles'],

            // Stock Adjustment Management
            ['name' => 'stock-adjustment.view', 'display_name' => 'View Stock Adjustments', 'description' => 'View stock adjustment history', 'group' => 'Stock Management'],
            ['name' => 'stock-adjustment.create', 'display_name' => 'Create Stock Adjustments', 'description' => 'Create new stock adjustments', 'group' => 'Stock Management'],
            ['name' => 'stock-adjustment.edit', 'display_name' => 'Edit Stock Adjustments', 'description' => 'Edit stock adjustment information', 'group' => 'Stock Management'],
            ['name' => 'stock-adjustment.delete', 'display_name' => 'Delete Stock Adjustments', 'description' => 'Delete stock adjustments', 'group' => 'Stock Management'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }
    }
}
