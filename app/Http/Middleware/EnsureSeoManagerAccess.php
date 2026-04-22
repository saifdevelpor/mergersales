<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSeoManagerAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $hasColumnRole = strtolower((string) $user->role) === 'seo_manager';
        $hasSpatieRole = method_exists($user, 'hasRole') && $user->hasRole('seo_manager');

        if (! $hasColumnRole && ! $hasSpatieRole) {
            abort(403, 'User does not have the right roles.');
        }

        return $next($request);
    }
}
