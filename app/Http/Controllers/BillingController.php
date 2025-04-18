<?php

namespace App\Http\Controllers;

use App\Models\Subscriptions;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    //View Billing Page
    public function Billing(){
        $subscriptions = Subscriptions::all();
        return view('pages.admin.Billing', compact('subscriptions'));
    }

    //Update billing amount
    public function EditBilling(Request $request,$id){
        $vaildated = $request->validate([
            'name'=>'required',
            'amount'=> 'required'
        ]);
        $subscription = Subscriptions::find($id);
        $subscription->update($vaildated);

        return redirect()->back()->with('success','Subscription edited successfully');
    }
}
