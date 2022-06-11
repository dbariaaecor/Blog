<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //  \App\Models\User::factory(10)->create();

        //  \App\Models\User::factory()->create([
        //      'name' => 'Test User',
        //      'email' => 'test@example.com',
        //  ]);


        //  Role::insert([

        //      ['name'=>'superadmin','guard_name'=>'web'],
        //      ['name'=>'admin','guard_name'=>'web'],
        //  ]);

        //  Permission::insert([['name'=>'isaprove','guard_name'=>'web'],]);


            // $user = User::find(2);
            // $permission = Permission::find(1);
            // $user->givePermissionTo($permission->name);
    }
}
