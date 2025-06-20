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

        //Log activity.
        activity()
            ->performedOn($subscription)
            ->causedBy(auth()->user())
            ->inLog('Billing')
            ->event('edited')
            ->withProperties([
                'user_name' => auth()->user()->name,
                'subscription_name' => $subscription->name,
                'subscription_amount' => $subscription->amount,
            ])
            ->log('Edited subscription');

        return redirect()->back()->with('success','Subscription edited successfully');
    }
}
