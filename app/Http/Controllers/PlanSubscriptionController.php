<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

//Payments controller for user payments table else redirect to plans page
class PlanSubscriptionController extends Controller
{
  public function planSubscription()
  {
      $userId = Auth::id();
      $subscriptions = Payment::where("user_id", $userId)
                             ->orderBy('created_at', 'desc')
                             ->get();
      $count = $subscriptions->count();
      
  
      if ($count > 0) {
          return view('pages.planSubscription', compact('subscriptions'));
      } else {
          return redirect()->route('premium.page');
      }
  }
}