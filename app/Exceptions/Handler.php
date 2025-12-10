<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            // Log all exceptions in production for debugging
            if (app()->environment('production')) {
                \Log::error('Exception occurred', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
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

        // Handle database query exceptions (missing columns, connection issues)
        $this->renderable(function (QueryException $e, $request) {
            // Check if it's a missing column error
            if (str_contains($e->getMessage(), 'has no column named')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Database configuration error',
                        'message' => 'The database schema is not up to date. Please run migrations.',
                    ], 500);
                }

                // In production, show a user-friendly error page
                if (app()->environment('production')) {
                    return response()->view('errors.500', [
                        'message' => 'Database configuration issue. Please contact support if this persists.',
                    ], 500);
                }

                // In development, show the actual error
                return response()->view('errors.500', [
                    'message' => $e->getMessage(),
                ], 500);
            }

            // Check if it's a connection error
            if (str_contains($e->getMessage(), 'Connection') || str_contains($e->getMessage(), 'SQLSTATE')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Database connection error',
                        'message' => 'Unable to connect to the database. Please try again later.',
                    ], 503);
                }

                return response()->view('errors.503', [
                    'message' => 'Service temporarily unavailable. Please try again later.',
                ], 503);
            }
        });

        // Handle middleware binding resolution errors
        $this->renderable(function (BindingResolutionException $e, $request) {
            // Check if it's a middleware resolution error
            if (str_contains($e->getMessage(), 'Target class') && str_contains($e->getMessage(), 'does not exist')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Application configuration error',
                        'message' => 'Please clear route cache and try again.',
                    ], 500);
                }

                // In production, show a user-friendly error
                if (app()->environment('production')) {
                    return response()->view('errors.500', [
                        'message' => 'Application configuration issue. Please contact support if this persists.',
                    ], 500);
                }
            }
        });

        // Handle model not found exceptions
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Resource not found',
                    'message' => 'The requested resource could not be found.',
                ], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        // Handle 404 errors gracefully
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Page not found',
                    'message' => 'The requested page could not be found.',
                ], 404);
            }

            return response()->view('errors.404', [], 404);
        });
    }
}


































