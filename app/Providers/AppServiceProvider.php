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
		// CRITICAL: Force 'local' driver to prevent S3 initialization errors
		// Only use S3 if explicitly configured AND the package is available
		$publicDiskDriver = config('filesystems.disks.public.driver', 'local');
		$appUrl = config('app.url');
		$hasAwsCredentials = env('AWS_ACCESS_KEY_ID') && env('AWS_SECRET_ACCESS_KEY');
		
		// Check if S3 package is available (safely)
		$hasS3Package = false;
		try {
			$hasS3Package = class_exists(\League\Flysystem\AwsS3V3\AwsS3V3Adapter::class);
		} catch (\Throwable $e) {
			// Class doesn't exist, S3 package not installed
			$hasS3Package = false;
		}
		
		// Always use 'local' driver unless S3 is explicitly requested AND package is installed
		if ($publicDiskDriver === 's3' && (!$hasAwsCredentials || !$hasS3Package)) {
			// Force public disk to use 'local' driver to avoid S3 class errors
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
			if ($publicDiskDriver === 'local' || !$hasS3Package) {
				$publicDiskConfig = config('filesystems.disks.public', []);
				config([
					'filesystems.disks.public' => array_merge($publicDiskConfig, [
						'driver' => 'local',
						'url' => $appUrl . '/storage',
					])
				]);
			}
		}
	}
}
