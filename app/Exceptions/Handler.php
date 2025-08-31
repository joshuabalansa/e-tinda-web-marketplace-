<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle PostTooLargeException specifically
        $this->renderable(function (PostTooLargeException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'File upload too large',
                    'message' => 'The uploaded file exceeds the maximum allowed size. Please ensure your video is under 50MB.',
                    'max_size' => '50MB'
                ], 413);
            }

            // For web requests, redirect back with error message
            if ($request->isMethod('POST') || $request->isMethod('PUT')) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['video' => 'The uploaded file is too large. Maximum allowed size is 50MB. Please choose a smaller video file.'])
                    ->with('error', 'File upload failed: File too large');
            }

            // For GET requests, show a user-friendly error page
            return response()->view('errors.413', [], 413);
        });
    }
}







