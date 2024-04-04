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

        if (DB::table('sessions')->where('ip_address', $ip)->exists()) { // Session exists for this IP

            if (DB::table('sessions')->where('id', $sessionID)->where('status', 'closed')->exists()) {
                logger("This IP " . $ip . " already has a session but it has been closed");
                return response()->json(['error' => 'Your session has been closed'], 403);
            }
            if (DB::table('sessions')->where('id','!=', $sessionID)->where('status', 'active')->exists()) {
                logger("This IP " . $ip . " already has a active session, current request is from new session");
                return response()->json(['error' => 'Another session in progress', 'continue_session_url' => route('continue-session')], 429);
            } else {
                DB::table('sessions')->where('id', $sessionID)->update(['status' => 'active']);
                logger("this IP " . $ip . " already has a session new request from same session");
                return $next($request);
            }

        } else {  // Session don't exists for this IP

            logger("New session from new IP " . $ip);
            Session::put($key, $sessionID);
            return $next($request);

        }
    }
}
