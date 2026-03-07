<?php

namespace Tests;

use Database\Seeders\OrderStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $seeder = OrderStatusSeeder::class;
}
