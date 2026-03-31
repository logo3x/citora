<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $resources = ['business', 'employee', 'service', 'appointment', 'business_schedule'];

        $actions = [
            'view_any',
            'view',
            'create',
            'update',
            'delete',
            'restore',
            'force_delete',
        ];

        $permissions = [];
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = "{$action}_{$resource}";
            }
        }

        // Shield panel permissions
        $permissions[] = 'view_role';
        $permissions[] = 'view_any_role';
        $permissions[] = 'create_role';
        $permissions[] = 'update_role';
        $permissions[] = 'delete_role';

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // super_admin: all permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // business_owner: full CRUD on their business resources
        $businessOwner = Role::firstOrCreate(['name' => 'business_owner', 'guard_name' => 'web']);
        $businessOwner->givePermissionTo([
            'view_any_business', 'view_business', 'update_business',
            'view_any_employee', 'view_employee', 'create_employee', 'update_employee', 'delete_employee',
            'view_any_service', 'view_service', 'create_service', 'update_service', 'delete_service',
            'view_any_appointment', 'view_appointment', 'create_appointment', 'update_appointment', 'delete_appointment',
        ]);

        // customer: view services, manage own appointments, create first business
        $customer = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $customer->givePermissionTo([
            'view_any_business', 'create_business',
            'view_any_service', 'view_service',
            'view_any_appointment', 'view_appointment', 'create_appointment',
        ]);

        // Create panel_user role for Shield panel access
        Role::firstOrCreate(['name' => 'panel_user', 'guard_name' => 'web']);
    }
}
