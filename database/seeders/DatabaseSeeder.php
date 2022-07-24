<?php

namespace Database\Seeders;

use App\Models\tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //call All Sedder in Right Order
        $this->call([
            RoleTableSeeder::class,
            PermissionTableSeeder::class,
            TagTableSeeder::class,
            UserTableSeeder::class,
        ]);
    }
}
