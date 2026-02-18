<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function set(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|size:3',
        ]);

        $currency = strtoupper($request->currency);

        // ✅ Session
        session(['currency' => $currency]);

        // ✅ Cookie (30 days) so user ki currency remember rahe
        return back()->withCookie(cookie('currency', $currency, 60 * 24 * 30));
    }
}
