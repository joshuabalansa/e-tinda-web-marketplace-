@extends('layouts.farmer')

@section('title', 'Reports Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-sm-12">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-doc-text"></i> Reports Dashboard</h4>
                    </div>
                    <div class="panel-options">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                <i class="entypo-download"></i> Export Report
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="exportReport('sales', 'csv')">Sales Report (CSV)</a></li>
                                <li><a href="#" onclick="exportReport('products', 'csv')">Products Report (CSV)</a></li>
                                <li><a href="#" onclick="exportReport('inventory', 'csv')">Inventory Report (CSV)</a></li>
                                <li><a href="#" onclick="exportReport('orders', 'csv')">Orders Report (CSV)</a></li>
                                <li><a href="#" onclick="exportReport('revenue', 'csv')">Revenue Report (CSV)</a></li>
                                <li><a href="#" onclick="exportReport('customers', 'csv')">Customers Report (CSV)</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="refreshReports()">
                            <i class="entypo-arrows-ccw"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="row mb-4">
        <div class="col-sm-12">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-filter"></i> Filter Reports</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="filterForm" method="GET" action="{{ route('farmer.reports.index') }}">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="reportType">Report Type</label>
                                    <select class="form-control" id="reportType" name="report_type">
                                        <option value="all" {{ request('report_type') == 'all' || !request('report_type') ? 'selected' : '' }}>All Reports</option>
                                        <option value="sales" {{ request('report_type') == 'sales' ? 'selected' : '' }}>Sales Reports</option>
                                        <option value="products" {{ request('report_type') == 'products' ? 'selected' : '' }}>Product Reports</option>
                                        <option value="inventory" {{ request('report_type') == 'inventory' ? 'selected' : '' }}>Inventory Reports</option>
                                        <option value="orders" {{ request('report_type') == 'orders' ? 'selected' : '' }}>Order Reports</option>
                                        <option value="revenue" {{ request('report_type') == 'revenue' ? 'selected' : '' }}>Revenue Reports</option>
                                        <option value="customers" {{ request('report_type') == 'customers' ? 'selected' : '' }}>Customer Reports</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="dateRange">Date Range</label>
                                    <select class="form-control" id="dateRange" name="date_range">
                                        <option value="all" {{ request('date_range') == 'all' || !request('date_range') ? 'selected' : '' }}>All Time</option>
                                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                                        <option value="quarter" {{ request('date_range') == 'quarter' ? 'selected' : '' }}>This Quarter</option>
                                        <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>This Year</option>
                                        <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>Custom Range</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3" id="customDateRange" style="display: {{ request('date_range') == 'custom' ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" name="start_date" value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-sm-3" id="customDateRange2" style="display: {{ request('date_range') == 'custom' ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label for="endDate">End Date</label>
                                    <input type="date" class="form-control" id="endDate" name="end_date" value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="entypo-search"></i> Apply Filter
                                        </button>
                                        <a href="{{ route('farmer.reports.index') }}" class="btn btn-secondary">
                                            <i class="entypo-cancel"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Status -->
    @if($reportType != 'all' || $dateRange != 'all')
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="alert alert-info">
                <i class="entypo-info"></i>
                <strong>Active Filters:</strong>
                @if($reportType != 'all')
                    Report Type: <span class="badge badge-primary">{{ ucfirst($reportType) }}</span>
                @endif
                @if($dateRange != 'all')
                    Date Range: <span class="badge badge-info">
                        @if($dateRange == 'custom')
                            {{ $startDate }} to {{ $endDate }}
                        @else
                            {{ ucfirst($dateRange) }}
                        @endif
                    </span>
                @endif
                <a href="{{ route('farmer.reports.index') }}" class="btn btn-sm btn-secondary ml-2">
                    <i class="entypo-cancel"></i> Clear All Filters
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <div class="col-sm-3">
            <div class="tile-progress tile-green">
                <div class="tile-header">
                    <h3>Total Revenue</h3>
                    <span>All time earnings</span>
                </div>
                <div class="tile-progressbar">
                    <span data-fill="100%"></span>
                </div>
                <div class="tile-footer">
                    <h4>₱{{ number_format(($reportsData['revenue']['summary']->total_revenue ?? 0), 2) }}</h4>
                    <span>from {{ $reportsData['revenue']['summary']->total_orders ?? 0 }} orders</span>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="tile-progress tile-aqua">
                <div class="tile-header">
                    <h3>Total Products</h3>
                    <span>Products in catalog</span>
                </div>
                <div class="tile-progressbar">
                    <span data-fill="100%"></span>
                </div>
                <div class="tile-footer">
                    <h4>{{ $reportsData['products']['metrics']->total_products ?? 0 }}</h4>
                    <span>{{ $reportsData['products']['metrics']->active_products ?? 0 }} active</span>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="tile-progress tile-orange">
                <div class="tile-header">
                    <h3>Unique Customers</h3>
                    <span>Total customer base</span>
                </div>
                <div class="tile-progressbar">
                    <span data-fill="100%"></span>
                </div>
                <div class="tile-footer">
                    <h4>{{ $reportsData['customers']['summary']->unique_customers ?? 0 }}</h4>
                    <span>customers served</span>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="tile-progress tile-red">
                <div class="tile-header">
                    <h3>Inventory Value</h3>
                    <span>Current stock value</span>
                </div>
                <div class="tile-progressbar">
                    <span data-fill="100%"></span>
                </div>
                <div class="tile-footer">
                    <h4>₱{{ number_format($reportsData['inventory']['summary']->total_value ?? 0, 2) }}</h4>
                    <span>{{ $reportsData['inventory']['summary']->total_stock ?? 0 }} units</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Reports Row -->
    @if($reportType == 'all' || $reportType == 'sales')
    <div class="row mb-4">
        <!-- Sales Summary -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-chart-bar"></i> Sales Summary</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="tile-progress tile-green">
                                <div class="tile-header">
                                    <h3>Today</h3>
                                    <span>Daily sales</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>₱{{ number_format(($reportsData['sales']['summary']['today']->total_revenue ?? 0), 2) }}</h4>
                                    <span>{{ $reportsData['sales']['summary']['today']->total_quantity ?? 0 }} items</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="tile-progress tile-aqua">
                                <div class="tile-header">
                                    <h3>This Week</h3>
                                    <span>Weekly sales</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>₱{{ number_format(($reportsData['sales']['summary']['this_week']->total_revenue ?? 0), 2) }}</h4>
                                    <span>{{ $reportsData['sales']['summary']['this_week']->total_quantity ?? 0 }} items</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="tile-progress tile-orange">
                                <div class="tile-header">
                                    <h3>This Month</h3>
                                    <span>Monthly sales</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>₱{{ number_format(($reportsData['sales']['summary']['this_month']->total_revenue ?? 0), 2) }}</h4>
                                    <span>{{ $reportsData['sales']['summary']['this_month']->total_quantity ?? 0 }} items</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="tile-progress tile-red">
                                <div class="tile-header">
                                    <h3>This Year</h3>
                                    <span>Yearly sales</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>₱{{ number_format(($reportsData['sales']['summary']['this_year']->total_revenue ?? 0), 2) }}</h4>
                                    <span>{{ $reportsData['sales']['summary']['this_year']->total_quantity ?? 0 }} items</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-star"></i> Top Selling Products</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Sold</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($reportsData['sales']['top_products'] ?? collect())->take(10) as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $product->total_sold }}</span>
                                        </td>
                                        <td>₱{{ number_format($product->total_revenue, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No sales data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Product Reports Row -->
    @if($reportType == 'all' || $reportType == 'products')
    <div class="row mb-4">
        <!-- Product Performance -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-tag"></i> Product Performance</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="tile-progress tile-green">
                                <div class="tile-header">
                                    <h3>Active Products</h3>
                                    <span>In stock</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>{{ $reportsData['products']['metrics']->active_products ?? 0 }}</h4>
                                    <span>products available</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="tile-progress tile-red">
                                <div class="tile-header">
                                    <h3>Low Stock</h3>
                                    <span>Need restocking</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>{{ $reportsData['products']['metrics']->low_stock_products ?? 0 }}</h4>
                                    <span>products</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="tile-progress tile-aqua">
                                <div class="tile-header">
                                    <h3>Average Product Price</h3>
                                    <span>Price per item</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>₱{{ number_format($reportsData['products']['metrics']->avg_product_price ?? 0, 2) }}</h4>
                                    <span>per product</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales by Category -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-folder"></i> Sales by Category</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Revenue</th>
                                    <th>Orders</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($reportsData['sales']['by_category'] ?? []) as $category)
                                    <tr>
                                        <td>{{ $category->category }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $category->total_quantity }}</span>
                                        </td>
                                        <td>₱{{ number_format($category->total_revenue, 2) }}</td>
                                        <td>{{ $category->order_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No category data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Order Reports Row -->
    @if($reportType == 'all' || $reportType == 'orders')
    <div class="row mb-4">
        <!-- Order Summary -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-mail"></i> Order Summary</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="tile-progress tile-green">
                                <div class="tile-header">
                                    <h3>Total Orders</h3>
                                    <span>All time</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>{{ $reportsData['orders']['summary']->total_orders ?? 0 }}</h4>
                                    <span>orders processed</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="tile-progress tile-aqua">
                                <div class="tile-header">
                                    <h3>Average Order</h3>
                                    <span>Order value</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>₱{{ number_format($reportsData['orders']['summary']->avg_order_value ?? 0, 2) }}</h4>
                                    <span>per order</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="tile-progress tile-orange">
                                <div class="tile-header">
                                    <h3>Total Items</h3>
                                    <span>Items sold</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>{{ $reportsData['orders']['summary']->total_items ?? 0 }}</h4>
                                    <span>items sold</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders by Status -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-pie-chart"></i> Orders by Status</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($reportsData['orders']['by_status'] ?? []) as $status)
                                    <tr>
                                        <td>
                                            <span class="label label-{{ $status->status == 'completed' ? 'success' : ($status->status == 'pending' ? 'warning' : 'info') }}">
                                                {{ ucfirst($status->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $status->order_count }}</td>
                                        <td>₱{{ number_format($status->total_value, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No order data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Customer Reports Row -->
    @if($reportType == 'all' || $reportType == 'customers')
    <div class="row mb-4">
        <!-- Top Customers -->
        <div class="col-sm-8">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-users"></i> Top Customers</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Orders</th>
                                    <th>Total Spent</th>
                                    <th>Items</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($reportsData['customers']['top_customers'] ?? []) as $customer)
                                    <tr>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $customer->order_count }}</span>
                                        </td>
                                        <td>₱{{ number_format($customer->total_spent, 2) }}</td>
                                        <td>{{ $customer->total_items }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No customer data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="col-sm-4">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-gauge"></i> Performance Metrics</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="tile-progress tile-green">
                        <div class="tile-header">
                            <h3>Customer Retention</h3>
                            <span>Repeat customers</span>
                        </div>
                        <div class="tile-progressbar">
                            <span data-fill="100%"></span>
                        </div>
                        <div class="tile-footer">
                            <h4>{{ number_format(($reportsData['performance']['customer_retention'] ?? 0), 1) }}%</h4>
                            <span>retention rate</span>
                        </div>
                    </div>
                    <div class="tile-progress tile-aqua">
                        <div class="tile-header">
                            <h3>Inventory Turnover</h3>
                            <span>Stock rotation</span>
                        </div>
                        <div class="tile-progressbar">
                            <span data-fill="100%"></span>
                        </div>
                        <div class="tile-footer">
                            <h4>{{ number_format(($reportsData['performance']['inventory_turnover'] ?? 0), 1) }}</h4>
                            <span>turnover rate</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Orders -->
    @if($reportType == 'all' || $reportType == 'orders')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-clock"></i> Recent Orders (Last 30 Days)</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Product</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($reportsData['orders']['recent'] ?? []) as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->product_name }}</td>
                                        <td>
                                            <span class="label label-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>₱{{ number_format($order->price, 2) }}</td>
                                        <td>₱{{ number_format($order->total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No recent orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Reports data
const reportsData = @json($reportsData);

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const dateRangeSelect = document.getElementById('dateRange');
    const customDateRange1 = document.getElementById('customDateRange');
    const customDateRange2 = document.getElementById('customDateRange2');

    // Show/hide custom date range fields
    function toggleCustomDateRange() {
        if (dateRangeSelect.value === 'custom') {
            customDateRange1.style.display = 'block';
            customDateRange2.style.display = 'block';
        } else {
            customDateRange1.style.display = 'none';
            customDateRange2.style.display = 'none';
        }
    }

    // Initial state
    toggleCustomDateRange();

    // Event listener for date range change
    dateRangeSelect.addEventListener('change', toggleCustomDateRange);

    // Auto-submit form when report type changes
    const reportTypeSelect = document.getElementById('reportType');
    reportTypeSelect.addEventListener('change', function() {
        if (this.value !== 'all') {
            document.getElementById('filterForm').submit();
        }
    });
});

// Utility functions
function refreshReports() {
    location.reload();
}

function exportReport(type, format) {
    const url = `/farmer/reports/export?type=${type}&format=${format}`;
    window.open(url, '_blank');
}

function applyFilter() {
    document.getElementById('filterForm').submit();
}

function clearFilters() {
    window.location.href = '{{ route("farmer.reports.index") }}';
}
</script>

<style>
.tile-progress {
    margin-bottom: 20px;
}

.text-success {
    color: #27ae60 !important;
}

.text-danger {
    color: #e74c3c !important;
}

.label-success {
    background-color: #27ae60;
}

.label-warning {
    background-color: #f39c12;
}

.label-info {
    background-color: #3498db;
}

.label-primary {
    background-color: #9b59b6;
}

.badge-primary {
    background-color: #9b59b6;
}

.badge-info {
    background-color: #3498db;
}

.badge-warning {
    background-color: #f39c12;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.badge-primary {
    background-color: #007bff;
}

.badge-info {
    background-color: #17a2b8;
}

.ml-2 {
    margin-left: 0.5rem;
}
</style>
@endsection
