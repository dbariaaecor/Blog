<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create Roles

        Role::insert([

            ['name'=>'superadmin','guard_name'=>'web'],
            ['name'=>'admin','guard_name'=>'web'],
       ]);
    }
}
