<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DecodeObfuscatedIds
{
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route();

        if ($route) {
            foreach ($route->parameters() as $key => $value) {
                if (is_string($value)) {
                    $decoded = d_id($value);
                    if ($decoded !== null) {
                        $route->setParameter($key, $decoded);
                    }
                }
            }
        }

        $query = $request->query();
        foreach ($query as $key => $value) {
            if (!is_string($value)) {
                continue;
            }

            if ($key === 'id' || str_ends_with($key, '_id')) {
                $decoded = d_id($value);
                if ($decoded !== null) {
                    $query[$key] = $decoded;
                }
            }
        }
        $request->query->replace($query);

        $payload = $request->all();
        foreach ($payload as $key => $value) {
            if (!is_string($value)) {
                continue;
            }

            if ($key === 'id' || str_ends_with($key, '_id')) {
                $decoded = d_id($value);
                if ($decoded !== null) {
                    $payload[$key] = $decoded;
                }
            }
        }
        $request->merge($payload);

        return $next($request);
    }
}
