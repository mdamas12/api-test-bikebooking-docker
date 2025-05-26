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
        $role1 = Role::create(['name'=>'global']);
        $role2 = Role::create(['name'=>'administrator']);
        $role3 = Role::create(['name'=>'employee']);
        $role4 = Role::create(['name'=>'technician']);
        $role5 = Role::create(['name'=>'customer']);
 
 
       Permission::create(['name'=>'dash.global'])->syncRoles([$role1]);
       Permission::create(['name'=>'dash.administrator'])->syncRoles([$role2]);
       Permission::create(['name'=>'dash.employee'])->syncRoles([$role3]);
       Permission::create(['name'=>'dash.customer'])->syncRoles([$role3]);
    }
}