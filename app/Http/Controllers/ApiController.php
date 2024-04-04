<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function test(Request $request)
    {
        return response()->json(['message' => 'Test endpoint accessed successfully']);
    }

    public function continueSession(Request $request)
    {
        $ip = $request->ip();

        // Close all other sessions
        DB::table('sessions')->where('ip_address', $ip)->where('id', '!=', $request->session()->getId())->update(['status' => 'closed']);

        // Mark current session as active
        DB::table('sessions')->where('id', $request->session()->getId())->update(['status' => 'active']);

        return response()->json(['message' => 'Closed other session.']);
    }
}
