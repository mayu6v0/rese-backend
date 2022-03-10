<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ReservationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'restaurant_id' => 1,
            'datetime' => '2022-3-10 18:00',
            'number' => 1,
        ];
        DB::table('reservations')->insert($param);
        $param = [
            'user_id' => 1,
            'restaurant_id' => 5,
            'datetime' => '2022-3-11 18:00',
            'number' => 2,
        ];
        DB::table('reservations')->insert($param);
        $param = [
            'user_id' => 1,
            'restaurant_id' => 10,
            'datetime' => '2022-3-15 19:00',
            'number' => 8,
        ];
        DB::table('reservations')->insert($param);
        $param = [
            'user_id' => 2,
            'restaurant_id' => 4,
            'datetime' => '2022-3-17 18:00',
            'number' => 5,
        ];
        DB::table('reservations')->insert($param);
        $param = [
            'user_id' => 2,
            'restaurant_id' => 7,
            'datetime' => '2022-3-22 18:00',
            'number' => 6,
        ];
        DB::table('reservations')->insert($param);
        $param = [
            'user_id' => 3,
            'restaurant_id' => 4,
            'datetime' => '2022-3-10 18:00',
            'number' => 4,
        ];
        DB::table('reservations')->insert($param);
        $param = [
            'user_id' => 3,
            'restaurant_id' => 1,
            'datetime' => '2022-3-15 18:00',
            'number' => 2,
        ];
        DB::table('reservations')->insert($param);
    }
}
