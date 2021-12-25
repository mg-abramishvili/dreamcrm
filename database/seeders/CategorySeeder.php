<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Категория 1',
                'slug' => 'category-1',
            ],
            [
                'id' => 2,
                'name' => 'Категория 2',
                'slug' => 'category-2',
            ],
            [
                'id' => 3,
                'name' => 'Категория 3',
                'slug' => 'category-3',
            ],
            [
                'id' => 4,
                'name' => 'Категория 4',
                'slug' => 'category-4',
            ],
            [
                'id' => 5,
                'name' => 'Категория 5',
                'slug' => 'category-5',
            ],
        ]);
    }
}