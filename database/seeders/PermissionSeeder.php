<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
//        Permission::create(['name' => 'edit articles']);
//        Permission::create(['name' => 'delete articles']);
//        Permission::create(['name' => 'publish articles']);
//        Permission::create(['name' => 'unpublish articles']);

        // create roles and assign existing permissions
        $manager = Role::create(['name' => 'district-manager']);
        $purchaser = Role::create(['name' => 'purchase-manager']);
        $admin = Role::create(['name' => 'admin']);
        $ceo = Role::create(['name' => 'ceo']);


        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@email.com';
        $user->password = Hash::make('password');
        $user->save();

        $user->assignRole($admin);
    }
}
