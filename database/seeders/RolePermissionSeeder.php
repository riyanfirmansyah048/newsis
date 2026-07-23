<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //membuat permision
        Permission::firstOrCreate(['name' => 'access-role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-role', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-permission', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-permission', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-permission', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-permission', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-permission', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-data', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-data', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-data', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-data', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-data', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-bppb', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-bppb', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-bppb', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-bppb', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-bppb', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-bppbmanual', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-bppbmanual', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-bppbmanual', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-bppbmanual', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-bppbmanual', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-vendor', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-vendor', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-vendor', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-vendor', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-vendor', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-domainemail', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-domainemail', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-domainemail', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-domainemail', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-domainemail', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-email', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-email', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-email', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-email', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-email', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-internet', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-internet', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-internet', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-internet', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-internet', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-item', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-item', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-item', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-item', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-item', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-po', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-po', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-po', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-po', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-po', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-bpb', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-bpb', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-bpb', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-bpb', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-bpb', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-service', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-service', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-service', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-service', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-service', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-expedition', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-expedition', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-expedition', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-expedition', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-expedition', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-user', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-user', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-user', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-user', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-user', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-bppb-software', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-bppb-software', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-bppb-software', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-bppb-software', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-bppb-software', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-all-service', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-all-service', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-all-service', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-all-service', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-all-service', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'access-all-by-region', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create-all-by-region', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'read-all-by-region', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update-all-by-region', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete-all-by-region', 'guard_name' => 'web']);

        // membuat role
        Role::firstOrCreate(['id' => 2, 'name' => 'admin', 'guard_name' => 'web']); //Super User
        Role::firstOrCreate(['id' => 4, 'name' => 'guest', 'guard_name' => 'web']); //Bebas
        Role::firstOrCreate(['id' => 5, 'name' => 'user', 'guard_name' => 'web']); //User
        Role::firstOrCreate(['name' => 'it', 'guard_name' => 'web']); //IT

        $roleAdmin = Role::findByName('admin');
        $roleGuest = Role::findByName('guest');
        $roleUser = Role::findByName('user');
        $roleIT = Role::findByName('it');

        // start admin_______________________________________________________
        $roleAdmin->syncPermissions([
            'access-role',
            'create-role',
            'read-role',
            'update-role',
            'delete-role',

            'access-permission',
            'create-permission',
            'read-permission',
            'update-permission',
            'delete-permission',

            'access-data',
            'create-data',
            'read-data',
            'update-data',
            'delete-data',

            'access-bppb',
            'create-bppb',
            'read-bppb',
            'update-bppb',
            'delete-bppb',

            'access-bppbmanual',
            'create-bppbmanual',
            'read-bppbmanual',
            'update-bppbmanual',
            'delete-bppbmanual',

            'access-vendor',
            'create-vendor',
            'read-vendor',
            'update-vendor',
            'delete-vendor',

            'access-domainemail',
            'create-domainemail',
            'read-domainemail',
            'update-domainemail',
            'delete-domainemail',

            'access-email',
            'create-email',
            'read-email',
            'update-email',
            'delete-email',

            'access-internet',
            'create-internet',
            'read-internet',
            'update-internet',
            'delete-internet',

            'access-item',
            'create-item',
            'read-item',
            'update-item',
            'delete-item',

            'access-po',
            'create-po',
            'read-po',
            'update-po',
            'delete-po',

            'access-bpb',
            'create-bpb',
            'read-bpb',
            'update-bpb',
            'delete-bpb',

            'access-service',
            'create-service',
            'read-service',
            'update-service',
            'delete-service',

            'access-expedition',
            'create-expedition',
            'read-expedition',
            'update-expedition',
            'delete-expedition',

            'access-user',
            'create-user',
            'read-user',
            'update-user',
            'delete-user',

            'access-bppb-software',
            'create-bppb-software',
            'read-bppb-software',
            'update-bppb-software',
            'delete-bppb-software',

            'access-all-service',
            'create-all-service',
            'read-all-service',
            'update-all-service',
            'delete-all-service',
        ]);
        // end admin_________________________________________________________
        $roleUser->syncPermissions([
            'access-email',
            'create-email',
            'read-email',

            'access-internet',
            'create-internet',
            'read-internet',

            'access-bppb',
            'create-bppb',
            'read-bppb',
            'update-bppb',
            'delete-bppb',

            'access-po',
            'read-po',

            'access-bpb',
            'read-bpb',

            'access-service',
            'create-service',
            'read-service',
        ]);
    }
}
