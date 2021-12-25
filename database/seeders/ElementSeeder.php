<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ElementSeeder extends Seeder
{
    public function run()
    {
        DB::table('elements')->insert([
            [
                'id' => 1,
                'name' => 'Элемент 1',
                'category_id' => 1,
                'pre_usd' => 100,
                'pre_rub' => 15000,
                'price' => 0,
            ],
            [
                'id' => 2,
                'name' => 'Элемент 2',
                'category_id' => 2,
                'pre_usd' => 100,
                'pre_rub' => 15000,
                'price' => 0,
            ],
            [
                'id' => 3,
                'name' => 'Элемент 3',
                'category_id' => 2,
                'pre_usd' => 100,
                'pre_rub' => 15000,
                'price' => 0,
            ],
            [
                'id' => 4,
                'name' => 'Элемент 4',
                'category_id' => 3,
                'pre_usd' => 100,
                'pre_rub' => 15000,
                'price' => 0,
            ],
            [
                'id' => 5,
                'name' => 'Элемент 5',
                'category_id' => 3,
                'pre_usd' => 100,
                'pre_rub' => 15000,
                'price' => 0,
            ],
        ]);
    }
}