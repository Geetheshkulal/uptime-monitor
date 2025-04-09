<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use Illuminate\Http\Request;

class PlanSubscriptionController extends Controller
{
 public function planSubscription()
 {
   //  $userId=Auth::id();

    // to get newest first
   //  $subscriptions=Payment::where("user_id",$userId)->latest()->first();

   //  return view('pages.planSubscription',compact('subscriptions'));
        $userId = Auth::id();
        $subscriptions = Payment::where("user_id", $userId)
                               ->orderBy('created_at', 'desc')
                               ->get();
       $count=$subscriptions->count();

       if($count>0)
       {
         return view('pages.planSubscription', compact('subscriptions'));
       }
       else{
         return view('pages.Premium');
       }

    
 }
}
