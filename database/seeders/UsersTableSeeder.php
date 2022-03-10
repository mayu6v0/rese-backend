<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'test1',
            'email' => 'test1@mail',
            'password' => 'test1',
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => 'test2',
            'email' => 'test2@mail',
            'password' => 'test2',
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => 'test3',
            'email' => 'test3@mail',
            'password' => 'test3',
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => 'test4',
            'email' => 'test4@mail',
            'password' => 'test4',
        ];
        DB::table('users')->insert($param);
        $param = [
            'name' => 'test5',
            'email' => 'test5@mail',
            'password' => 'test5',
        ];
        DB::table('users')->insert($param);
    }
}
