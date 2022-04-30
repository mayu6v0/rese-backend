<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Database\Seeders\AreaSeeder;
use Database\Seeders\GenreSeeder;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // protected $seed = true;

    // protected $seeder = ([
    //     AreaSeeder::class,
    //     GenreSeeder::class,
    // ]);
}
