<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SubscriptionsTableSeeder::class,
        ]);
        $this->call([
            SuperAdmin::class
        ]);

        $this->call([
            permissionandrole::class,
        ]);
        $this->call([
            AssignDefaultPermissions::class,
        ]);   
        $this->call([
            TemplateSeeder::class,
        ]);
    }
}
