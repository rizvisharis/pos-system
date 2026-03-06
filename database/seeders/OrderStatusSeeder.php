<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_status')->insert([
            ['key' => 'pending', 'value' => 'Pending'],
            ['key' => 'paid', 'value' => 'Paid'],
            ['key' => 'cancelled', 'value' => 'Cancelled'],
        ]);
    }
}
