<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'show user']);

        Permission::create(['name' => 'add product']);
        Permission::create(['name' => 'edit product']);
        Permission::create(['name' => 'delete product']);
        Permission::create(['name' => 'show product']);

        Permission::create(['name' => 'add category']);
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'delete category']);
        Permission::create(['name' => 'show category']);

        Permission::create(['name' => 'add unit']);
        Permission::create(['name' => 'edit unit']);
        Permission::create(['name' => 'delete unit']);
        Permission::create(['name' => 'show unit']);

        Permission::create(['name' => 'add role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);
        Permission::create(['name' => 'show role']);

        Permission::create(['name' => 'print export']);

        Permission::create(['name' => 'show sales']);
        Permission::create(['name' => 'show profits']);
        Permission::create(['name' => 'show capital']);
        Permission::create(['name' => 'show return items']);
        Permission::create(['name' => 'show voided items']);
        Permission::create(['name' => 'show voided transaction']);
        Permission::create(['name' => 'show point of sale']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'cashier']);
        $role->givePermissionTo('show point of sale');
    }
}
