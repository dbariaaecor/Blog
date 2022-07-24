<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create Super Admin

        User::create([
            'name'=>'superadmin',
            'email'=>"superadmin@gmail.com",
            'password'=>Hash::make('password'),
        ]);

        //Assign Role and Permission to Super Admin
        $user = User::find(1);
        $permission = Permission::find(1);
        $role = Role::find(1);
        $user->roles()->attach($role);
        $user->givePermissionTo($permission->name);
    }
}
