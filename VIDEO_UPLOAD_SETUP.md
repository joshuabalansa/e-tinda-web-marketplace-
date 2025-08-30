# Video Upload Setup Guide

## Overview
This guide explains how to set up video uploads for the forum system and resolve the "Content Too Large" error.

## Problem
The error occurs because the default PHP configuration has very low limits:
- `post_max_size`: 8M (8 MB)
- `upload_max_filesize`: 2M (2 MB)

But the forum system is designed to handle videos up to 50MB.

## Solution 1: For Laravel Development Server (`php artisan serve`)

### Step 1: Use the provided startup script
```bash
# On macOS/Linux
./start-server.sh

# On Windows
start-server.bat
```

### Step 2: Or manually start with PHP flags
```bash
php -d upload_max_filesize=100M \
    -d post_max_size=100M \
    -d max_execution_time=300 \
    -d memory_limit=256M \
    -d max_input_time=300 \
    artisan serve
```

## Solution 2: For Production Servers (Apache/Nginx)

### Apache (.htaccess)
Add to your `.htaccess` file:
```apache
php_value upload_max_filesize 100M
php_value post_max_size 100M
php_value max_execution_time 300
php_value memory_limit 256M
```

### Nginx
Add to your nginx configuration:
```nginx
client_max_body_size 100M;
```

### PHP Configuration
Update your `php.ini`:
```ini
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
memory_limit = 256M
max_input_time = 300
```

## Solution 3: Environment Variables

Add to your `.env` file:
```env
MAX_FILE_SIZE=52428800
MAX_VIDEO_SIZE=52428800
MAX_IMAGE_SIZE=10485760
```

## Configuration Files

### Upload Configuration (`config/upload.php`)
- `max_video_size`: Maximum video file size (default: 50MB)
- `allowed_video_types`: Supported video formats
- `storage_disk`: Storage disk to use
- `video_storage_path`: Path for storing videos

### Custom Error Handler (`app/Exceptions/Handler.php`)
- Handles `PostTooLargeException` gracefully
- Provides user-friendly error messages
- Redirects back to forms with proper error display

### Custom Middleware (`app/Http/Middleware/HandleLargeUploads.php`)
- Pre-validates file sizes before processing
- Prevents server crashes from large files
- Applied globally to all web routes via the `web` middleware group

## Features

### Client-Side Validation
- File size checking before upload
- File type validation
- Immediate user feedback

### Server-Side Validation
- Multiple validation layers
- Configurable file size limits
- Secure file handling

### Error Handling
- Custom error pages for large files
- User-friendly error messages
- Graceful fallbacks

## Testing

### Test with Small Files
1. Try uploading a video under 50MB
2. Should work without issues

### Test with Large Files
1. Try uploading a video over 50MB
2. Should show clear error message
3. Form should retain input data

### Test Error Pages
1. Navigate to `/forums/create` with large file
2. Should see custom error page

## Troubleshooting

### Still Getting "Content Too Large" Error?
1. **Check PHP configuration**: `php -i | grep -E "(post_max_size|upload_max_filesize)"`
2. **Restart web server**: Configuration changes require server restart
3. **Check file permissions**: Ensure startup scripts are executable
4. **Verify middleware**: Check if `HandleLargeUploads` middleware is in the `web` middleware group
5. **Check middleware registration**: Ensure `HandleLargeUploads` class is properly registered in `app/Http/Kernel.php`

### File Uploads Not Working?
1. **Check storage disk**: Ensure `public` disk is configured
2. **Check file permissions**: Ensure storage directories are writable
3. **Check routes**: Verify middleware is applied to upload routes

### Performance Issues?
1. **Reduce file size limits**: Adjust `MAX_VIDEO_SIZE` in config
2. **Use chunked uploads**: Implement progressive file uploads
3. **External hosting**: Consider using video hosting services

## Security Considerations

- File type validation prevents malicious uploads
- File size limits prevent DoS attacks
- Secure file storage with proper permissions
- Input sanitization and validation

## Support

If you continue to experience issues:
1. Check the Laravel logs in `storage/logs/`
2. Verify PHP configuration with `php -i`
3. Test with different file sizes and types
4. Check web server error logs
