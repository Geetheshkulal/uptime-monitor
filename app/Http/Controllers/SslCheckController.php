<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Ssl;
use Carbon\Carbon;

//Controller for SSL check
class SslCheckController extends Controller
{

    //SSL page
    public function index()
    {
        return view('ssl.index');
    }

    //Check for SSL validity.
    public function check(Request $request)
    {
        //validate request
        $request->validate([
            'domain' => 'required|url',
        ]);

        //Get input URL
        $inputUrl = $request->domain;
        $host = parse_url($inputUrl, PHP_URL_HOST); //Extract host part from URL

        //extract domain manually
        if (!$host) {
            $inputUrl = preg_replace('#^https?://#', '', $inputUrl);
            $host = explode('/', $inputUrl)[0];
        }

        try {
            $context = stream_context_create(["ssl" => ["capture_peer_cert" => true]]); //Create steam context to capture domains ssl certificate.
            $stream = @stream_socket_client("ssl://{$host}:443", $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $context); //Opens a SSL connection to port 443

            //If connection could not be made
            if (!$stream) {
                throw new \Exception("Could not connect to '{$host}' ({$errstr})");
            }

            //Get connection params including SSL certificate
            $params = stream_context_get_params($stream);
            $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']); //Parse raw SSL data into humanr readable fields

            //Extract the fields
            $validFrom = Carbon::createFromTimestamp($cert['validFrom_time_t']);
            $validTo = Carbon::createFromTimestamp($cert['validTo_time_t']);
            $daysRemaining = Carbon::now()->diffInDays($validTo, false);
            $status = $daysRemaining <= 0 ? 'Expired' : 'Valid';

            //Create an entry in the SSL table
            $ssl=Ssl::create([
                'user_id'        => Auth::id(),
                'url'            => $host,
                'issuer'         => $cert['issuer']['CN'] ?? 'Unknown',
                'valid_from'     => $validFrom,
                'valid_to'       => $validTo,
                'days_remaining' => $daysRemaining,
                'status'         => $status
            ]);

            //Log the SSL activity
            activity()
            ->causedBy(auth()->user())
            ->performedOn($ssl)
            ->inLog('ssl_monitoring')
            ->event('created')
            ->withProperties([
                'url' => $host,
                'issuer' => $cert['issuer']['CN'] ?? 'Unknown',
                'valid_to' => $validTo->toDateString(),
                'status' => $status
            ])
            ->log('SSL certificate monitored and logged.');

            return response()->json([
                'success' => true,
                'ssl_details' => [
                    'domain'         => $host,
                    'issuer'         => $cert['issuer']['CN'] ?? 'Unknown',
                    'valid_from'     => $validFrom->toDateString(),
                    'valid_to'       => $validTo->toDateString(),
                    'days_remaining' => $daysRemaining,
                    'status'         => $status
                ]
            ]);

            // return redirect()->back()->with([
            //     'success' => 'SSL check successful!',
            //     'ssl_details' => [
            //         'domain'         => $host,
            //         'issuer'         => $cert['issuer']['CN'] ?? 'Unknown',
            //         'valid_from'     => $validFrom->toDateString(),
            //         'valid_to'       => $validTo->toDateString(),
            //         'days_remaining' => $daysRemaining,
            //         'status'         => $status
            //     ]
            // ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "No valid SSL certificate found for '{$host}'."
            ]);
            // return redirect()->back()->with('error', "No valid SSL certificate found for '{$host}'.");
        }
        
    }

    //SSL Check history page
    public function history()
    {
        $sslChecks = Ssl::where('user_id', Auth::id())->latest()->get();
        return view('ssl.history', compact('sslChecks'));
    }
}
