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
		if ($this->app->environment('production')) {
			$appUrl = config('app.url');
			if ($appUrl) {
				try {
					// Only configure if using local driver (not S3)
					// Laravel Cloud may use S3 for buckets, which handles URLs automatically
					$publicDiskDriver = config('filesystems.disks.public.driver', 'local');

					if ($publicDiskDriver === 'local') {
						// Ensure the public disk URL is set correctly
						// This makes Storage::disk('public')->url() work properly
						config(['filesystems.disks.public.url' => $appUrl . '/storage']);
					}
					// If using S3 or other drivers, Laravel Cloud handles URL generation automatically
				} catch (\Exception $e) {
					// Silently fail if storage isn't ready yet (during build process)
					// This prevents build errors when storage classes aren't available
				}
			}
		}
	}
}
