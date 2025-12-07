<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmerAnalyticsController extends Controller
{
    /**
     * Display farmer analytics dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get farmer's products
        $products = $user->products();

        // Get orders for farmer's products
        $orders = Order::whereHas('items.product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['items.product', 'items'])->get();

        // Calculate revenue only for farmer's products
        $currentMonthRevenue = $this->calculateFarmerRevenue($orders->where('created_at', '>=', now()->startOfMonth()), $user->id);

        // Prepare analytics data structure
        $analyticsData = [
            'revenue' => [
                'current_month' => $currentMonthRevenue,
                'growth_percentage' => $this->calculateGrowthPercentage($orders, $user->id),
                'monthly_revenue' => $this->getMonthlyRevenue($orders, $user->id),
            ],
            'orders' => [
                'monthly_orders' => $this->getMonthlyOrders($orders),
                'status_distribution' => $this->getOrderStatusDistribution($orders),
            ],
            'products' => [
                'top_products' => $this->getTopProducts($products),
                'category_performance' => $this->getCategoryPerformance($products),
            ],
            'inventory' => [
                'low_stock' => $products->where('stock_quantity', '<=', 10)->get(),
                'stock_levels' => $products->take(10)->get(),
            ],
            'sales' => [
                'monthly' => $this->getMonthlySales($orders),
                'daily' => $this->getDailySales($orders),
            ],
            'customers' => [
                'monthly_customers' => $this->getMonthlyCustomers($orders),
            ],
        ];

        return view('farmer.analytics.index', compact('analyticsData'));
    }

    /**
     * Calculate revenue for farmer's products only.
     */
    private function calculateFarmerRevenue($orders, $farmerId)
    {
        $revenue = 0;
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if ($item->product && $item->product->user_id == $farmerId) {
                    $revenue += $item->price * $item->quantity;
                }
            }
        }
        return $revenue;
    }

    /**
     * Calculate growth percentage for revenue.
     */
    private function calculateGrowthPercentage($orders, $farmerId)
    {
        $currentMonth = $this->calculateFarmerRevenue(
            $orders->where('created_at', '>=', now()->startOfMonth()),
            $farmerId
        );
        $lastMonth = $this->calculateFarmerRevenue(
            $orders->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]),
            $farmerId
        );

        if ($lastMonth == 0) return 0;

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * Get monthly revenue data.
     */
    private function getMonthlyRevenue($orders, $farmerId)
    {
        return $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m');
        })->map(function($monthOrders, $month) use ($farmerId) {
            $revenue = 0;
            foreach ($monthOrders as $order) {
                foreach ($order->items as $item) {
                    if ($item->product && $item->product->user_id == $farmerId) {
                        $revenue += $item->price * $item->quantity;
                    }
                }
            }
            return [
                'month' => (int) substr($month, 5),
                'year' => (int) substr($month, 0, 4),
                'revenue' => $revenue
            ];
        })->values();
    }

    /**
     * Get monthly orders data.
     */
    private function getMonthlyOrders($orders)
    {
        return $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m');
        })->map(function($orders, $month) {
            return [
                'month' => (int) substr($month, 5),
                'year' => (int) substr($month, 0, 4),
                'order_count' => $orders->count()
            ];
        })->values();
    }

    /**
     * Get order status distribution.
     */
    private function getOrderStatusDistribution($orders)
    {
        return $orders->groupBy('status')->map(function($orders, $status) {
            return [
                'status' => $status,
                'order_count' => $orders->count()
            ];
        })->values();
    }

    /**
     * Get top products.
     */
    private function getTopProducts($products)
    {
        return $products->withCount('orderItems')->orderBy('order_items_count', 'desc')->take(10)->get()->map(function($product) {
            return [
                'name' => $product->name,
                'total_sold' => $product->order_items_count ?? 0
            ];
        });
    }

    /**
     * Get category performance.
     */
    private function getCategoryPerformance($products)
    {
        return $products->get()->groupBy('category')->map(function($categoryProducts, $category) {
            $totalRevenue = 0;
            foreach ($categoryProducts as $product) {
                $orderItems = $product->orderItems;
                foreach ($orderItems as $item) {
                    $totalRevenue += $item->price * $item->quantity;
                }
            }
            return [
                'category' => $category ?: 'Uncategorized',
                'total_revenue' => $totalRevenue
            ];
        })->values();
    }

    /**
     * Get monthly sales data.
     */
    private function getMonthlySales($orders)
    {
        return $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m');
        })->map(function($orders, $month) {
            return [
                'month' => (int) substr($month, 5),
                'year' => (int) substr($month, 0, 4),
                'total_quantity' => $orders->sum(function($order) {
                    return $order->items->sum('quantity');
                })
            ];
        })->values();
    }

    /**
     * Get daily sales data.
     */
    private function getDailySales($orders)
    {
        return $orders->where('created_at', '>=', now()->startOfDay())->map(function($order) {
            return [
                'total_quantity' => $order->items->sum('quantity')
            ];
        });
    }

    /**
     * Get monthly customers data.
     */
    private function getMonthlyCustomers($orders)
    {
        return $orders->groupBy(function($order) {
            return $order->created_at->format('Y-m');
        })->map(function($orders, $month) {
            return [
                'month' => (int) substr($month, 5),
                'year' => (int) substr($month, 0, 4),
                'customer_count' => $orders->unique('user_id')->count()
            ];
        })->values();
    }
}