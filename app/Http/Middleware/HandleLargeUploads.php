<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleLargeUploads
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only process requests that might have file uploads
        if ($request->isMethod('POST') || $request->isMethod('PUT')) {
            // Check if the request has video files
            if ($request->hasFile('video')) {
                $video = $request->file('video');

                // Get max size (50MB default)
                $maxSize = 50 * 1024 * 1024; // 50MB in bytes

                // Check file size
                if ($video->getSize() > $maxSize) {
                    // For AJAX requests, return JSON response
                    if ($request->expectsJson()) {
                        return response()->json([
                            'error' => 'File too large',
                            'message' => 'Video file size must be less than ' . round($maxSize / (1024 * 1024), 0) . 'MB. Current size: ' . round($video->getSize() / (1024 * 1024), 2) . 'MB'
                        ], 413);
                    }

                    // For regular requests, redirect back with error
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['video' => 'Video file size must be less than ' . round($maxSize / (1024 * 1024), 0) . 'MB. Current size: ' . round($video->getSize() / (1024 * 1024), 2) . 'MB']);
                }
            }
        }

        return $next($request);
    }
}
