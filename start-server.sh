#!/bin/bash

# Start Laravel server with increased PHP limits for video uploads
export PHP_CLI_SERVER_WORKERS=4

# Set PHP configuration for large file uploads
php -d upload_max_filesize=100M \
    -d post_max_size=100M \
    -d max_execution_time=300 \
    -d memory_limit=256M \
    -d max_input_time=300 \
    artisan serve --host=127.0.0.1 --port=8000
