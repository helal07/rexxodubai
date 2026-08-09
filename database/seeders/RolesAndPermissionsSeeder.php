<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions grouped by module
        $modules = [
            'dashboard'   => ['view dashboard'],
            'orders'      => ['view orders', 'create orders', 'edit orders', 'delete orders'],
            'products'    => ['view products', 'create products', 'edit products', 'delete products'],
            'categories'  => ['view categories', 'create categories', 'edit categories', 'delete categories'],
            'purchases'   => ['view purchases', 'create purchases', 'edit purchases', 'delete purchases'],
            'customers'   => ['view customers', 'create customers', 'edit customers', 'delete customers'],
            'suppliers'   => ['view suppliers', 'create suppliers', 'edit suppliers', 'delete suppliers'],
            'menus'       => ['view menus', 'create menus', 'edit menus', 'delete menus'],
            'courier'     => ['view courier', 'edit courier'],
            'api-settings'=> ['view api-settings', 'edit api-settings'],
            'seo-settings'=> ['view seo-settings', 'edit seo-settings'],
            'settings'    => ['view settings', 'edit settings'],
            'users'       => ['view users', 'create users', 'edit users', 'delete users'],
            'roles'       => ['view roles', 'create roles', 'edit roles', 'delete roles'],
        ];

        // Create all permissions
        foreach ($modules as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            }
        }

        // Create roles and assign permissions
        // Super Admin gets ALL permissions automatically via Gate::before in AuthServiceProvider
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        // Admin — everything except role management
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminPermissions = collect($modules)
            ->except(['roles'])
            ->flatten()
            ->toArray();
        $admin->syncPermissions($adminPermissions);

        // Manager — operational modules
        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $managerPermissions = collect($modules)
            ->only(['dashboard', 'orders', 'products', 'categories', 'purchases', 'customers', 'suppliers'])
            ->flatten()
            ->toArray();
        $manager->syncPermissions($managerPermissions);

        // Staff — limited access
        $staff = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);
        $staffPermissions = [
            'view dashboard',
            'view orders', 'create orders',
            'view products',
            'view customers',
        ];
        $staff->syncPermissions($staffPermissions);

        // Assign Super Admin to the first user (current admin)
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasRole('Super Admin')) {
            $firstUser->assignRole('Super Admin');
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
