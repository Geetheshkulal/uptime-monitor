<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateSeeder extends Seeder
{
    public function run()
    {
        DB::table('templates')->insert([
            [
                'template_name' => 'whatsap_monitor_down',
                'content' => '',
                'variables' => json_encode(['user_name', 'monitor_name', 'down_timestamp', 'monitor_type']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'template_name' => 'whatsapp_monitor_up',
                'content' => '',
                'variables' => json_encode(['user_name', 'monitor_name', 'down_timestamp', 'monitor_type', 'downtime_duration']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
