<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monitors;

use Illuminate\Support\Facades\Auth;
use App\Models\DnsResponse;


class DnsController extends Controller
{
    public function AddDNS(Request $request){

        $request->validate([
            'domain' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telegram_id' => 'nullable|string|max:255',
            'telegram_bot_token' => 'nullable|string|max:255',
            'interval' => 'required|integer|min:1' // Ensure interval is valid
        ]);
    
        // Create a new DNS monitor entry
        $monitor = Monitors::create([
            'name'=> $request->name,
            'status'=>'up',
            'user_id' => Auth::id(),
            'url' => $request->domain, // Store domain as the target
            'type' => 'dns',
            'port'=>null,
            'retries'=>$request->retries,
            'interval' => $request->interval, // Store interval for monitoring            
            'dns_resource_type'=>$request->dns_resource_type,
            'email' => $request->email,
            'telegram_id' => $request->telegram_id,
            'telegram_bot_token' => $request->telegram_bot_token,
        ]);
    
        return redirect()->route('monitoring.dashboard')->with('success', ucfirst($request->type) . ' monitoring added successfully!');
    
    }
}
