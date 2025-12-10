<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;

class BuyerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has a role
        if (!isset($user->role) || $user->role === null) {
            abort(403, 'User role not configured. Please contact support.');
        }

        // Use the isBuyer() method for consistency
        if (!$user->isBuyer()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}