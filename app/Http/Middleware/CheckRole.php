<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Check if user has a role attribute
        if (!isset($user->role) || $user->role === null) {
            Log::warning('User missing role attribute', [
                'user_id' => $user->id,
                'route' => $request->path(),
            ]);
            abort(403, 'User role not configured. Please contact support.');
        }

        // Normalize role name (lowercase)
        $role = strtolower($role);
        $method = 'is' . ucfirst($role);

        // Check if the method exists and call it
        if (!method_exists($user, $method)) {
            Log::error('Role check method not found', [
                'user_id' => $user->id,
                'role' => $role,
                'method' => $method,
            ]);
            abort(403, 'Unauthorized action.');
        }

        try {
            if (!$user->$method()) {
                abort(403, 'Unauthorized action.');
            }
        } catch (\Exception $e) {
            Log::error('Error checking user role', [
                'user_id' => $user->id,
                'role' => $role,
                'error' => $e->getMessage(),
            ]);
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}