<?php

namespace App\Http\Controllers;

use App\Models\Subscriptions;
use Illuminate\Http\Request;
use Log;

class PremiumPageController extends Controller
{
    //
    public function PremiumPage(){
        $plans = Subscriptions::all();
  
      if (!$plans) {
          Log::error("Subscription plan with ID 1 not found.");
          return back()->with('error', 'Subscription plan not available.');
      }
  
      Log::info("Plan Data: ", $plans->toArray()); // Detailed log

      return view('pages.premium',compact('plans'));
    }
}
