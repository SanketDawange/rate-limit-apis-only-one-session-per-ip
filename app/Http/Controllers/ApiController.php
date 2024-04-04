<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Session;

class ApiController extends Controller
{
    public function test(Request $request)
    {
        return response()->json(['message' => 'Test endpoint accessed successfully']);
    }

    public function continueSession(Request $request)
    {
        $ip = $request->ip();

        DB::table('sessions')->where('ip_address', $ip)->where('id', '!=', $request->session()->getId())->delete();

        return response()->json(['message' => 'Closed previous session.']);
    }
}
