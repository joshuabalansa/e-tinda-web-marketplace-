<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CartController;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Default role to buyer if not provided
        $role = $request->input('role', 'buyer');

        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'string', 'in:farmer,buyer'],
        ];

        // Add location validation for farmers
        if ($role === 'farmer') {
            $validationRules['farm_address'] = ['required', 'string', 'max:500'];
            $validationRules['city'] = ['required', 'string', 'max:100'];
            $validationRules['state'] = ['required', 'string', 'max:100'];
            $validationRules['zip_code'] = ['nullable', 'string', 'max:20'];
            $validationRules['country'] = ['nullable', 'string', 'max:100'];
        }

        $request->validate($validationRules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ];

        // Add location fields for farmers
        if ($role === 'farmer') {
            $userData['farm_address'] = $request->farm_address;
            $userData['city'] = $request->city;
            $userData['state'] = $request->state;
            $userData['zip_code'] = $request->zip_code;
            $userData['country'] = $request->country ?? 'Philippines';
        }

        $user = User::create($userData);

        event(new Registered($user));

        Auth::login($user);

        // Sync session cart to database when user registers
        $cartController = new CartController();
        $cartController->syncSessionToDatabase($user->id);

        return redirect(route('dashboard', absolute: false));
    }
}
