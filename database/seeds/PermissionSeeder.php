<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleSuperAdmin = Role::create(['name' => 'super-admin']);
        $roleAdmin = Role::create(['name' => 'admin']);

        $superAdmin = User::create(['id' => 1, 'employee_id' => 1, 'name' => 'superadmin', 'password' => 'admin', 'created_by' => 0, 'updated_by' => 0]);
        $superAdmin->assignRole('super-admin');

        $admin = User::create(['id' => 2, 'employee_id' => 2, 'name' => 'admin', 'password' => 'admin', 'created_by' => 0, 'updated_by' => 0]);
        $admin->assignRole('admin');

        $reza = User::create(['id' => 3, 'employee_id' => 61, 'name' => 'reza', 'password' => 'admin', 'created_by' => 0, 'updated_by' => 0]);
        $reza->assignRole('super-admin');

        $reza = User::create(['id' => 4, 'employee_id' => 58, 'name' => 'jefri', 'password' => 'admin', 'created_by' => 0, 'updated_by' => 0]);
        $reza->assignRole('super-admin');

        $reza = User::create(['id' => 5, 'employee_id' => 60, 'name' => 'jorgi', 'password' => 'admin', 'created_by' => 0, 'updated_by' => 0]);
        $reza->assignRole('super-admin');

        $permission = Permission::create(['name' => 'warehouse map']);

        $permission = Permission::create(['name' => 'transaction goods request']);
        $permission = Permission::create(['name' => 'transaction service request']);
        $permission = Permission::create(['name' => 'transaction goods borrow']);
        $permission = Permission::create(['name' => 'transaction purchase order']);
        $permission = Permission::create(['name' => 'transaction delivery order']);
        $permission = Permission::create(['name' => 'transaction stock opname']);

        $permission = Permission::create(['name' => 'report stock goods']);

        $permission = Permission::create(['name' => 'master item']);
        $permission = Permission::create(['name' => 'master category']);
        $permission = Permission::create(['name' => 'master unit']);
        $permission = Permission::create(['name' => 'master brand']);
        $permission = Permission::create(['name' => 'master machine']);
        $permission = Permission::create(['name' => 'master currency']);

        $permission = Permission::create(['name' => 'general employee']);
        $permission = Permission::create(['name' => 'general department']);
        $permission = Permission::create(['name' => 'general department level']);
        $permission = Permission::create(['name' => 'general country']);
        $permission = Permission::create(['name' => 'general menu']);

        $permission = Permission::create(['name' => 'authentication user']);
        $permission = Permission::create(['name' => 'authentication role']);
        $permission = Permission::create(['name' => 'authentication permission']);
        $permission = Permission::create(['name' => 'authentication assignment']);
    }
}
