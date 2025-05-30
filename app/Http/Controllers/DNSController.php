<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monitors;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

//Controller for DNS
class DNSController extends Controller
{
    //Add a new DNS monitor.
    public function AddDNS(Request $request){

        $user = auth()->user();
        $user = ($user->hasRole('subuser'))?$user->parentUser:auth()->user();

        $request->validate([
            'name' => 'required|string',
            'domain' => [
                'required',
                'url',
                Rule::unique('monitors','url')->where(function ($query) use ($user, $request) {
                    return $query->where('user_id', $user->id)
                                ->where('type', 'dns');
                }),
            ],
            'email' => 'required|email|max:255',
            'telegram_id' => 'nullable|string|max:255',
            'telegram_bot_token' => 'nullable|string|max:255',
            'interval' => 'required|integer|min:1', // Ensure interval is valid
            'retries'=>'required|integer|min:1' 
        ]);
    
        // Create a new DNS monitor entry
        $monitor = Monitors::create([
            'name'=> $request->name,
            'status'=>'down',
            'user_id' => $user->id,
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

        //Log activity.
        activity()
        ->performedOn($monitor)
        ->causedBy(auth()->user())
        ->inLog('DNS monitoring') 
        ->event('created')
        ->withProperties([
            'user_name' => $user->name,
            'monitor_name' => $monitor->name,
            'monitor_url' => $monitor->url,
            'dns_resource_type'=>$request->dns_resource_type,
            'monitor_type' => $monitor->type
        ])
        ->log("Created {$monitor->type} monitor");
    
        return redirect()->route('monitoring.dashboard')->with('success', ucfirst($request->type) . ' monitoring added successfully!');
    
    }
}
