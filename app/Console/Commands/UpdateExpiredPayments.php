<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;

class UpdateExpiredPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:expire-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire payments whose end_date is today';

    public function handle()
    {
        $today = Carbon::today();

         // Get users whose premium ends today and status is 'paid'
         $users = User::where('status', 'paid')
         ->whereDate('premium_end_date', $today)
         ->get();

         foreach ($users as $user) {
            // Update the user's status and clear premium_end_date
            $user->status = 'free';
            $user->premium_end_date = null;
            $user->save();

            $this->info("Updated user ID: {$user->id} to status 'free'");

            // Expire all their active payments
            $activePayments = Payment::where('user_id', $user->id)
                ->where('status', 'active')
                ->get();

            foreach ($activePayments as $payment) {
                $payment->status = 'expired';
                $payment->save();
                $this->info("Expired payment ID: {$payment->id} for user ID: {$user->id}");
            }
        }
        return 0;
    }
}
