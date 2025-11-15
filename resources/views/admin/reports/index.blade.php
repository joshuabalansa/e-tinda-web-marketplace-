@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="page-title">Reports Dashboard</h1>
                    <p class="page-description">Generate and export comprehensive reports</p>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('admin.analytics.index') }}" class="btn btn-primary">
                        <i class="entypo-chart-bar"></i> View Analytics
                    </a>
                </div>
            </div>
        </div>

        <!-- Report Type Navigation -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Report Types</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <a href="{{ route('admin.reports.index', ['type' => 'overview']) }}"
                           class="btn btn-block {{ request('type') == 'overview' || !request('type') ? 'btn-primary' : 'btn-default' }}">
                            <i class="entypo-home"></i><br>Overview
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <a href="{{ route('admin.reports.index', ['type' => 'users']) }}"
                           class="btn btn-block {{ request('type') == 'users' ? 'btn-primary' : 'btn-default' }}">
                            <i class="entypo-users"></i><br>Users
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <a href="{{ route('admin.reports.index', ['type' => 'orders']) }}"
                           class="btn btn-block {{ request('type') == 'orders' ? 'btn-primary' : 'btn-default' }}">
                            <i class="entypo-basket"></i><br>Orders
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <a href="{{ route('admin.reports.index', ['type' => 'products']) }}"
                           class="btn btn-block {{ request('type') == 'products' ? 'btn-primary' : 'btn-default' }}">
                            <i class="entypo-box"></i><br>Products
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <a href="{{ route('admin.reports.index', ['type' => 'forums']) }}"
                           class="btn btn-block {{ request('type') == 'forums' ? 'btn-primary' : 'btn-default' }}">
                            <i class="entypo-chat"></i><br>Forums
                        </a>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <a href="{{ route('admin.reports.index', ['type' => 'financial']) }}"
                           class="btn btn-block {{ request('type') == 'financial' ? 'btn-primary' : 'btn-default' }}">
                            <i class="entypo-credit-card"></i><br>Financial
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(request('type') == 'overview' || !request('type'))
            <!-- Overview Report -->
            <div class="row">
                <!-- Summary Statistics -->
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Summary Statistics</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-primary">
                                        <div class="panel-body text-center">
                                            <h3 class="text-primary">{{ $stats['total_users'] }}</h3>
                                            <p>Total Users</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-success">
                                        <div class="panel-body text-center">
                                            <h3 class="text-success">{{ $stats['total_orders'] }}</h3>
                                            <p>Total Orders</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-info">
                                        <div class="panel-body text-center">
                                            <h3 class="text-info">{{ $stats['total_products'] }}</h3>
                                            <p>Total Products</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="panel panel-warning">
                                        <div class="panel-body text-center">
                                            <h3 class="text-warning">₱{{ number_format($stats['total_revenue'], 2) }}</h3>
                                            <p>Total Revenue</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Orders by Status</h3>
                        </div>
                        <div class="panel-body">
                            <canvas id="ordersStatusChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Users by Role</h3>
                        </div>
                        <div class="panel-body">
                            <canvas id="usersRoleChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Export Data</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.reports.export', ['type' => 'users', 'format' => 'csv']) }}"
                               class="btn btn-block btn-success">
                                <i class="entypo-download"></i> Export Users
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.reports.export', ['type' => 'orders', 'format' => 'csv']) }}"
                               class="btn btn-block btn-success">
                                <i class="entypo-download"></i> Export Orders
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.reports.export', ['type' => 'products', 'format' => 'csv']) }}"
                               class="btn btn-block btn-success">
                                <i class="entypo-download"></i> Export Products
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.reports.export', ['type' => 'forums', 'format' => 'csv']) }}"
                               class="btn btn-block btn-success">
                                <i class="entypo-download"></i> Export Forums
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(request('type') == 'users')
            <!-- Users Report -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Users Report</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-body text-center">
                                    <h3 class="text-primary">{{ $stats['total_users'] }}</h3>
                                    <p>Total Users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-body text-center">
                                    <h3 class="text-success">{{ $stats['farmers'] }}</h3>
                                    <p>Farmers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-info">
                                <div class="panel-body text-center">
                                    <h3 class="text-info">{{ $stats['buyers'] }}</h3>
                                    <p>Buyers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-body text-center">
                                    <h3 class="text-warning">{{ $stats['active_users'] }}</h3>
                                    <p>Active Users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(request('type') == 'orders')
            <!-- Orders Report -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Orders Report</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-body text-center">
                                    <h3 class="text-primary">{{ $stats['total_orders'] }}</h3>
                                    <p>Total Orders</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-body text-center">
                                    <h3 class="text-success">{{ $stats['completed_orders'] }}</h3>
                                    <p>Completed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-body text-center">
                                    <h3 class="text-warning">{{ $stats['pending_orders'] }}</h3>
                                    <p>Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-info">
                                <div class="panel-body text-center">
                                    <h3 class="text-info">₱{{ number_format($stats['total_revenue'], 2) }}</h3>
                                    <p>Total Revenue</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(request('type') == 'products')
            <!-- Products Report -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Products Report</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-body text-center">
                                    <h3 class="text-primary">{{ $stats['total_products'] }}</h3>
                                    <p>Total Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-body text-center">
                                    <h3 class="text-success">{{ $stats['active_products'] }}</h3>
                                    <p>Active Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="panel panel-info">
                                <div class="panel-body text-center">
                                    <h3 class="text-info">{{ $stats['total_categories'] }}</h3>
                                    <p>Categories</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(request('type') == 'forums')
            <!-- Forums Report -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Forums Report</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-body text-center">
                                    <h3 class="text-primary">{{ $stats['total_forums'] }}</h3>
                                    <p>Total Forums</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-body text-center">
                                    <h3 class="text-success">{{ $stats['active_forums'] }}</h3>
                                    <p>Active Forums</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-danger">
                                <div class="panel-body text-center">
                                    <h3 class="text-danger">{{ $stats['flagged_forums'] }}</h3>
                                    <p>Flagged Forums</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-info">
                                <div class="panel-body text-center">
                                    <h3 class="text-info">{{ $stats['total_replies'] }}</h3>
                                    <p>Total Replies</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(request('type') == 'financial')
            <!-- Financial Report -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Financial Report</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-body text-center">
                                    <h3 class="text-primary">₱{{ number_format($stats['total_revenue'], 2) }}</h3>
                                    <p>Total Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-body text-center">
                                    <h3 class="text-success">₱{{ number_format($stats['period_revenue'], 2) }}</h3>
                                    <p>Period Revenue</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-info">
                                <div class="panel-body text-center">
                                    <h3 class="text-info">{{ $stats['total_orders'] }}</h3>
                                    <p>Total Orders</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-warning">
                                <div class="panel-body text-center">
                                    <h3 class="text-warning">₱{{ number_format($stats['average_order_value'], 2) }}</h3>
                                    <p>Avg Order Value</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if(request('type') == 'overview' || !request('type'))
<script>
// Orders by Status Chart
const ordersStatusCtx = document.getElementById('ordersStatusChart').getContext('2d');
const ordersStatusData = @json($chartData['orders_by_status']);

new Chart(ordersStatusCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(ordersStatusData),
        datasets: [{
            data: Object.values(ordersStatusData),
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

// Users by Role Chart
const usersRoleCtx = document.getElementById('usersRoleChart').getContext('2d');
const usersRoleData = @json($chartData['users_by_role']);

new Chart(usersRoleCtx, {
    type: 'pie',
    data: {
        labels: Object.keys(usersRoleData),
        datasets: [{
            data: Object.values(usersRoleData),
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
</script>
@endif
@endsection
