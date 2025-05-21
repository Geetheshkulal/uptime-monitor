<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlockedIP;

class BlockController extends Controller
{
    //

    public function BlockIP($ip){
      $blockedList = BlockedIP::where('ip_address', $ip)->pluck('ip_address')->toArray();
      if (!in_array($ip, $blockedList)) {
             BlockedIP::create(['ip_address' =>$ip]);
             return redirect()->back()->with('success','IP blocked successfully');
      }else{
         return redirect()->back()->with('error','IP already blocked');
      }
    }

    public function UnblockIP($ip)
    {
        $blockedEntry = BlockedIP::where('ip_address', $ip)->first();

        if ($blockedEntry) {
            $blockedEntry->delete();
            return redirect()->back()->with('success', 'IP unblocked successfully.');
        } else {
            return redirect()->back()->with('error', 'IP not found in blocked list.');
        }
    }

}
