<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
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

		// Force HTTPS in production for Laravel Cloud
		if ($this->app->environment('production')) {
			URL::forceScheme('https');
		}

		// Configure storage URL for Laravel Cloud
		// This ensures Storage::url() generates correct URLs in production
		if ($this->app->environment('production')) {
			$appUrl = config('app.url');
			if ($appUrl) {
				Storage::disk('public')->buildTemporaryUrlsUsing(function ($path, $expiration, $options) use ($appUrl) {
					return $appUrl . '/storage/' . $path;
				});
			}
		}
	}
}
