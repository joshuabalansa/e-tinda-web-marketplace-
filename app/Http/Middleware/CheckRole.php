<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $method = 'is' . ucfirst($role);
        if (!method_exists($request->user(), $method) || !$request->user()->$method()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}