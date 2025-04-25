<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;


class FreeTrialCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:free-trial-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update users whose free trial has expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now();

        // Get users whose free trial has expired (10 days after created_at) and are still marked as 'paid'

        $users = User::where('status','paid')
            ->where('created_at', '<=', $today->subDays(10))
            ->where(function($query){
                $query->whereNull('premium_end_date')
                      ->orWhere('premium_end_date', '<', Carbon::now());
            })
            ->get();

            foreach ($users as $user){
                $user->status= 'free';
                $user->premium_end_date = null;
                $user->save();

                $this->info("Updated user ID: {$user->id} to status 'free' after free trial expired.");
            }

            return 0;
    }
}
