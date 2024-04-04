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

            if (DB::table('sessions')->where('id', $sessionID)->where('status', 'closed')->exists()) {
                logger("this IP " . $ip . " already has a session but it has been closed");
                return response()->json(['error' => 'Your session has been closed'], 403);
            }
            if (Session::get($key) != $sessionID && DB::table('sessions')->where('id','!=', $sessionID)->where('status', 'active')->exists()) {
                logger("this IP " . $ip . " already has a session current request is from another session but IP exists");
                return response()->json(['error' => 'Another session in progress', 'continue_session_url' => route('continue-session')], 429);
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
