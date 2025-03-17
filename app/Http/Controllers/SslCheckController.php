<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SslCheckController extends Controller
{
    public function index()
    {
        return view('ssl.index'); // Create this view next
    }

    public function check(Request $request)
    {
        $request->validate([
            'domain' => 'required|url'
        ]);
    
        $domain = parse_url($request->domain, PHP_URL_HOST);
        $certInfo = @stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
        $client = @stream_socket_client("ssl://{$domain}:443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $certInfo);
    
        if (!$client) {
            return back()->with('error', 'Could not fetch SSL details. The domain may not support SSL.');
        }
    
        $context = stream_context_get_params($client);
        $cert = openssl_x509_parse($context["options"]["ssl"]["peer_certificate"]);
        
        $validFrom = date("Y-m-d", $cert['validFrom_time_t']);
        $validTo = date("Y-m-d", $cert['validTo_time_t']);
        $daysRemaining = (strtotime($validTo) - time()) / 86400;

        // Determine SSL Status
        if ($daysRemaining <= 0) {
            $status = 'Expired 🔴';
        } elseif ($daysRemaining <= 30) {
            $status = 'Expiring Soon 🟠';
        } else {
            $status = 'Active🟢';
        }
    
        return back()->with([
            'ssl_details' => [
                'domain' => $domain,
                'issuer' => $cert['issuer']['O'] ?? 'Unknown',
                'valid_from' => $validFrom,
                'valid_to' => $validTo,
                'days_remaining' => round($daysRemaining),
                'status' => $status
            ]
        ]);
    }
    
}
