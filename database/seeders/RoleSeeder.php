<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Loan permissions
            'view_loans',
            'create_loans',
            'edit_loans',
            'delete_loans',
            'approve_loans',
            'reject_loans',
            'disburse_loans',

            // Transaction permissions
            'view_transactions',
            'create_transactions',
            'edit_transactions',
            'delete_transactions',

            // User permissions
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Report permissions
            'view_reports',
            'export_reports',

            // Admin permissions
            'manage_roles',
            'manage_permissions',
            'view_audit_logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo([
            'view_loans',
            'create_loans',
            'view_transactions',
            'create_transactions',
        ]);

        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'view_loans',
            'create_loans',
            'edit_loans',
            'approve_loans',
            'reject_loans',
            'disburse_loans',
            'view_transactions',
            'create_transactions',
            'edit_transactions',
            'view_users',
            'view_reports',
            'export_reports',
        ]);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
    }
}
