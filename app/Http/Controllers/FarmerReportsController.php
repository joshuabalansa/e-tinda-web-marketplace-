<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FarmerReportsController extends Controller
{
    public function index(Request $request)
    {
        $farmerId = auth()->id();

        // Get filter parameters
        $reportType = $request->get('report_type', 'all');
        $dateRange = $request->get('date_range', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Get reports data with filters
        $reportsData = $this->getReportsData($farmerId, $reportType, $dateRange, $startDate, $endDate);

        return view('farmer.reports.index', compact('reportsData', 'reportType', 'dateRange', 'startDate', 'endDate'));
    }

    public function getReportsData($farmerId, $reportType = 'all', $dateRange = 'all', $startDate = null, $endDate = null)
    {
        $data = [];

        // Apply filters based on report type
        if ($reportType === 'all' || $reportType === 'sales') {
            $data['sales'] = $this->getSalesReports($farmerId, $dateRange, $startDate, $endDate);
        }

        if ($reportType === 'all' || $reportType === 'products') {
            $data['products'] = $this->getProductReports($farmerId, $dateRange, $startDate, $endDate);
        }

        if ($reportType === 'all' || $reportType === 'inventory') {
            $data['inventory'] = $this->getInventoryReports($farmerId, $dateRange, $startDate, $endDate);
        }

        if ($reportType === 'all' || $reportType === 'orders') {
            $data['orders'] = $this->getOrderReports($farmerId, $dateRange, $startDate, $endDate);
        }

        if ($reportType === 'all' || $reportType === 'revenue') {
            $data['revenue'] = $this->getRevenueReports($farmerId, $dateRange, $startDate, $endDate);
        }

        if ($reportType === 'all' || $reportType === 'customers') {
            $data['customers'] = $this->getCustomerReports($farmerId, $dateRange, $startDate, $endDate);
        }

        if ($reportType === 'all') {
            $data['performance'] = $this->getPerformanceReports($farmerId, $dateRange, $startDate, $endDate);
        }

        return $data;
    }

    private function applyDateFilter($query, $dateRange, $startDate = null, $endDate = null, $dateColumn = 'created_at')
    {
        switch ($dateRange) {
            case 'today':
                $query->whereDate($dateColumn, Carbon::today());
                break;
            case 'week':
                $query->whereBetween($dateColumn, [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth($dateColumn, Carbon::now()->month)
                      ->whereYear($dateColumn, Carbon::now()->year);
                break;
            case 'quarter':
                $query->whereBetween($dateColumn, [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()]);
                break;
            case 'year':
                $query->whereYear($dateColumn, Carbon::now()->year);
                break;
            case 'custom':
                if ($startDate) {
                    $query->whereDate($dateColumn, '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate($dateColumn, '<=', $endDate);
                }
                break;
            // 'all' case - no filter applied
        }

        return $query;
    }

    private function getSalesReports($farmerId, $dateRange = 'all', $startDate = null, $endDate = null)
    {
        // Sales summary for different periods
        $salesSummary = [
            'today' => $this->getSalesForPeriod($farmerId, 'today'),
            'this_week' => $this->getSalesForPeriod($farmerId, 'this_week'),
            'this_month' => $this->getSalesForPeriod($farmerId, 'this_month'),
            'this_year' => $this->getSalesForPeriod($farmerId, 'this_year'),
        ];

        // Top selling products (detailed)
        $topSellingProductsQuery = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled']);

        // Apply date filter
        $this->applyDateFilter($topSellingProductsQuery, $dateRange, $startDate, $endDate, 'orders.created_at');

        $topSellingProducts = $topSellingProductsQuery
            ->select(
                'products.id',
                'products.name',
                'products.category',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('AVG(order_items.price) as avg_price'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('products.id', 'products.name', 'products.category')
            ->orderBy('total_sold', 'desc')
            ->limit(20)
            ->get();

        // Sales by category
        $salesByCategoryQuery = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled']);

        // Apply date filter
        $this->applyDateFilter($salesByCategoryQuery, $dateRange, $startDate, $endDate, 'orders.created_at');

        $salesByCategory = $salesByCategoryQuery
            ->select(
                'products.category',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('products.category')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return [
            'summary' => $salesSummary,
            'top_products' => $topSellingProducts,
            'by_category' => $salesByCategory,
        ];
    }

    private function getSalesForPeriod($farmerId, $period)
    {
        $query = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled']);

        switch ($period) {
            case 'today':
                $query->whereDate('orders.created_at', Carbon::today());
                break;
            case 'this_week':
                $query->whereBetween('orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('orders.created_at', Carbon::now()->month)
                      ->whereYear('orders.created_at', Carbon::now()->year);
                break;
            case 'this_year':
                $query->whereYear('orders.created_at', Carbon::now()->year);
                break;
        }

        return $query->select(
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
            DB::raw('COUNT(DISTINCT orders.id) as order_count')
        )->first();
    }

    private function getProductReports($farmerId)
    {
        // Product performance metrics
        $productMetrics = Product::where('user_id', $farmerId)
            ->select(
                DB::raw('COUNT(*) as total_products'),
                DB::raw('SUM(CASE WHEN stock_quantity > 0 THEN 1 ELSE 0 END) as active_products'),
                DB::raw('SUM(CASE WHEN stock_quantity <= 10 THEN 1 ELSE 0 END) as low_stock_products'),
                DB::raw('SUM(stock_quantity) as total_inventory_value'),
                DB::raw('AVG(price_per_unit) as avg_product_price')
            )
            ->first();

        // Product reviews and ratings
        $productReviews = Review::join('products', 'reviews.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->select(
                'products.id',
                'products.name',
                DB::raw('COUNT(reviews.review_id) as review_count'),
                DB::raw('AVG(reviews.rating) as avg_rating'),
                DB::raw('SUM(CASE WHEN reviews.rating >= 4 THEN 1 ELSE 0 END) as positive_reviews')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('avg_rating', 'desc')
            ->limit(10)
            ->get();

        return [
            'metrics' => $productMetrics,
            'reviews' => $productReviews,
        ];
    }

    private function getInventoryReports($farmerId)
    {
        // Inventory summary
        $inventorySummary = Product::where('user_id', $farmerId)
            ->select(
                DB::raw('COUNT(*) as total_products'),
                DB::raw('SUM(stock_quantity) as total_stock'),
                DB::raw('SUM(stock_quantity * price_per_unit) as total_value'),
                DB::raw('AVG(stock_quantity) as avg_stock_per_product')
            )
            ->first();

        // Inventory movements (last 30 days)
        $inventoryMovements = Inventory::where('farmer_id', $farmerId)
            ->where('transaction_date', '>=', Carbon::now()->subDays(30))
            ->select(
                'transaction_type',
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(quantity_in) as total_in'),
                DB::raw('SUM(quantity_out) as total_out'),
                DB::raw('SUM(total_value) as total_value')
            )
            ->groupBy('transaction_type')
            ->get();

        // Stock aging report
        $stockAging = Product::where('user_id', $farmerId)
            ->select(
                'id',
                'name',
                'stock_quantity',
                'created_at',
                DB::raw('julianday("now") - julianday(created_at) as days_in_stock')
            )
            ->orderBy('days_in_stock', 'desc')
            ->limit(20)
            ->get();

        return [
            'summary' => $inventorySummary,
            'movements' => $inventoryMovements,
            'aging' => $stockAging,
        ];
    }

    private function getOrderReports($farmerId)
    {
        // Order summary
        $orderSummary = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->select(
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(order_items.quantity) as total_items'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_value'),
                DB::raw('AVG(order_items.price * order_items.quantity) as avg_order_value')
            )
            ->first();

        // Orders by status
        $ordersByStatus = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->select(
                'orders.status',
                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_value')
            )
            ->groupBy('orders.status')
            ->get();

        // Recent orders (last 30 days)
        $recentOrders = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->where('orders.created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                'orders.id',
                'orders.status',
                'orders.created_at',
                'products.name as product_name',
                'order_items.quantity',
                'order_items.price',
                DB::raw('order_items.price * order_items.quantity as total')
            )
            ->orderBy('orders.created_at', 'desc')
            ->limit(50)
            ->get();

        return [
            'summary' => $orderSummary,
            'by_status' => $ordersByStatus,
            'recent' => $recentOrders,
        ];
    }

    private function getRevenueReports($farmerId)
    {
        // Revenue summary
        $revenueSummary = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->select(
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('AVG(order_items.price * order_items.quantity) as avg_order_value'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
            ->first();

        // Revenue by month (last 12 months)
        $monthlyRevenue = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->where('orders.created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('strftime("%m", orders.created_at) as month'),
                DB::raw('strftime("%Y", orders.created_at) as year'),
                DB::raw('SUM(order_items.price * order_items.quantity) as revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Revenue by day (last 30 days)
        $dailyRevenue = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->where('orders.created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(orders.created_at) as date'),
                DB::raw('SUM(order_items.price * order_items.quantity) as revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return [
            'summary' => $revenueSummary,
            'monthly' => $monthlyRevenue,
            'daily' => $dailyRevenue,
        ];
    }

    private function getCustomerReports($farmerId)
    {
        // Customer summary
        $customerSummary = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->select(
                DB::raw('COUNT(DISTINCT orders.user_id) as unique_customers'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('AVG(order_items.price * order_items.quantity) as avg_order_value')
            )
            ->first();

        // Top customers
        $topCustomers = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('products.user_id', $farmerId)
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_spent'),
                DB::raw('SUM(order_items.quantity) as total_items')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        return [
            'summary' => $customerSummary,
            'top_customers' => $topCustomers,
        ];
    }

    private function getPerformanceReports($farmerId)
    {
        // Performance metrics
        $performanceMetrics = [
            'conversion_rate' => $this->calculateConversionRate($farmerId),
            'average_order_value' => $this->calculateAverageOrderValue($farmerId),
            'customer_retention' => $this->calculateCustomerRetention($farmerId),
            'inventory_turnover' => $this->calculateInventoryTurnover($farmerId),
        ];

        return $performanceMetrics;
    }

    private function calculateConversionRate($farmerId)
    {
        // This is a simplified calculation - in a real app, you'd track views/clicks
        $totalOrders = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->count();

        $totalProducts = Product::where('user_id', $farmerId)->count();

        return $totalProducts > 0 ? ($totalOrders / $totalProducts) * 100 : 0;
    }

    private function calculateAverageOrderValue($farmerId)
    {
        $result = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->select(DB::raw('AVG(order_items.price * order_items.quantity) as avg_value'))
            ->first();

        return $result->avg_value ?? 0;
    }

    private function calculateCustomerRetention($farmerId)
    {
        // Customers who made more than one order
        $repeatCustomers = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->select('orders.user_id')
            ->groupBy('orders.user_id')
            ->havingRaw('COUNT(DISTINCT orders.id) > 1')
            ->count();

        $totalCustomers = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->distinct('orders.user_id')
            ->count();

        return $totalCustomers > 0 ? ($repeatCustomers / $totalCustomers) * 100 : 0;
    }

    private function calculateInventoryTurnover($farmerId)
    {
        $totalSold = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->where('orders.created_at', '>=', Carbon::now()->subYear())
            ->sum('order_items.quantity');

        $avgInventory = Product::where('user_id', $farmerId)->avg('stock_quantity');

        return $avgInventory > 0 ? $totalSold / $avgInventory : 0;
    }

    public function exportReport(Request $request)
    {
        $farmerId = auth()->id();
        $reportType = $request->get('type', 'sales');
        $format = $request->get('format', 'csv');

        $data = $this->getReportsData($farmerId);

        switch ($reportType) {
            case 'sales':
                $exportData = $data['sales'];
                break;
            case 'products':
                $exportData = $data['products'];
                break;
            case 'inventory':
                $exportData = $data['inventory'];
                break;
            case 'orders':
                $exportData = $data['orders'];
                break;
            case 'revenue':
                $exportData = $data['revenue'];
                break;
            case 'customers':
                $exportData = $data['customers'];
                break;
            default:
                $exportData = $data;
        }

        if ($format === 'csv') {
            return $this->exportToCSV($exportData, $reportType);
        }

        return response()->json($exportData);
    }

    private function exportToCSV($data, $reportType)
    {
        $filename = "farmer_{$reportType}_report_" . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data, $reportType) {
            $file = fopen('php://output', 'w');

            // Write CSV headers based on report type
            switch ($reportType) {
                case 'sales':
                    fputcsv($file, ['Product Name', 'Category', 'Total Sold', 'Total Revenue', 'Average Price', 'Order Count']);
                    foreach ($data['top_products'] as $product) {
                        fputcsv($file, [
                            $product->name,
                            $product->category,
                            $product->total_sold,
                            $product->total_revenue,
                            $product->avg_price,
                            $product->order_count
                        ]);
                    }
                    break;
                case 'products':
                    fputcsv($file, ['Product Name', 'Review Count', 'Average Rating', 'Positive Reviews']);
                    foreach ($data['reviews'] as $product) {
                        fputcsv($file, [
                            $product->name,
                            $product->review_count,
                            $product->avg_rating,
                            $product->positive_reviews
                        ]);
                    }
                    break;
                // Add more cases as needed
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
