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

        // Get all order items for this farmer's products
        $farmerOrderItems = \App\Models\OrderItem::whereHas('product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['product', 'order'])->get();

        // Get filter parameters
        $reportType = $request->get('report_type', 'all');
        $dateRange = $request->get('date_range', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Apply date filters if needed
        if ($dateRange !== 'all') {
            $filteredOrderItems = $this->applyDateFilter($farmerOrderItems, $dateRange, $startDate, $endDate);
            $filteredOrders = $this->applyDateFilterToOrders($orders, $dateRange, $startDate, $endDate);
        } else {
            $filteredOrderItems = $farmerOrderItems;
            $filteredOrders = $orders;
        }

        // Calculate total revenue from order items (not order totals)
        $totalRevenue = $farmerOrderItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Prepare reports data structure
        $reportsData = [
            'revenue' => [
                'summary' => (object) [
                    'total_revenue' => $totalRevenue,
                    'total_orders' => $orders->unique('id')->count(),
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
                        'total_revenue' => $farmerOrderItems->where('created_at', '>=', now()->startOfDay())
                            ->sum(function($item) { return $item->price * $item->quantity; }),
                        'total_quantity' => $farmerOrderItems->where('created_at', '>=', now()->startOfDay())
                            ->sum('quantity'),
                    ],
                    'this_week' => (object) [
                        'total_revenue' => $farmerOrderItems->where('created_at', '>=', now()->startOfWeek())
                            ->sum(function($item) { return $item->price * $item->quantity; }),
                        'total_quantity' => $farmerOrderItems->where('created_at', '>=', now()->startOfWeek())
                            ->sum('quantity'),
                    ],
                    'this_month' => (object) [
                        'total_revenue' => $farmerOrderItems->where('created_at', '>=', now()->startOfMonth())
                            ->sum(function($item) { return $item->price * $item->quantity; }),
                        'total_quantity' => $farmerOrderItems->where('created_at', '>=', now()->startOfMonth())
                            ->sum('quantity'),
                    ],
                    'this_year' => (object) [
                        'total_revenue' => $farmerOrderItems->where('created_at', '>=', now()->startOfYear())
                            ->sum(function($item) { return $item->price * $item->quantity; }),
                        'total_quantity' => $farmerOrderItems->where('created_at', '>=', now()->startOfYear())
                            ->sum('quantity'),
                    ],
                ],
                'top_products' => $this->getTopSellingProducts($farmerOrderItems),
                'by_category' => $this->getSalesByCategory($farmerOrderItems),
            ],
            'orders' => [
                'summary' => (object) [
                    'total_orders' => $orders->unique('id')->count(),
                    'avg_order_value' => $farmerOrderItems->count() > 0
                        ? ($farmerOrderItems->sum(function($item) { return $item->price * $item->quantity; }) / $orders->unique('id')->count())
                        : 0,
                    'total_items' => $farmerOrderItems->sum('quantity'),
                ],
                'by_status' => $this->getOrdersByStatus($orders, $farmerOrderItems),
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
        $user = Auth::user();
        return $orders->groupBy('user_id')->map(function($orders, $userId) use ($user) {
            $firstOrder = $orders->first();
            // Calculate total spent on this farmer's products only
            $totalSpent = 0;
            $totalItems = 0;
            foreach ($orders as $order) {
                $farmerItems = $order->items->filter(function($item) use ($user) {
                    return $item->product && $item->product->user_id === $user->id;
                });
                $totalSpent += $farmerItems->sum(function($item) {
                    return $item->price * $item->quantity;
                });
                $totalItems += $farmerItems->sum('quantity');
            }

            return (object) [
                'name' => ($firstOrder->first_name ?? '') . ' ' . ($firstOrder->last_name ?? ''),
                'email' => $firstOrder->email ?? '',
                'order_count' => $orders->count(),
                'total_spent' => $totalSpent,
                'total_items' => $totalItems,
            ];
        })->sortByDesc('total_spent')->take(10)->values();
    }

    /**
     * Get top selling products.
     */
    private function getTopSellingProducts($orderItems)
    {
        return $orderItems->groupBy('product_id')->map(function($items, $productId) {
            $product = $items->first()->product;
            return (object) [
                'name' => $product->name ?? 'Unknown',
                'category' => $product->category ?? 'Uncategorized',
                'total_sold' => $items->sum('quantity'),
                'total_revenue' => $items->sum(function($item) {
                    return $item->price * $item->quantity;
                }),
            ];
        })->sortByDesc('total_revenue')->take(10)->values();
    }

    /**
     * Get sales by category.
     */
    private function getSalesByCategory($orderItems)
    {
        return $orderItems->groupBy(function($item) {
            return $item->product->category ?? 'Uncategorized';
        })->map(function($items, $category) {
            return (object) [
                'category' => $category ?? 'Uncategorized',
                'total_quantity' => $items->sum('quantity'),
                'total_revenue' => $items->sum(function($item) {
                    return $item->price * $item->quantity;
                }),
                'order_count' => $items->pluck('order_id')->unique()->count(),
            ];
        })->values();
    }

    /**
     * Get orders by status.
     */
    private function getOrdersByStatus($orders, $orderItems)
    {
        $user = Auth::user();
        return $orders->groupBy('status')->map(function($orders, $status) use ($orderItems, $user) {
            // Get order IDs for this status
            $orderIds = $orders->pluck('id');
            // Calculate total value from order items for this farmer's products
            $totalValue = $orderItems->whereIn('order_id', $orderIds)
                ->sum(function($item) {
                    return $item->price * $item->quantity;
                });

            return (object) [
                'status' => $status,
                'order_count' => $orders->unique('id')->count(),
                'total_value' => $totalValue,
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

    /**
     * Apply date filter to order items.
     */
    private function applyDateFilter($orderItems, $dateRange, $startDate = null, $endDate = null)
    {
        switch ($dateRange) {
            case 'today':
                return $orderItems->filter(function($item) {
                    return $item->created_at >= now()->startOfDay();
                });
            case 'week':
                return $orderItems->filter(function($item) {
                    return $item->created_at >= now()->startOfWeek();
                });
            case 'month':
                return $orderItems->filter(function($item) {
                    return $item->created_at >= now()->startOfMonth();
                });
            case 'quarter':
                return $orderItems->filter(function($item) {
                    return $item->created_at >= now()->startOfQuarter();
                });
            case 'year':
                return $orderItems->filter(function($item) {
                    return $item->created_at >= now()->startOfYear();
                });
            case 'custom':
                if ($startDate && $endDate) {
                    return $orderItems->filter(function($item) use ($startDate, $endDate) {
                        return $item->created_at >= $startDate && $item->created_at <= $endDate;
                    });
                }
                return $orderItems;
            default:
                return $orderItems;
        }
    }

    /**
     * Apply date filter to orders.
     */
    private function applyDateFilterToOrders($orders, $dateRange, $startDate = null, $endDate = null)
    {
        switch ($dateRange) {
            case 'today':
                return $orders->filter(function($order) {
                    return $order->created_at >= now()->startOfDay();
                });
            case 'week':
                return $orders->filter(function($order) {
                    return $order->created_at >= now()->startOfWeek();
                });
            case 'month':
                return $orders->filter(function($order) {
                    return $order->created_at >= now()->startOfMonth();
                });
            case 'quarter':
                return $orders->filter(function($order) {
                    return $order->created_at >= now()->startOfQuarter();
                });
            case 'year':
                return $orders->filter(function($order) {
                    return $order->created_at >= now()->startOfYear();
                });
            case 'custom':
                if ($startDate && $endDate) {
                    return $orders->filter(function($order) use ($startDate, $endDate) {
                        return $order->created_at >= $startDate && $order->created_at <= $endDate;
                    });
                }
                return $orders;
            default:
                return $orders;
        }
    }

    /**
     * Export report data.
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'sales');
        $format = $request->get('format', 'csv');

        if ($format !== 'csv') {
            return redirect()->back()->with('error', 'Unsupported export format');
        }

        switch ($type) {
            case 'sales':
                return $this->exportSales();
            case 'products':
                return $this->exportProducts();
            case 'inventory':
                return $this->exportInventory();
            case 'orders':
                return $this->exportOrders();
            case 'revenue':
                return $this->exportRevenue();
            case 'customers':
                return $this->exportCustomers();
            default:
                return redirect()->back()->with('error', 'Invalid export type');
        }
    }

    /**
     * Export sales report.
     */
    private function exportSales()
    {
        $user = Auth::user();
        $orderItems = \App\Models\OrderItem::whereHas('product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['product', 'order'])->get();

        $filename = 'sales_report_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orderItems) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Product', 'Category', 'Quantity', 'Price', 'Total', 'Date']);

            foreach ($orderItems as $item) {
                fputcsv($file, [
                    $item->order_id,
                    $item->product->name ?? 'N/A',
                    $item->product->category ?? 'N/A',
                    $item->quantity,
                    '₱' . number_format($item->price, 2),
                    '₱' . number_format($item->price * $item->quantity, 2),
                    $item->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return \Response::stream($callback, 200, $headers);
    }

    /**
     * Export products report.
     */
    private function exportProducts()
    {
        $user = Auth::user();
        $products = $user->products()->withCount('orderItems')->get();

        $filename = 'products_report_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Category', 'Price', 'Stock', 'Times Sold', 'Status']);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->category ?? 'Uncategorized',
                    '₱' . number_format($product->price_per_unit, 2),
                    $product->stock_quantity,
                    $product->order_items_count ?? 0,
                    ucfirst($product->status ?? 'active')
                ]);
            }

            fclose($file);
        };

        return \Response::stream($callback, 200, $headers);
    }

    /**
     * Export inventory report.
     */
    private function exportInventory()
    {
        $user = Auth::user();
        $products = $user->products()->get();

        $filename = 'inventory_report_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Product', 'Category', 'Stock Quantity', 'Price Per Unit', 'Total Value', 'Status']);

            foreach ($products as $product) {
                $totalValue = $product->price_per_unit * $product->stock_quantity;
                $status = $product->stock_quantity <= 10 ? 'Low Stock' : 'In Stock';
                
                fputcsv($file, [
                    $product->name,
                    $product->category ?? 'Uncategorized',
                    $product->stock_quantity,
                    '₱' . number_format($product->price_per_unit, 2),
                    '₱' . number_format($totalValue, 2),
                    $status
                ]);
            }

            fclose($file);
        };

        return \Response::stream($callback, 200, $headers);
    }

    /**
     * Export orders report.
     */
    private function exportOrders()
    {
        $user = Auth::user();
        $orders = Order::whereHas('items.product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['items.product'])->get();

        $filename = 'orders_report_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders, $user) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Customer Name', 'Customer Email', 'Status', 'Items', 'Total Value', 'Date']);

            foreach ($orders as $order) {
                $farmerItems = $order->items->filter(function($item) use ($user) {
                    return $item->product && $item->product->user_id === $user->id;
                });
                
                $totalValue = $farmerItems->sum(function($item) {
                    return $item->price * $item->quantity;
                });
                
                $itemCount = $farmerItems->sum('quantity');

                fputcsv($file, [
                    $order->id,
                    ($order->first_name ?? '') . ' ' . ($order->last_name ?? ''),
                    $order->email ?? 'N/A',
                    ucfirst($order->status),
                    $itemCount,
                    '₱' . number_format($totalValue, 2),
                    $order->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return \Response::stream($callback, 200, $headers);
    }

    /**
     * Export revenue report.
     */
    private function exportRevenue()
    {
        $user = Auth::user();
        $orderItems = \App\Models\OrderItem::whereHas('product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['product', 'order'])->get();

        // Group by month
        $monthlyRevenue = $orderItems->groupBy(function($item) {
            return $item->created_at->format('Y-m');
        });

        $filename = 'revenue_report_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($monthlyRevenue) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Month', 'Orders', 'Items Sold', 'Total Revenue']);

            foreach ($monthlyRevenue as $month => $items) {
                $revenue = $items->sum(function($item) {
                    return $item->price * $item->quantity;
                });
                
                fputcsv($file, [
                    \Carbon\Carbon::parse($month . '-01')->format('F Y'),
                    $items->pluck('order_id')->unique()->count(),
                    $items->sum('quantity'),
                    '₱' . number_format($revenue, 2)
                ]);
            }

            fclose($file);
        };

        return \Response::stream($callback, 200, $headers);
    }

    /**
     * Export customers report.
     */
    private function exportCustomers()
    {
        $user = Auth::user();
        $orders = Order::whereHas('items.product', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['items.product'])->get();

        $customers = $orders->groupBy('user_id')->map(function($customerOrders, $userId) use ($user) {
            $firstOrder = $customerOrders->first();
            $totalSpent = 0;
            $totalItems = 0;
            
            foreach ($customerOrders as $order) {
                $farmerItems = $order->items->filter(function($item) use ($user) {
                    return $item->product && $item->product->user_id === $user->id;
                });
                $totalSpent += $farmerItems->sum(function($item) {
                    return $item->price * $item->quantity;
                });
                $totalItems += $farmerItems->sum('quantity');
            }

            return [
                'name' => ($firstOrder->first_name ?? '') . ' ' . ($firstOrder->last_name ?? ''),
                'email' => $firstOrder->email ?? 'N/A',
                'orders' => $customerOrders->count(),
                'items' => $totalItems,
                'total_spent' => $totalSpent,
                'first_order' => $customerOrders->min('created_at'),
                'last_order' => $customerOrders->max('created_at'),
            ];
        })->sortByDesc('total_spent');

        $filename = 'customers_report_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Customer Name', 'Email', 'Total Orders', 'Total Items', 'Total Spent', 'First Order', 'Last Order']);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer['name'],
                    $customer['email'],
                    $customer['orders'],
                    $customer['items'],
                    '₱' . number_format($customer['total_spent'], 2),
                    \Carbon\Carbon::parse($customer['first_order'])->format('Y-m-d'),
                    \Carbon\Carbon::parse($customer['last_order'])->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return \Response::stream($callback, 200, $headers);
    }
}