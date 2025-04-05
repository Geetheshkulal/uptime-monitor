<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Ssl;
use Carbon\Carbon;

class SslCheckController extends Controller
{
    public function index()
    {
        return view('ssl.index');
    }

    public function check(Request $request)
    {
        $request->validate([
            'domain' => 'required|url',
        ]);

        $inputUrl = $request->domain;
        $host = parse_url($inputUrl, PHP_URL_HOST);

        if (!$host) {
            $inputUrl = preg_replace('#^https?://#', '', $inputUrl);
            $host = explode('/', $inputUrl)[0];
        }

        try {
            $context = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
            $stream = @stream_socket_client("ssl://{$host}:443", $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $context);

            if (!$stream) {
                throw new \Exception("Could not connect to '{$host}' ({$errstr})");
            }

            $params = stream_context_get_params($stream);
            $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);

            $validFrom = Carbon::createFromTimestamp($cert['validFrom_time_t']);
            $validTo = Carbon::createFromTimestamp($cert['validTo_time_t']);
            $daysRemaining = Carbon::now()->diffInDays($validTo, false);
            $status = $daysRemaining <= 0 ? 'Expired' : 'Valid';

            Ssl::create([
                'user_id'        => Auth::id(),
                'url'            => $host,
                'issuer'         => $cert['issuer']['CN'] ?? 'Unknown',
                'valid_from'     => $validFrom,
                'valid_to'       => $validTo,
                'days_remaining' => $daysRemaining,
                'status'         => $status
            ]);

            return redirect()->back()->with([
                'success' => 'SSL check successful!',
                'ssl_details' => [
                    'domain'         => $host,
                    'issuer'         => $cert['issuer']['CN'] ?? 'Unknown',
                    'valid_from'     => $validFrom->toDateString(),
                    'valid_to'       => $validTo->toDateString(),
                    'days_remaining' => $daysRemaining,
                    'status'         => $status
                ]
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "No valid SSL certificate found for '{$host}'.");
        }
        
    }

    public function history()
    {
        $sslChecks = Ssl::where('user_id', Auth::id())->latest()->get();
        return view('ssl.history', compact('sslChecks'));
    }
}
