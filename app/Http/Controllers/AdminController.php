<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Forum;
use App\Enums\UserRole;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get statistics for the admin dashboard
        $stats = [
            'total_users' => User::count(),
            'total_farmers' => User::where('role', UserRole::Farmer)->count(),
            'total_buyers' => User::where('role', UserRole::Buyer)->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_forums' => Forum::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
        ];

        // Get recent users
        $recent_users = User::latest()->take(5)->get();

        // Get recent orders
        $recent_orders = Order::with('user')->latest()->take(5)->get();

        // Get recent products
        $recent_products = Product::with('user')->latest()->take(5)->get();

        // Get monthly order data for charts (MySQL compatible)
        $monthly_orders = Order::selectRaw("DATE_FORMAT(created_at, '%m') as month, COUNT(*) as count")
            ->whereRaw("YEAR(created_at) = ?", [date('Y')])
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0 and ensure integer keys
        $monthly_data = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthly_data[$i] = $monthly_orders[(string)$i] ?? 0;
        }

        return view('admin.index', compact(
            'stats',
            'recent_users',
            'recent_orders',
            'recent_products',
            'monthly_data'
        ));
    }

    /**
     * Display all users for management.
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter by status (assuming we have an 'is_active' field)
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $users = $query->with(['products', 'orders'])->paginate(15);

        $roleCounts = [
            'admin' => User::where('role', UserRole::Admin)->count(),
            'farmer' => User::where('role', UserRole::Farmer)->count(),
            'buyer' => User::where('role', UserRole::Buyer)->count(),
        ];

        return view('admin.users.index', compact('users', 'roleCounts'));
    }

    /**
     * Show a specific user's details.
     */
    public function showUser(User $user)
    {
        $user->load(['products', 'orders', 'reviews', 'wishlist']);

        $stats = [
            'total_products' => $user->products->count(),
            'total_orders' => $user->orders->count(),
            'total_reviews' => $user->reviews->count(),
            'total_wishlist' => $user->wishlist->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function storeUser(Request $request)
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['required', 'string', 'in:farmer,buyer'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];

        // Add location validation for farmers
        if ($request->role === 'farmer') {
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
            'role' => UserRole::from($request->role),
            'phone' => $request->phone,
            'address' => $request->address,
            'business_name' => $request->business_name,
            'is_active' => $request->has('is_active') ? true : true, // Default to active
        ];

        // Add location fields for farmers
        if ($request->role === 'farmer') {
            $userData['farm_address'] = $request->farm_address;
            $userData['city'] = $request->city;
            $userData['state'] = $request->state;
            $userData['zip_code'] = $request->zip_code;
            $userData['country'] = $request->country ?? 'Philippines';
        }

        $user = User::create($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Show the form for editing a user.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update a user's information.
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,farmer,buyer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'business_name' => 'nullable|string|max:255',
            'business_type' => 'nullable|string|max:100',
            'farm_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'delivery_radius' => 'nullable|integer|min:0|max:100',
            'coordinates' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => UserRole::from($request->role),
            'phone' => $request->phone,
            'address' => $request->address,
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'farm_address' => $request->farm_address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'delivery_radius' => $request->delivery_radius,
            'coordinates' => $request->coordinates,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Toggle user active status.
     */
    public function toggleUserStatus(User $user)
    {
        // Prevent admin from deactivating themselves
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot deactivate your own account!');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "User {$status} successfully!");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Show user details (alias for showUser method).
     */
    public function userDetails(User $user)
    {
        return $this->showUser($user);
    }

    /**
     * Display admin settings page.
     */
    public function settings()
    {
        return view('admin.settings', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Display admin reports.
     */
    public function reports()
    {
        // Get comprehensive statistics for reports
        $stats = [
            'total_users' => User::count(),
            'total_farmers' => User::where('role', UserRole::Farmer)->count(),
            'total_buyers' => User::where('role', UserRole::Buyer)->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_forums' => Forum::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
        ];

        // Get monthly data for charts
        $monthly_orders = Order::selectRaw("DATE_FORMAT(created_at, '%m') as month, COUNT(*) as count")
            ->whereRaw("YEAR(created_at) = ?", [date('Y')])
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthly_data = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthly_data[$i] = $monthly_orders[(string)$i] ?? 0;
        }

        // Get recent activity
        $recent_orders = Order::with('user')->latest()->take(10)->get();
        $recent_users = User::latest()->take(10)->get();

        return view('admin.reports.index', compact('stats', 'monthly_data', 'recent_orders', 'recent_users'));
    }
}
