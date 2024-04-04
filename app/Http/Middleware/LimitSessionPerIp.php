<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class LimitSessionPerIp
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $key = 'api_limit_' . $ip;

        $sessionID = $request->session()->getId();

        if (DB::table('sessions')->where('ip_address', $ip)->exists()) {

            if (Session::get($key) != $sessionID) {
                logger("this IP " . $ip . " already has a session current request is from another session but IP exists");
                return response()->json(['error' => 'Previous session in progress', 'url' => route('continue-session') ], 429);
            } else {
                logger("this IP " . $ip . " already has a session new request from same session");
                return $next($request);
            }

        } else {

            logger("New session from new IP " . $ip);
            Session::put($key, $sessionID);
            return $next($request);

        }
    }
}
