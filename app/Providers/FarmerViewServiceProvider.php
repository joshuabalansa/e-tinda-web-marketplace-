<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class FarmerViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share stats with farmer layout
        View::composer('layouts.farmer', function ($view) {
            if (Auth::check() && Auth::user()->role === 'farmer') {
                $farmerId = Auth::id();

                $stats = [
                    'pending_orders' => Order::whereHas('items.product', function($query) use ($farmerId) {
                        $query->where('user_id', $farmerId);
                    })->where('status', 'pending')->count(),
                ];

                $view->with('stats', $stats);
            }
        });
    }
}
