<?php

namespace Database\Seeders;

use App\Models\tag;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create Tag

        Tag::insert([
            ['name'=>'laravel','slug'=>'laravel'],
            ['name'=>'php','slug'=>'php'],
            ['name'=>'css','slug'=>'css'],
            ['name'=>'javascript','slug'=>'javascript'],
            ['name'=>'java','slug'=>'java'],
            ['name'=>'kotlin','slug'=>'kotlin'],
            ['name'=>'android','slug'=>'android'],
            ['name'=>'programming','slug'=>'programming'],
            ['name'=>'blog','slug'=>'blog'],
        ]);
    }
}
