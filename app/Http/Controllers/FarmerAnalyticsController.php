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

class FarmerAnalyticsController extends Controller
{
    public function index()
    {
        $farmerId = auth()->id();

        // Get analytics data
        $analyticsData = $this->getAnalyticsData($farmerId);

        return view('farmer.analytics.index', compact('analyticsData'));
    }

    public function getAnalyticsData($farmerId)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Sales Analytics
        $salesData = $this->getSalesAnalytics($farmerId);

        // Product Performance
        $productData = $this->getProductAnalytics($farmerId);

        // Inventory Analytics
        $inventoryData = $this->getInventoryAnalytics($farmerId);

        // Order Analytics
        $orderData = $this->getOrderAnalytics($farmerId);

        // Revenue Analytics
        $revenueData = $this->getRevenueAnalytics($farmerId);

        // Customer Analytics
        $customerData = $this->getCustomerAnalytics($farmerId);

        return [
            'sales' => $salesData,
            'products' => $productData,
            'inventory' => $inventoryData,
            'orders' => $orderData,
            'revenue' => $revenueData,
            'customers' => $customerData,
        ];
    }

    private function getSalesAnalytics($farmerId)
    {
        // Monthly sales for the last 12 months
        $monthlySales = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->select(
                DB::raw($this->getMonthExpression('orders.created_at') . ' as month'),
                DB::raw($this->getYearExpression('orders.created_at') . ' as year'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy(DB::raw($this->getYearExpression('orders.created_at')), DB::raw($this->getMonthExpression('orders.created_at')))
            ->orderBy(DB::raw($this->getYearExpression('orders.created_at')), 'desc')
            ->orderBy(DB::raw($this->getMonthExpression('orders.created_at')), 'desc')
            ->limit(12)
            ->get();

        // Daily sales for current month
        $dailySales = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->whereRaw($this->getMonthExpression('orders.created_at') . ' = ?', [Carbon::now()->month])
            ->whereRaw($this->getYearExpression('orders.created_at') . ' = ?', [Carbon::now()->year])
            ->select(
                DB::raw($this->getDayExpression('orders.created_at') . ' as day'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return [
            'monthly' => $monthlySales,
            'daily' => $dailySales,
        ];
    }

    private function getProductAnalytics($farmerId)
    {
        // Top selling products
        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->select(
                'products.name',
                'products.id',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw('AVG(order_items.price) as avg_price')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        // Product categories performance
        $categoryPerformance = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->select(
                'products.category',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('products.category')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return [
            'top_products' => $topProducts,
            'category_performance' => $categoryPerformance,
        ];
    }

    private function getInventoryAnalytics($farmerId)
    {
        // Current stock levels
        $stockLevels = Product::where('user_id', $farmerId)
            ->select('id', 'name', 'stock_quantity', 'category')
            ->orderBy('stock_quantity', 'asc')
            ->get();

        // Inventory movements (last 30 days)
        $inventoryMovements = Inventory::where('farmer_id', $farmerId)
            ->where('transaction_date', '>=', Carbon::now()->subDays(30))
            ->select(
                'transaction_type',
                DB::raw('SUM(quantity_in) as total_in'),
                DB::raw('SUM(quantity_out) as total_out'),
                DB::raw('SUM(total_value) as total_value')
            )
            ->groupBy('transaction_type')
            ->get();

        // Low stock products
        $lowStockProducts = Product::where('user_id', $farmerId)
            ->where('stock_quantity', '<=', 10)
            ->select('id', 'name', 'stock_quantity', 'category')
            ->get();

        return [
            'stock_levels' => $stockLevels,
            'movements' => $inventoryMovements,
            'low_stock' => $lowStockProducts,
        ];
    }

    private function getOrderAnalytics($farmerId)
    {
        // Order status distribution
        $orderStatus = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->select(
                'orders.status',
                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_value')
            )
            ->groupBy('orders.status')
            ->get();

        // Orders by month (last 6 months)
        $monthlyOrders = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->where('orders.created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw($this->getMonthExpression('orders.created_at') . ' as month'),
                DB::raw($this->getYearExpression('orders.created_at') . ' as year'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy(DB::raw($this->getYearExpression('orders.created_at')), DB::raw($this->getMonthExpression('orders.created_at')))
            ->orderBy(DB::raw($this->getYearExpression('orders.created_at')), 'desc')
            ->orderBy(DB::raw($this->getMonthExpression('orders.created_at')), 'desc')
            ->get();

        return [
            'status_distribution' => $orderStatus,
            'monthly_orders' => $monthlyOrders,
        ];
    }

    private function getRevenueAnalytics($farmerId)
    {
        // Revenue by month (last 12 months)
        $monthlyRevenue = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->where('orders.created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw($this->getMonthExpression('orders.created_at') . ' as month'),
                DB::raw($this->getYearExpression('orders.created_at') . ' as year'),
                DB::raw('SUM(order_items.price * order_items.quantity) as revenue')
            )
            ->groupBy(DB::raw($this->getYearExpression('orders.created_at')), DB::raw($this->getMonthExpression('orders.created_at')))
            ->orderBy(DB::raw($this->getYearExpression('orders.created_at')), 'desc')
            ->orderBy(DB::raw($this->getMonthExpression('orders.created_at')), 'desc')
            ->get();

        // Revenue comparison (current vs previous month)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;


        $currentMonthRevenue = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->whereRaw($this->getMonthExpression('orders.created_at') . ' = ?', [$currentMonth])
            ->whereRaw($this->getYearExpression('orders.created_at') . ' = ?', [$currentYear])
            ->sum(DB::raw('order_items.price * order_items.quantity'));

        $previousMonth = Carbon::now()->subMonth()->month;
        $previousYear = Carbon::now()->subMonth()->year;

        $previousMonthRevenue = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->whereRaw($this->getMonthExpression('orders.created_at') . ' = ?', [$previousMonth])
            ->whereRaw($this->getYearExpression('orders.created_at') . ' = ?', [$previousYear])
            ->sum(DB::raw('order_items.price * order_items.quantity'));

        $revenueGrowth = $previousMonthRevenue > 0
            ? (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100
            : 0;

        return [
            'monthly_revenue' => $monthlyRevenue,
            'current_month' => $currentMonthRevenue,
            'previous_month' => $previousMonthRevenue,
            'growth_percentage' => round($revenueGrowth, 2),
        ];
    }

    private function getCustomerAnalytics($farmerId)
    {
        // Customer count by month
        $monthlyCustomers = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->where('orders.created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw($this->getMonthExpression('orders.created_at') . ' as month'),
                DB::raw($this->getYearExpression('orders.created_at') . ' as year'),
                DB::raw('COUNT(DISTINCT orders.user_id) as customer_count')
            )
            ->groupBy(DB::raw($this->getYearExpression('orders.created_at')), DB::raw($this->getMonthExpression('orders.created_at')))
            ->orderBy(DB::raw($this->getYearExpression('orders.created_at')), 'desc')
            ->orderBy(DB::raw($this->getMonthExpression('orders.created_at')), 'desc')
            ->get();

        // Average order value
        $avgOrderValue = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.user_id', $farmerId)
            ->whereRaw('orders.status != ?', ['cancelled'])
            ->select(DB::raw('AVG(order_items.price * order_items.quantity) as avg_value'))
            ->first();

        return [
            'monthly_customers' => $monthlyCustomers,
            'avg_order_value' => $avgOrderValue->avg_value ?? 0,
        ];
    }


    // API endpoint for AJAX requests
    public function getChartData(Request $request)
    {
        $farmerId = auth()->id();
        $type = $request->get('type', 'sales');

        switch ($type) {
            case 'sales':
                return response()->json($this->getSalesAnalytics($farmerId));
            case 'products':
                return response()->json($this->getProductAnalytics($farmerId));
            case 'inventory':
                return response()->json($this->getInventoryAnalytics($farmerId));
            case 'orders':
                return response()->json($this->getOrderAnalytics($farmerId));
            case 'revenue':
                return response()->json($this->getRevenueAnalytics($farmerId));
            case 'customers':
                return response()->json($this->getCustomerAnalytics($farmerId));
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }

    /**
     * Get database-specific month extraction expression
     */
    private function getMonthExpression($column)
    {
        $driver = config('database.default');
        switch ($driver) {
            case 'mysql':
                return "MONTH($column)";
            case 'sqlite':
                return "CAST(strftime('%m', $column) AS UNSIGNED)";
            case 'pgsql':
                return "EXTRACT(MONTH FROM $column)";
            default:
                return "MONTH($column)";
        }
    }

    /**
     * Get database-specific year extraction expression
     */
    private function getYearExpression($column)
    {
        $driver = config('database.default');
        switch ($driver) {
            case 'mysql':
                return "YEAR($column)";
            case 'sqlite':
                return "CAST(strftime('%Y', $column) AS UNSIGNED)";
            case 'pgsql':
                return "EXTRACT(YEAR FROM $column)";
            default:
                return "YEAR($column)";
        }
    }

    /**
     * Get database-specific day extraction expression
     */
    private function getDayExpression($column)
    {
        $driver = config('database.default');
        switch ($driver) {
            case 'mysql':
                return "DAY($column)";
            case 'sqlite':
                return "CAST(strftime('%d', $column) AS UNSIGNED)";
            case 'pgsql':
                return "EXTRACT(DAY FROM $column)";
            default:
                return "DAY($column)";
        }
    }

    /**
     * Get database-specific date difference expression
     */
    private function getDateDiffExpression($date1, $date2)
    {
        $driver = config('database.default');
        switch ($driver) {
            case 'mysql':
                return "DATEDIFF($date1, $date2)";
            case 'sqlite':
                return "julianday($date1) - julianday($date2)";
            case 'pgsql':
                return "EXTRACT(DAY FROM ($date1 - $date2))";
            default:
                return "DATEDIFF($date1, $date2)";
        }
    }
}
