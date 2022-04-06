<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function start_login(Request $request) { 
        $state = Str::random(40);

        session([
            'state' => $state
        ]);
    
        $query_url = http_build_query([
            'client_id' => env('CLIENT_ID'),
            'redirect_url' => env('REDIRECT_URL'),
            'response_type' => 'code',
            'scope' => '',
            'state' => $state
        ]);
    
        return redirect(env('API_URL').'oauth/authorize?'.$query_url);
    }

    public function callback(Request $request) {
        $response = Http::post(env('API_URL').'oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'redirect_url' => env('REDIRECT_URL'),
            'code' => $request->code,
        ]);

        return $response->json();
    }
}
