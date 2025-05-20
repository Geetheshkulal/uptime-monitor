<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeactivateExpiredCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupons:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate coupons whose valid_until date is today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $expired = DB::table('coupon_codes')
            ->whereDate('valid_until', $today)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $this->info("Deactivated $expired expired coupon(s).");
    }
}
