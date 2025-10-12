<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmerReportsController extends Controller
{
    /**
     * Display farmer reports.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get farmer's products with order items count
        $products = $user->products()->withCount('orderItems')->get();

        // Get orders for farmer's products
        $orders = Order::whereHas('items.product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['items.product', 'user'])->get();

        // Get filter parameters
        $reportType = $request->get('report_type', 'all');
        $dateRange = $request->get('date_range', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Prepare reports data structure
        $reportsData = [
            'revenue' => [
                'summary' => (object) [
                    'total_revenue' => $orders->sum('total_amount'),
                    'total_orders' => $orders->count(),
                ]
            ],
            'products' => [
                'metrics' => (object) [
                    'total_products' => $products->count(),
                    'active_products' => $products->where('stock_quantity', '>', 0)->count(),
                    'low_stock_products' => $products->where('stock_quantity', '<=', 10)->count(),
                    'avg_product_price' => $products->avg('price_per_unit') ?? 0,
                ]
            ],
            'customers' => [
                'summary' => (object) [
                    'unique_customers' => $orders->unique('user_id')->count(),
                ],
                'top_customers' => $this->getTopCustomers($orders),
            ],
            'inventory' => [
                'summary' => (object) [
                    'total_value' => $products->sum(function($product) {
                        return $product->price_per_unit * $product->stock_quantity;
                    }),
                    'total_stock' => $products->sum('stock_quantity'),
                ]
            ],
            'sales' => [
                'summary' => [
                    'today' => (object) [
                        'total_revenue' => $orders->where('created_at', '>=', now()->startOfDay())->sum('total_amount'),
                        'total_quantity' => $orders->where('created_at', '>=', now()->startOfDay())->sum(function($order) {
                            return $order->items->sum('quantity');
                        }),
                    ],
                    'this_week' => (object) [
                        'total_revenue' => $orders->where('created_at', '>=', now()->startOfWeek())->sum('total_amount'),
                        'total_quantity' => $orders->where('created_at', '>=', now()->startOfWeek())->sum(function($order) {
                            return $order->items->sum('quantity');
                        }),
                    ],
                    'this_month' => (object) [
                        'total_revenue' => $orders->where('created_at', '>=', now()->startOfMonth())->sum('total_amount'),
                        'total_quantity' => $orders->where('created_at', '>=', now()->startOfMonth())->sum(function($order) {
                            return $order->items->sum('quantity');
                        }),
                    ],
                    'this_year' => (object) [
                        'total_revenue' => $orders->where('created_at', '>=', now()->startOfYear())->sum('total_amount'),
                        'total_quantity' => $orders->where('created_at', '>=', now()->startOfYear())->sum(function($order) {
                            return $order->items->sum('quantity');
                        }),
                    ],
                ],
                'top_products' => $this->getTopSellingProducts($products),
                'by_category' => $this->getSalesByCategory($products),
            ],
            'orders' => [
                'summary' => (object) [
                    'total_orders' => $orders->count(),
                    'avg_order_value' => $orders->avg('total_amount') ?? 0,
                    'total_items' => $orders->sum(function($order) {
                        return $order->items->sum('quantity');
                    }),
                ],
                'by_status' => $this->getOrdersByStatus($orders),
                'recent' => $this->getRecentOrders($orders),
            ],
            'performance' => [
                'customer_retention' => $this->calculateCustomerRetention($orders),
                'inventory_turnover' => $this->calculateInventoryTurnover($products),
            ],
        ];

        return view('farmer.reports.index', compact(
            'reportsData',
            'reportType',
            'dateRange',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get top customers.
     */
    private function getTopCustomers($orders)
    {
        return $orders->groupBy('user_id')->map(function($orders, $userId) {
            $firstOrder = $orders->first();
            return (object) [
                'name' => $firstOrder->first_name . ' ' . $firstOrder->last_name,
                'email' => $firstOrder->email,
                'order_count' => $orders->count(),
                'total_spent' => $orders->sum('total_amount'),
                'total_items' => $orders->sum(function($order) {
                    return $order->items->sum('quantity');
                }),
            ];
        })->sortByDesc('total_spent')->take(10)->values();
    }

    /**
     * Get top selling products.
     */
    private function getTopSellingProducts($products)
    {
        return $products->sortByDesc('order_items_count')->take(10)->map(function($product) {
            return (object) [
                'name' => $product->name,
                'category' => $product->category ?? 'Uncategorized',
                'total_sold' => $product->order_items_count ?? 0,
                'total_revenue' => $product->orderItems()->sum('price') ?? 0,
            ];
        });
    }

    /**
     * Get sales by category.
     */
    private function getSalesByCategory($products)
    {
        return $products->groupBy('category')->map(function($products, $category) {
            return (object) [
                'category' => $category ?? 'Uncategorized',
                'total_quantity' => $products->sum(function($product) {
                    return $product->orderItems()->sum('quantity');
                }),
                'total_revenue' => $products->sum(function($product) {
                    return $product->orderItems()->sum('price');
                }),
                'order_count' => $products->sum(function($product) {
                    return $product->orderItems()->distinct('order_id')->count();
                }),
            ];
        })->values();
    }

    /**
     * Get orders by status.
     */
    private function getOrdersByStatus($orders)
    {
        return $orders->groupBy('status')->map(function($orders, $status) {
            return (object) [
                'status' => $status,
                'order_count' => $orders->count(),
                'total_value' => $orders->sum('total_amount'),
            ];
        })->values();
    }

    /**
     * Get recent orders.
     */
    private function getRecentOrders($orders)
    {
        return $orders->where('created_at', '>=', now()->subDays(30))->take(20)->map(function($order) {
            $farmerItems = $order->items->filter(function($item) {
                return $item->product->user_id === auth()->id();
            });

            return $farmerItems->map(function($item) use ($order) {
                return (object) [
                    'id' => $order->id,
                    'product_name' => $item->product->name,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                ];
            });
        })->flatten();
    }

    /**
     * Calculate customer retention.
     */
    private function calculateCustomerRetention($orders)
    {
        $totalCustomers = $orders->unique('user_id')->count();
        $repeatCustomers = $orders->groupBy('user_id')->filter(function($orders) {
            return $orders->count() > 1;
        })->count();

        return $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100, 1) : 0;
    }

    /**
     * Calculate inventory turnover.
     */
    private function calculateInventoryTurnover($products)
    {
        $totalStock = $products->sum('stock_quantity');
        $totalSold = $products->sum(function($product) {
            return $product->orderItems()->sum('quantity');
        });

        return $totalStock > 0 ? round($totalSold / $totalStock, 1) : 0;
    }
}