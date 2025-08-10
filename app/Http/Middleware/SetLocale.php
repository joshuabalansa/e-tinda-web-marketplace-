<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure session is started
        if (!$request->session()->isStarted()) {
            $request->session()->start();
        }

        // Check if locale is set in cookie first (more reliable)
        if ($request->hasCookie('locale')) {
            $locale = $request->cookie('locale');
            if (in_array($locale, ['en', 'hil'])) {
                app()->setLocale($locale);
                // Also update session for consistency
                $request->session()->put('locale', $locale);
                return $next($request);
            }
        }

        // Check if locale is set in session
        if ($request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
            if (in_array($locale, ['en', 'hil'])) {
                app()->setLocale($locale);
                return $next($request);
            }
        }

        // Set default locale if none is set
        app()->setLocale('en');
        $request->session()->put('locale', 'en');

        return $next($request);
    }
}
