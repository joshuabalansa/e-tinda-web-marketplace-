<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if locale is set in session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        }
        // Check if locale is set in cookie
        elseif ($request->cookie('locale')) {
            $locale = $request->cookie('locale');
        }
        // Default to English
        else {
            $locale = 'en';
        }

        // Set the application locale
        App::setLocale($locale);

        // Log for debugging
        \Log::info("SetLocale middleware: Setting locale to {$locale}", [
            'session_locale' => Session::get('locale'),
            'cookie_locale' => $request->cookie('locale'),
            'final_locale' => $locale,
            'app_locale' => App::getLocale(),
            'url' => $request->url()
        ]);

        return $next($request);
    }
}
