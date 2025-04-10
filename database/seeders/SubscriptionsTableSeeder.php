<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('subscriptions')->insert([
            'name'   => 'Premium',
            'amount' => 399.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}