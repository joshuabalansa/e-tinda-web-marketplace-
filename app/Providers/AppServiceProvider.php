<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		// Ensure locale is set early using session or cookie, fallback to config('app.locale')
		$locale = Session::get('locale')
			?: request()->cookie('locale')
			?: config('app.locale');

		if (in_array($locale, config('app.available_locales', ['en']))) {
			App::setLocale($locale);
		}
	}
}
