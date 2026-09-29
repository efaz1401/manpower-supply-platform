<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Granular permissions grouped by module.
        $permissions = [
            'view executive dashboard',
            'view operations dashboard',
            'manage workers', 'view workers',
            'manage clients', 'view clients',
            'manage contracts',
            'manage deployments',
            'manage timesheets', 'approve timesheets',
            'manage payroll',
            'manage invoices', 'view invoices',
            'manage recruitment',
            'view compliance',
            'manage users',
            'view activity log',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Refresh the cache so freshly created permissions resolve by name.
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Role -> permission map.
        $roles = [
            'admin' => $permissions, // full access
            'manager' => [
                'view executive dashboard', 'view operations dashboard',
                'view workers', 'view clients', 'manage contracts',
                'manage deployments', 'approve timesheets', 'manage payroll',
                'manage invoices', 'view invoices', 'view compliance',
                'view activity log', 'view reports',
            ],
            'ops' => [
                'view operations dashboard',
                'manage workers', 'view workers', 'manage clients', 'view clients',
                'manage contracts', 'manage deployments', 'manage timesheets',
                'approve timesheets', 'manage payroll', 'manage invoices', 'view invoices',
                'manage recruitment', 'view compliance', 'view reports',
            ],
            'client' => ['view invoices', 'approve timesheets'],
            'worker' => [],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($rolePermissions);
        }
    }
}
