<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for file uploads including
    | maximum file sizes, allowed file types, and storage settings.
    |
    */

    'max_file_size' => env('MAX_FILE_SIZE', 50 * 1024 * 1024), // 50MB in bytes

    'max_video_size' => env('MAX_VIDEO_SIZE', 50 * 1024 * 1024), // 50MB in bytes

    'max_image_size' => env('MAX_IMAGE_SIZE', 10 * 1024 * 1024), // 10MB in bytes

    'allowed_video_types' => [
        'mp4',
        'avi',
        'mov',
        'wmv',
        'flv',
        'webm',
        'mkv'
    ],

    'allowed_image_types' => [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp'
    ],

    'storage_disk' => 'public',

    'video_storage_path' => 'forums/videos',

    'image_storage_path' => 'forums/images',

    'temp_storage_path' => 'temp',
];



