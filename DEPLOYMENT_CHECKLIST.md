# Laravel Cloud Deployment Checklist

## Pre-Deployment

- [ ] Code changes committed to repository
- [ ] All tests passing locally
- [ ] Database migrations reviewed

## Environment Configuration

### Required Environment Variables

Set these in Laravel Cloud dashboard:

```env
# Application
APP_NAME="E-Tinda Marketplace"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.laravel.cloud

# Key (Generate with: php artisan key:generate --show)
APP_KEY=base64:your-generated-key-here

# Database (Laravel Cloud provides these automatically)
DB_CONNECTION=mysql
DB_HOST=<provided-by-laravel-cloud>
DB_PORT=3306
DB_DATABASE=<provided-by-laravel-cloud>
DB_USERNAME=<provided-by-laravel-cloud>
DB_PASSWORD=<provided-by-laravel-cloud>

# Filesystem - CRITICAL FOR IMAGES
FILESYSTEM_DISK=public

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (Configure based on your email provider)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Post-Deployment Commands

Run these commands after deployment (Laravel Cloud may run some automatically):

```bash
# 1. Run migrations
php artisan migrate --force

# 2. Create storage link
php artisan storage:link

# 3. Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 4. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. (Optional) Seed admin user if needed
php artisan db:seed --class=AdminSeeder
```

## Verification Steps

### 1. Application Access
- [ ] Application loads at production URL
- [ ] HTTPS is working (no mixed content warnings)
- [ ] No errors in browser console

### 2. Authentication
- [ ] Can register new user
- [ ] Can login with existing user
- [ ] Password reset works
- [ ] Email verification works (if enabled)

### 3. Image Upload & Display
- [ ] Login as farmer
- [ ] Navigate to Products → Create Product
- [ ] Upload an image
- [ ] Save product
- [ ] Verify image displays on product list
- [ ] Verify image displays on product detail page
- [ ] Verify image displays on shop page
- [ ] Check image URL (should be HTTPS)

### 4. Database Operations
- [ ] Can create new records
- [ ] Can update existing records
- [ ] Can delete records
- [ ] Relationships working correctly

### 5. File Storage
- [ ] Check storage link: `ls -la public/storage`
- [ ] Verify uploads directory exists: `ls -la storage/app/public/products`
- [ ] Check file permissions: `ls -la storage/app/public`

### 6. Performance
- [ ] Page load times acceptable
- [ ] Images load quickly
- [ ] Database queries optimized
- [ ] No N+1 query issues

### 7. Error Handling
- [ ] 404 page displays correctly
- [ ] 500 errors logged properly
- [ ] User-friendly error messages shown

## Troubleshooting Commands

If images don't work:

```bash
# Check APP_URL
php artisan tinker
>>> config('app.url')

# Check filesystem disk
>>> config('filesystems.default')

# Test storage URL generation
>>> Storage::disk('public')->url('test.jpg')

# Check if storage link exists
>>> file_exists(public_path('storage'))

# List uploaded files
>>> Storage::disk('public')->files('products')

# Check a product's image
>>> \App\Models\Product::first()->getImageUrl()
```

## Rollback Plan

If deployment fails:

1. **Revert to previous version** in Laravel Cloud dashboard
2. **Check logs** for error messages
3. **Verify environment variables** are set correctly
4. **Test locally** with production-like settings
5. **Fix issues** and redeploy

## Common Issues & Solutions

### Images Not Loading

**Symptoms**: 404 errors on image URLs

**Solutions**:
1. Run `php artisan storage:link`
2. Verify `FILESYSTEM_DISK=public` in environment
3. Check `APP_URL` is set correctly with HTTPS
4. Verify file permissions on `storage/app/public`

### Mixed Content Warnings

**Symptoms**: Browser blocks HTTP resources on HTTPS page

**Solutions**:
1. Ensure `APP_URL` uses HTTPS
2. Clear config cache: `php artisan config:clear && php artisan config:cache`
3. Check `AppServiceProvider` has `URL::forceScheme('https')`

### Database Connection Errors

**Symptoms**: SQLSTATE errors, connection refused

**Solutions**:
1. Verify database credentials in environment
2. Check database server is running
3. Ensure database exists
4. Run migrations: `php artisan migrate --force`

### Session/Cache Issues

**Symptoms**: Logged out unexpectedly, old data showing

**Solutions**:
1. Clear all caches: `php artisan cache:clear`
2. Verify `SESSION_DRIVER=database`
3. Check sessions table exists
4. Clear browser cookies

## Monitoring

After deployment, monitor:

- [ ] Application logs in Laravel Cloud dashboard
- [ ] Error rates
- [ ] Response times
- [ ] Database performance
- [ ] Storage usage
- [ ] User feedback

## Documentation

- [ ] Update README with production URL
- [ ] Document any manual configuration steps
- [ ] Update API documentation if applicable
- [ ] Note any known issues or limitations

## Success Criteria

Deployment is successful when:

- ✅ Application accessible at production URL
- ✅ All core features working
- ✅ Images upload and display correctly
- ✅ No critical errors in logs
- ✅ Performance acceptable
- ✅ Users can complete key workflows
- ✅ Data persists correctly

---

**Last Updated**: December 2024
**Platform**: Laravel Cloud
**Laravel Version**: 11.x
