<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BoxSeeder extends Seeder
{
    public function run()
    {
        DB::table('boxes')->insert([
            [
                'id' => 1,
                'name' => 'Корпус 1',
                'pre_usd' => 100,
                'pre_rub' => 15000,
                'price' => 0,
            ],
            [
                'id' => 2,
                'name' => 'Корпус 2',
                'pre_usd' => 100,
                'pre_rub' => 15000,
                'price' => 0,
            ],
            [
                'id' => 3,
                'name' => 'Корпус 3',
                'pre_usd' => 100,
                'pre_rub' => 15000,
                'price' => 0,
            ],
            [
                'id' => 4,
                'name' => 'Корпус 4',
                'pre_usd' => 100,
                'pre_rub' => 15000,
                'price' => 0,
            ],
            [
                'id' => 5,
                'name' => 'Корпус 5',
                'pre_usd' => 100,
                'pre_rub' => 15000,
                'price' => 0,
            ],
        ]);
    }
}