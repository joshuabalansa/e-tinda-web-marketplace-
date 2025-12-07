@extends('layouts.admin')

@section('content')
<div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="page-title">Analytics Dashboard</h1>
                    <p class="page-description">Comprehensive analytics and insights for your marketplace</p>
                </div>
                <div class="col-md-6 text-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" onclick="refreshCharts()">
                            <i class="entypo-arrows-ccw"></i> Refresh Data
                        </button>
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-success">
                            <i class="entypo-doc-text"></i> View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-primary">
                    <div class="panel-body text-center">
                        <h3 class="text-primary">{{ $stats['total_users'] }}</h3>
                        <p>Total Users</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-success">
                    <div class="panel-body text-center">
                        <h3 class="text-success">{{ $stats['total_farmers'] }}</h3>
                        <p>Farmers</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-info">
                    <div class="panel-body text-center">
                        <h3 class="text-info">{{ $stats['total_buyers'] }}</h3>
                        <p>Buyers</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-warning">
                    <div class="panel-body text-center">
                        <h3 class="text-warning">{{ $stats['total_products'] }}</h3>
                        <p>Products</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-danger">
                    <div class="panel-body text-center">
                        <h3 class="text-danger">{{ $stats['total_orders'] }}</h3>
                        <p>Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <h3 class="text-muted">₱{{ number_format($stats['total_revenue'], 2) }}</h3>
                        <p>Revenue</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Monthly Orders Chart -->
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Monthly Orders</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="ordersChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Monthly Revenue</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Charts Row -->
        <div class="row">
            <!-- User Registrations Chart -->
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">User Registrations</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="usersChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Category Distribution Chart -->
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Product Categories</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="categoriesChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity and Top Performers -->
        <div class="row">
            <!-- Recent Activity -->
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Recent Activity</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            @foreach($recentActivity['recent_orders'] as $order)
                            <div class="list-group-item">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="list-group-item-heading">New Order #{{ $order->id }}</h5>
                                        <p class="list-group-item-text">by {{ $order->user->name }} - ₱{{ number_format($order->total_amount, 2) }}</p>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Performers -->
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Top Farmers</h3>
                    </div>
                    <div class="panel-body">
                        <div class="list-group">
                            @foreach($topPerformers['top_farmers'] as $farmer)
                            <div class="list-group-item">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="list-group-item-heading">{{ $farmer->name }}</h5>
                                        <p class="list-group-item-text">{{ $farmer->products_count }} products</p>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <span class="label label-success">{{ $farmer->products_count }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div class="row">
            <div class="col-lg-3">
                <div class="panel panel-info">
                    <div class="panel-body text-center">
                        <h3 class="text-info">{{ $stats['active_forums'] }}</h3>
                        <p>Active Forums</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="panel panel-warning">
                    <div class="panel-body text-center">
                        <h3 class="text-warning">{{ $stats['total_replies'] }}</h3>
                        <p>Forum Replies</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="panel panel-danger">
                    <div class="panel-body text-center">
                        <h3 class="text-danger">{{ $stats['flagged_content'] }}</h3>
                        <p>Flagged Content</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="panel panel-success">
                    <div class="panel-body text-center">
                        <h3 class="text-success">{{ $stats['completed_orders'] }}</h3>
                        <p>Completed Orders</p>
                    </div>
                </div>
            </div>
        </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart data from PHP
const monthlyOrdersData = @json($chartData['monthly_orders']);
const monthlyRevenueData = @json($chartData['monthly_revenue']);
const userRegistrationsData = @json($chartData['user_registrations']);
const categoryData = @json($chartData['category_distribution']);

// Chart configurations
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
        }
    },
    scales: {
        y: {
            beginAtZero: true
        }
    }
};

// Monthly Orders Chart
const ordersCtx = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Orders',
            data: Object.values(monthlyOrdersData),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: chartOptions
});

// Monthly Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Revenue (₱)',
            data: Object.values(monthlyRevenueData),
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: chartOptions
});

// User Registrations Chart
const usersCtx = document.getElementById('usersChart').getContext('2d');
new Chart(usersCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'New Users',
            data: Object.values(userRegistrationsData),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    },
    options: chartOptions
});

// Categories Chart
const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
new Chart(categoriesCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(categoryData),
        datasets: [{
            data: Object.values(categoryData),
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40'
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

// Refresh charts function
function refreshCharts() {
    location.reload();
}
</script>
@endsection
