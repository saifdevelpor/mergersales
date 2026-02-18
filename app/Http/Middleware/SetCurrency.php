<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SetCurrency
{
    public function handle(Request $request, Closure $next)
    {
        $default = strtoupper(env('DEFAULT_CURRENCY', 'USD'));

        // ✅ session > cookie > default
        $currency = session('currency')
            ?? $request->cookie('currency')
            ?? $default;

        $currency = strtoupper($currency);

        // ✅ har view me available
        View::share('currentCurrency', $currency);

        return $next($request);
    }
}
