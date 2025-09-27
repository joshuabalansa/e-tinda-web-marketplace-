<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Forum;
use App\Models\ForumReply;
use App\Models\OrderItem;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        // Get basic statistics
        $stats = $this->getBasicStats();

        // Get chart data
        $chartData = $this->getChartData();

        // Get recent activity
        $recentActivity = $this->getRecentActivity();

        // Get top performing data
        $topPerformers = $this->getTopPerformers();

        return view('admin.analytics.index', compact(
            'stats',
            'chartData',
            'recentActivity',
            'topPerformers'
        ));
    }

    /**
     * Get basic statistics for the dashboard.
     */
    private function getBasicStats()
    {
        return [
            'total_users' => User::count(),
            'total_farmers' => User::where('role', UserRole::Farmer)->count(),
            'total_buyers' => User::where('role', UserRole::Buyer)->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_forums' => Forum::count(),
            'total_replies' => ForumReply::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'active_forums' => Forum::where('status', 'active')->count(),
            'flagged_content' => Forum::where('is_flagged', true)->count() + ForumReply::where('is_flagged', true)->count(),
        ];
    }

    /**
     * Get chart data for various analytics charts.
     */
    private function getChartData()
    {
        // Monthly orders data
        $monthlyOrders = Order::selectRaw("strftime('%m', created_at) as month, COUNT(*) as count")
            ->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = $monthlyOrders[(string)$i] ?? 0;
        }

        // Monthly revenue data
        $monthlyRevenue = Order::selectRaw("strftime('%m', created_at) as month, SUM(total) as revenue")
            ->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
            ->where('status', 'completed')
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        $revenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenueData[$i] = $monthlyRevenue[(string)$i] ?? 0;
        }

        // User registration trends
        $userRegistrations = User::selectRaw("strftime('%m', created_at) as month, COUNT(*) as count")
            ->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $userData = [];
        for ($i = 1; $i <= 12; $i++) {
            $userData[$i] = $userRegistrations[(string)$i] ?? 0;
        }

        // Product categories distribution
        $categoryData = Product::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        // User role distribution
        $roleData = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        return [
            'monthly_orders' => $monthlyData,
            'monthly_revenue' => $revenueData,
            'user_registrations' => $userData,
            'category_distribution' => $categoryData,
            'role_distribution' => $roleData,
        ];
    }

    /**
     * Get recent activity data.
     */
    private function getRecentActivity()
    {
        return [
            'recent_orders' => Order::with('user')->latest()->take(5)->get(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_products' => Product::with('user')->latest()->take(5)->get(),
            'recent_forums' => Forum::with('user')->latest()->take(5)->get(),
        ];
    }

    /**
     * Get top performing data.
     */
    private function getTopPerformers()
    {
        return [
            'top_farmers' => User::where('role', UserRole::Farmer)
                ->withCount('products')
                ->orderBy('products_count', 'desc')
                ->take(5)
                ->get(),
            'top_products' => Product::with('user')
                ->withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->take(5)
                ->get(),
            'top_categories' => Product::selectRaw('category, COUNT(*) as product_count')
                ->groupBy('category')
                ->orderBy('product_count', 'desc')
                ->take(5)
                ->get(),
            'most_active_forums' => Forum::with('user')
                ->withCount('replies')
                ->orderBy('replies_count', 'desc')
                ->take(5)
                ->get(),
        ];
    }

    /**
     * Get analytics data for AJAX requests.
     */
    public function getData(Request $request)
    {
        $type = $request->get('type', 'orders');

        switch ($type) {
            case 'orders':
                return $this->getOrdersData($request);
            case 'revenue':
                return $this->getRevenueData($request);
            case 'users':
                return $this->getUsersData($request);
            case 'products':
                return $this->getProductsData($request);
            case 'forums':
                return $this->getForumsData($request);
            default:
                return response()->json(['error' => 'Invalid data type'], 400);
        }
    }

    /**
     * Get orders analytics data.
     */
    private function getOrdersData($request)
    {
        $period = $request->get('period', 'monthly');

        if ($period === 'daily') {
            $data = Order::selectRaw("strftime('%d', created_at) as day, COUNT(*) as count")
                ->whereRaw("strftime('%Y-%m', created_at) = ?", [date('Y-m')])
                ->groupBy('day')
                ->pluck('count', 'day')
                ->toArray();
        } else {
            $data = Order::selectRaw("strftime('%m', created_at) as month, COUNT(*) as count")
                ->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
                ->groupBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }

        return response()->json($data);
    }

    /**
     * Get revenue analytics data.
     */
    private function getRevenueData($request)
    {
        $period = $request->get('period', 'monthly');

        if ($period === 'daily') {
            $data = Order::selectRaw("strftime('%d', created_at) as day, SUM(total) as revenue")
                ->whereRaw("strftime('%Y-%m', created_at) = ?", [date('Y-m')])
                ->where('status', 'completed')
                ->groupBy('day')
                ->pluck('revenue', 'day')
                ->toArray();
        } else {
            $data = Order::selectRaw("strftime('%m', created_at) as month, SUM(total) as revenue")
                ->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
                ->where('status', 'completed')
                ->groupBy('month')
                ->pluck('revenue', 'month')
                ->toArray();
        }

        return response()->json($data);
    }

    /**
     * Get users analytics data.
     */
    private function getUsersData($request)
    {
        $period = $request->get('period', 'monthly');

        if ($period === 'daily') {
            $data = User::selectRaw("strftime('%d', created_at) as day, COUNT(*) as count")
                ->whereRaw("strftime('%Y-%m', created_at) = ?", [date('Y-m')])
                ->groupBy('day')
                ->pluck('count', 'day')
                ->toArray();
        } else {
            $data = User::selectRaw("strftime('%m', created_at) as month, COUNT(*) as count")
                ->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
                ->groupBy('month')
                ->pluck('count', 'month')
                ->toArray();
        }

        return response()->json($data);
    }

    /**
     * Get products analytics data.
     */
    private function getProductsData($request)
    {
        $data = Product::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        return response()->json($data);
    }

    /**
     * Get forums analytics data.
     */
    private function getForumsData($request)
    {
        $data = Forum::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        return response()->json($data);
    }
}