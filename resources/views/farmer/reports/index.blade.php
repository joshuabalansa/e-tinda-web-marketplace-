@extends('layouts.farmer')

@section('title', 'E-Tinda - Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-doc-text"></i> Reports Dashboard</h4>
                    </div>
                    <div class="panel-options">
                        <button type="button" class="btn btn-success btn-sm" onclick="exportReport('sales', 'csv')">
                            <i class="entypo-download"></i> Export Report
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="refreshReports()">
                            <i class="entypo-arrows-ccw"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-filter"></i> Filter Reports</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="filterForm" method="GET" action="{{ route('farmer.reports.index') }}">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="reportType">Report Type</label>
                                    <select class="form-control" id="reportType" name="report_type">
                                        <option value="all" {{ request('report_type') == 'all' || !request('report_type') ? 'selected' : '' }}>All Reports</option>
                                        <option value="sales" {{ request('report_type') == 'sales' ? 'selected' : '' }}>Sales</option>
                                        <option value="products" {{ request('report_type') == 'products' ? 'selected' : '' }}>Products</option>
                                        <option value="orders" {{ request('report_type') == 'orders' ? 'selected' : '' }}>Orders</option>
                                        <option value="customers" {{ request('report_type') == 'customers' ? 'selected' : '' }}>Customers</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="dateRange">Date Range</label>
                                    <select class="form-control" id="dateRange" name="date_range">
                                        <option value="all" {{ request('date_range') == 'all' || !request('date_range') ? 'selected' : '' }}>All Time</option>
                                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                                        <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>This Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
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

    <!-- Sales Summary -->
    <div class="row mb-4">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-chart-bar"></i> Sales Summary</h4>
                    </div>
                    <div class="panel-options">
                        <button type="button" class="btn btn-sm btn-primary" onclick="exportReport('sales', 'csv')">
                            <i class="entypo-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-3">
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
                        <div class="col-sm-3">
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
                        <div class="col-sm-3">
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
                        <div class="col-sm-3">
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
    </div>

    <!-- Top Selling Products & Sales by Category -->
    <div class="row mb-4">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-star"></i> Top Selling Products</h4>
                    </div>
                    <div class="panel-options">
                        <button type="button" class="btn btn-sm btn-primary" onclick="exportReport('products', 'csv')">
                            <i class="entypo-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
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
                                        <td><span class="label label-info">{{ $product->category }}</span></td>
                                        <td><span class="badge badge-primary">{{ $product->total_sold }}</span></td>
                                        <td><strong>₱{{ number_format($product->total_revenue, 2) }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No sales data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-folder"></i> Sales by Category</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
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
                                        <td><span class="label label-info">{{ $category->category }}</span></td>
                                        <td><span class="badge badge-info">{{ $category->total_quantity }}</span></td>
                                        <td><strong>₱{{ number_format($category->total_revenue, 2) }}</strong></td>
                                        <td>{{ $category->order_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No category data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers & Performance Metrics -->
    <div class="row mb-4">
        <div class="col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-users"></i> Top Customers</h4>
                    </div>
                    <div class="panel-options">
                        <button type="button" class="btn btn-sm btn-primary" onclick="exportReport('customers', 'csv')">
                            <i class="entypo-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
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
                                        <td><span class="badge badge-primary">{{ $customer->order_count }}</span></td>
                                        <td><strong>₱{{ number_format($customer->total_spent, 2) }}</strong></td>
                                        <td>{{ $customer->total_items }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No customer data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="panel panel-default">
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
                            <span data-fill="{{ $reportsData['performance']['customer_retention'] ?? 0 }}%"></span>
                        </div>
                        <div class="tile-footer">
                            <h4>{{ number_format(($reportsData['performance']['customer_retention'] ?? 0), 1) }}%</h4>
                            <span>retention rate</span>
                        </div>
                    </div>
                    <div class="tile-progress tile-aqua" style="margin-top: 15px;">
                        <div class="tile-header">
                            <h3>Inventory Turnover</h3>
                            <span>Stock rotation</span>
                        </div>
                        <div class="tile-progressbar">
                            <span data-fill="75%"></span>
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

    <!-- Export Options Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="entypo-download"></i> Export Reports</h4>
                </div>
                <div class="modal-body">
                    <p>Select a report type to export:</p>
                    <div class="list-group">
                        <a href="#" onclick="exportReport('sales', 'csv')" class="list-group-item">
                            <i class="entypo-chart-bar"></i> Sales Report
                        </a>
                        <a href="#" onclick="exportReport('products', 'csv')" class="list-group-item">
                            <i class="entypo-box"></i> Products Report
                        </a>
                        <a href="#" onclick="exportReport('inventory', 'csv')" class="list-group-item">
                            <i class="entypo-database"></i> Inventory Report
                        </a>
                        <a href="#" onclick="exportReport('orders', 'csv')" class="list-group-item">
                            <i class="entypo-mail"></i> Orders Report
                        </a>
                        <a href="#" onclick="exportReport('revenue', 'csv')" class="list-group-item">
                            <i class="entypo-chart-line"></i> Revenue Report
                        </a>
                        <a href="#" onclick="exportReport('customers', 'csv')" class="list-group-item">
                            <i class="entypo-users"></i> Customers Report
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Utility functions
function refreshReports() {
    showNotification('Refreshing reports...', 'info');
    setTimeout(() => {
        location.reload();
    }, 500);
}

function exportReport(type, format) {
    const url = `{{ route('farmer.reports.export') }}?type=${type}&format=${format}`;
    showNotification(`Exporting ${type} report...`, 'info');
    window.location.href = url;
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = type === 'error' ? 'alert alert-danger' : (type === 'info' ? 'alert alert-info' : 'alert alert-success');
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '250px';
    notification.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
    notification.innerHTML = `
        <button type="button" class="close" onclick="this.parentElement.remove()">
            <span>&times;</span>
        </button>
        ${message}
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const dateRangeSelect = document.getElementById('dateRange');
    const reportTypeSelect = document.getElementById('reportType');

    // Auto-submit form when selections change
    if (dateRangeSelect) {
        dateRangeSelect.addEventListener('change', function() {
            if (this.value !== 'all') {
                document.getElementById('filterForm').submit();
            }
        });
    }
});
</script>

<style>
.tile-progress {
    margin-bottom: 20px;
}

.panel-options {
    display: flex;
    gap: 10px;
}

.panel-options .btn {
    margin-left: 5px;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0,0,0,.02);
}

.text-muted {
    color: #999;
}

.label {
    display: inline-block;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 3px;
}

.label-info {
    background-color: #5bc0de;
}

.label-success {
    background-color: #5cb85c;
}

.label-warning {
    background-color: #f0ad4e;
}

.badge {
    display: inline-block;
    min-width: 10px;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: bold;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    background-color: #777;
    border-radius: 10px;
}

.badge-primary {
    background-color: #007bff;
}

.badge-info {
    background-color: #17a2b8;
}

.list-group {
    margin-bottom: 0;
}

.list-group-item {
    padding: 12px 20px;
    border: 1px solid #ddd;
    display: block;
    text-decoration: none;
    color: #333;
}

.list-group-item:hover {
    background-color: #f5f5f5;
}

.list-group-item i {
    margin-right: 10px;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

.alert-info {
    color: #31708f;
    background-color: #d9edf7;
    border-color: #bce8f1;
}

.alert-danger {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
}

.close {
    float: right;
    font-size: 21px;
    font-weight: bold;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .2;
    background: transparent;
    border: 0;
    cursor: pointer;
}

.close:hover {
    opacity: .5;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .panel-options {
        flex-direction: column;
        width: 100%;
    }
    
    .panel-options .btn {
        width: 100%;
        margin: 5px 0;
    }
    
    .table {
        font-size: 12px;
    }
    
    .table th,
    .table td {
        padding: 8px 5px;
    }
}
</style>
@endsection
