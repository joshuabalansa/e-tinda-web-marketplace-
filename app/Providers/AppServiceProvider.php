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

		// Configure storage URL for production
		// Always use local driver to avoid S3 class loading issues
		$appUrl = config('app.url');

		if ($appUrl) {
			$publicDiskConfig = config('filesystems.disks.public', []);
			config([
				'filesystems.disks.public' => array_merge($publicDiskConfig, [
					'driver' => 'local',
					'root' => storage_path('app/public'),
					'url' => $appUrl . '/storage',
					'visibility' => 'public',
					'throw' => false,
					'report' => false,
				])
			]);
		}
	}
}
