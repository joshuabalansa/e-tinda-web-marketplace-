<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;

class DashboardController extends Controller
{
    /**
     * Redirects the authenticated user to their role-specific dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role->value === UserRole::Admin->value) {

            return redirect()->intended(route('admin.dashboard', absolute: false));

        } elseif ($user->role->value === UserRole::Farmer->value) {

            return redirect()->intended(route('farmer.dashboard', absolute: false));

        } else {

            return redirect()->intended(route('buyer.dashboard', absolute: false));
        }
    }
}
