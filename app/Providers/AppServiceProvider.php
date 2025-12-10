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
		$publicDiskDriver = config('filesystems.disks.public.driver', 'local');
		$appUrl = config('app.url');
		$isLocal = $this->app->environment('local', 'testing');

		// If S3 driver is configured but we're in local environment, use local driver instead
		// This prevents errors when the flysystem-aws-s3-v3 package is not installed
		if ($publicDiskDriver === 's3' && $isLocal) {
			// Force public disk to use 'local' driver to avoid S3 class errors
			// Merge config to preserve existing keys like 'root'
			$publicDiskConfig = config('filesystems.disks.public', []);
			config([
				'filesystems.disks.public' => array_merge($publicDiskConfig, [
					'driver' => 'local',
					'root' => $publicDiskConfig['root'] ?? storage_path('app/public'),
					'url' => $appUrl ? $appUrl . '/storage' : '/storage',
					'visibility' => $publicDiskConfig['visibility'] ?? 'public',
					'throw' => $publicDiskConfig['throw'] ?? false,
					'report' => $publicDiskConfig['report'] ?? false,
				])
			]);
		} elseif ($this->app->environment('production') && $appUrl) {
			// In production, if using local driver, ensure URL is set correctly
			if ($publicDiskDriver === 'local') {
				$publicDiskConfig = config('filesystems.disks.public', []);
				config([
					'filesystems.disks.public' => array_merge($publicDiskConfig, [
						'url' => $appUrl . '/storage',
					])
				]);
			}
		}
	}
}
