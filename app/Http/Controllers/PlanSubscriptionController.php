<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use Illuminate\Http\Request;

class PlanSubscriptionController extends Controller
{
 public function planSubscription()
 {
    $userId=Auth::id();

    $subscription=Payment::where("user_id",$userId)->latest()->first();

    return view('pages.planSubscription',compact('subscription'));
    
 }
}
