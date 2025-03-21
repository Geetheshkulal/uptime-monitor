<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
//     protected function schedule(Schedule $schedule): void
//         // $schedule->command('inspire')->hourly();
//         // $schedule->command('monitor:check-http {user_id}')->everyMinute();
// {
//     $users = User::pluck('id'); // Get all user IDs
//     foreach ($users as $userId) {
//         $schedule->command("monitor:check-http {$userId}")->everyMinute();
//     }
//     }


protected function schedule(Schedule $schedule): void
{
    $schedule->call(function () {
        $users = User::pluck('id'); // Get all user IDs
        foreach ($users as $userId) {
            Artisan::call('monitor:check-http', ['user_id' => $userId]);
        }
    })->everyMinute();
}

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
