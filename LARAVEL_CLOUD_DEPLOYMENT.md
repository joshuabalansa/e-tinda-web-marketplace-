# Laravel Cloud Deployment Guide - Image Storage Fix

## Issues Fixed

This guide addresses the image storage issues when deploying to Laravel Cloud.

### Problems Identified

1. **Incorrect Filesystem Disk**: Default was set to `local` which stores files in `storage/app/private` (not web-accessible)
2. **Missing APP_URL Configuration**: Image URLs were not generated correctly in production
3. **HTTPS Configuration**: Laravel Cloud requires HTTPS but URLs were being generated with HTTP

## Changes Made

### 1. Storage Route (CRITICAL for Laravel Cloud)

Added a route in `routes/web.php` to serve storage files directly through Laravel:

```php
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($fullPath);
    
    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*');
```

**Why this is needed**: Laravel Cloud doesn't support traditional symbolic links (`php artisan storage:link`). Files must be served through Laravel routes instead.

### 2. Environment Configuration (`.env`)

Update your production `.env` file with these settings:

```env
# Application URL - CRITICAL: Set this to your Laravel Cloud domain
APP_URL=https://your-app-name.laravel.cloud
APP_ENV=production
APP_DEBUG=false

# Filesystem - Use 'public' disk for web-accessible uploads
FILESYSTEM_DISK=public
```

### 2. AppServiceProvider Updates

The `app/Providers/AppServiceProvider.php` now includes:

- **HTTPS Enforcement**: Forces HTTPS scheme in production
- **Storage URL Configuration**: Ensures Storage::url() generates correct URLs

### 3. Product Model Updates

The `getImageUrl()` method now uses `Storage::disk('public')->url()` instead of `asset()` for better compatibility with Laravel Cloud.

## Deployment Steps

### Step 1: Update Environment Variables

In your Laravel Cloud dashboard:

1. Go to your project settings
2. Navigate to Environment Variables
3. Add/Update these variables:
   ```
   APP_URL=https://your-actual-domain.laravel.cloud
   APP_ENV=production
   APP_DEBUG=false
   FILESYSTEM_DISK=public
   ```

### Step 2: ~~Ensure Storage Link Exists~~ (NOT NEEDED)

**IMPORTANT**: Do NOT run `php artisan storage:link` on Laravel Cloud. The storage route in `routes/web.php` handles file serving instead.

Traditional symlinks don't work reliably on Laravel Cloud's infrastructure. The route-based approach is the correct solution.

### Step 3: Verify File Permissions

Ensure the storage directories are writable:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Step 4: Clear Caches

After deployment, clear all caches:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

Then rebuild caches for production:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Test Image Upload

1. Log in as a farmer
2. Create or edit a product
3. Upload an image
4. Verify the image displays correctly

## Troubleshooting

### Images Still Not Showing?

**Check 1: Verify APP_URL**
```bash
php artisan tinker
>>> config('app.url')
```
Should return your production URL with HTTPS.

**Check 2: Verify Storage Disk**
```bash
php artisan tinker
>>> config('filesystems.default')
```
Should return `"public"`.

**Check 3: Check Storage Link**
```bash
ls -la public/storage
```
Should show a symlink to `../storage/app/public`.

**Check 4: Test Storage URL Generation**
```bash
php artisan tinker
>>> Storage::disk('public')->url('test.jpg')
```
Should return a full URL like `https://your-domain.laravel.cloud/storage/test.jpg`.

**Check 5: Verify Image Path in Database**
```bash
php artisan tinker
>>> \App\Models\Product::first()->image_url
```
Should return a path like `products/xyz.jpg` (not a full URL).

### Common Issues

#### Issue: 404 on Image URLs

**Cause**: Storage link not created or broken.

**Solution**:
```bash
php artisan storage:link
```

#### Issue: HTTP URLs Instead of HTTPS

**Cause**: APP_URL not set correctly or missing HTTPS enforcement.

**Solution**: 
1. Set `APP_URL=https://your-domain.laravel.cloud` in environment
2. Run `php artisan config:clear && php artisan config:cache`

#### Issue: Images Upload But Don't Display

**Cause**: File permissions or storage disk misconfiguration.

**Solution**:
```bash
# Fix permissions
chmod -R 775 storage/app/public

# Verify disk configuration
php artisan tinker
>>> Storage::disk('public')->exists('products')
```

#### Issue: Old Images Still Broken

**Cause**: Existing images in database have incorrect paths.

**Solution**: The `getImageUrl()` method handles various path formats, but if needed, you can update existing records:

```bash
php artisan tinker
>>> \App\Models\Product::whereNotNull('image_url')
    ->where('image_url', 'LIKE', 'http%')
    ->update(['image_url' => DB::raw("REPLACE(image_url, CONCAT('" . config('app.url') . "/storage/'), '')")]);
```

## Laravel Cloud Specific Notes

### Storage Persistence

Laravel Cloud provides persistent storage for the `storage/app` directory. Files uploaded to `storage/app/public` will persist across deployments.

### Build Process

The `nixpacks.toml` file includes:
```toml
[phases.build]
cmds = [
    "php artisan storage:link || true"
]
```

This ensures the storage link is created during deployment.

### Environment Variables

Always set environment variables through the Laravel Cloud dashboard, not in the `.env` file in your repository.

## Testing Checklist

- [ ] APP_URL is set to production domain with HTTPS
- [ ] FILESYSTEM_DISK is set to `public`
- [ ] Storage link exists (`public/storage` → `../storage/app/public`)
- [ ] Can upload new images as farmer
- [ ] Uploaded images display correctly
- [ ] Images display on shop page
- [ ] Images display in orders
- [ ] Images display in wishlist
- [ ] Old images still work (if any existed)

## Additional Resources

- [Laravel File Storage Documentation](https://laravel.com/docs/filesystem)
- [Laravel Cloud Documentation](https://cloud.laravel.com/docs)
- [Symlink Issues on Cloud Platforms](https://laravel.com/docs/filesystem#the-public-disk)

## Support

If images still don't work after following this guide:

1. Check Laravel Cloud logs for errors
2. Verify all environment variables are set correctly
3. Ensure storage directories have correct permissions
4. Test image URL generation in tinker
5. Check browser console for 404 or CORS errors
