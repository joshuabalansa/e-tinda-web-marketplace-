<?php

namespace App\Providers;

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
		// Locale is handled by SetLocale middleware (see bootstrap/app.php)
		// No need to set it here during boot to avoid session/request access issues

		// Force HTTPS in production for Laravel Cloud
		if ($this->app->environment('production')) {
			URL::forceScheme('https');
		}

		// Configure storage URL for Laravel Cloud
		// This ensures Storage::url() generates correct URLs in production
		// CRITICAL: Bucket must be set to "public" in Laravel Cloud dashboard for this to work
		// IMPORTANT: Force 'local' driver to prevent S3 initialization errors
		if ($this->app->environment('production')) {
			$appUrl = config('app.url');
			if ($appUrl) {
				// Force public disk to use 'local' driver to avoid S3 class errors
				// Laravel Cloud buckets are mounted as local filesystem, not S3
				config(['filesystems.disks.public.driver' => 'local']);
				config(['filesystems.disks.public.url' => $appUrl . '/storage']);
			}
		}
	}
}
