@extends('layouts.farmer')

@section('title', 'Etinda')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-sm-12">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-chart-bar"></i> Analytics Dashboard</h4>
                    </div>
                    <div class="panel-options">
                        <button type="button" class="btn btn-primary btn-sm" onclick="refreshCharts()">
                            <i class="entypo-arrows-ccw"></i> Refresh
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="exportData()">
                            <i class="entypo-download"></i> Export
                        </button>
                    </div>
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
                    <span>This month's earnings</span>
                </div>
                <div class="tile-progressbar">
                    <span data-fill="100%"></span>
                </div>
                <div class="tile-footer">
                    <h4>₱{{ number_format($analyticsData['revenue']['current_month'], 2) }}</h4>
                    @if($analyticsData['revenue']['growth_percentage'] > 0)
                        <span class="text-success">
                            <i class="entypo-up"></i> {{ $analyticsData['revenue']['growth_percentage'] }}% growth
                        </span>
                    @elseif($analyticsData['revenue']['growth_percentage'] < 0)
                        <span class="text-danger">
                            <i class="entypo-down"></i> {{ abs($analyticsData['revenue']['growth_percentage']) }}% decline
                        </span>
                    @else
                        <span>No change</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="tile-progress tile-aqua">
                <div class="tile-header">
                    <h3>Total Orders</h3>
                    <span>This month's orders</span>
                </div>
                <div class="tile-progressbar">
                    <span data-fill="100%"></span>
                </div>
                <div class="tile-footer">
                    <h4>{{ $analyticsData['orders']['monthly_orders']->sum('order_count') }}</h4>
                    <span>orders processed</span>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="tile-progress tile-orange">
                <div class="tile-header">
                    <h3>Active Products</h3>
                    <span>Products with sales</span>
                </div>
                <div class="tile-progressbar">
                    <span data-fill="100%"></span>
                </div>
                <div class="tile-footer">
                    <h4>{{ $analyticsData['products']['top_products']->count() }}</h4>
                    <span>products selling</span>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="tile-progress tile-red">
                <div class="tile-header">
                    <h3>Low Stock Items</h3>
                    <span>Need restocking</span>
                </div>
                <div class="tile-progressbar">
                    <span data-fill="100%"></span>
                </div>
                <div class="tile-footer">
                    <h4>{{ $analyticsData['inventory']['low_stock']->count() }}</h4>
                    <span>need attention</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-sm-8">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-chart-line"></i> Revenue Trend (Last 12 Months)</h4>
                    </div>
                    <div class="panel-options">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="entypo-cog"></i> Options
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" onclick="changeChartPeriod('revenue', '12months')">Last 12 Months</a></li>
                                <li><a href="#" onclick="changeChartPeriod('revenue', '6months')">Last 6 Months</a></li>
                                <li><a href="#" onclick="changeChartPeriod('revenue', '3months')">Last 3 Months</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="col-sm-4">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-pie-chart"></i> Order Status Distribution</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="chart-pie">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <div class="text-center">
                        @foreach($analyticsData['orders']['status_distribution'] as $status)
                            <span class="label label-default" style="margin-right: 5px;">
                                {{ ucfirst($status['status']) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mb-4">
        <!-- Top Products -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-star"></i> Top Selling Products</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="chart-bar">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Performance -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-tag"></i> Category Performance</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="chart-bar">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 3 -->
    <div class="row mb-4">
        <!-- Monthly Sales -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-chart-area"></i> Monthly Sales Volume</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="chart-area">
                        <canvas id="salesVolumeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Levels -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-box"></i> Current Stock Levels</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="chart-bar">
                        <canvas id="stockLevelsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row">
        <!-- Low Stock Alert -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-warning"></i> Low Stock Alert</h4>
                    </div>
                </div>
                <div class="panel-body">
                    @if($analyticsData['inventory']['low_stock']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analyticsData['inventory']['low_stock'] as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->category }}</td>
                                            <td>
                                                <span class="badge badge-warning">{{ $product->stock_quantity }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('farmer.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Restock
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-success">All products are well stocked!</h5>
                            <p class="text-muted">No low stock alerts at this time.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-sm-6">
            <div class="panel panel-default" data-collapsed="0">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4><i class="entypo-clock"></i> Recent Activity Summary</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="tile-progress tile-aqua">
                                <div class="tile-header">
                                    <h3>Orders Today</h3>
                                    <span>Current day orders</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>{{ $analyticsData['orders']['monthly_orders']->where('month', Carbon\Carbon::now()->month)->sum('order_count') }}</h4>
                                    <span>orders</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="tile-progress tile-green">
                                <div class="tile-header">
                                    <h3>Revenue Today</h3>
                                    <span>Estimated daily revenue</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>₱{{ number_format($analyticsData['revenue']['current_month'] / 30, 2) }}</h4>
                                    <span>estimated</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="tile-progress tile-orange">
                                <div class="tile-header">
                                    <h3>Products Sold</h3>
                                    <span>Today's sales</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>{{ $analyticsData['sales']['daily']->sum('total_quantity') }}</h4>
                                    <span>items sold</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="tile-progress tile-red">
                                <div class="tile-header">
                                    <h3>New Customers</h3>
                                    <span>This month</span>
                                </div>
                                <div class="tile-progressbar">
                                    <span data-fill="100%"></span>
                                </div>
                                <div class="tile-footer">
                                    <h4>{{ $analyticsData['customers']['monthly_customers']->where('month', Carbon\Carbon::now()->month)->sum('customer_count') }}</h4>
                                    <span>customers</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart configurations and data
const analyticsData = @json($analyticsData);

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: analyticsData.revenue.monthly_revenue.map(item => {
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${monthNames[item.month - 1]} ${item.year}`;
        }).reverse(),
        datasets: [{
            label: 'Revenue (₱)',
            data: analyticsData.revenue.monthly_revenue.map(item => item.revenue).reverse(),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenue: ₱' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});

// Order Status Chart
const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
const orderStatusChart = new Chart(orderStatusCtx, {
    type: 'doughnut',
    data: {
        labels: analyticsData.orders.status_distribution.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
        datasets: [{
            data: analyticsData.orders.status_distribution.map(item => item.order_count),
            backgroundColor: [
                '#e74a3b',
                '#f6c23e',
                '#1cc88a',
                '#36b9cc',
                '#858796'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Top Products Chart
const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
const topProductsChart = new Chart(topProductsCtx, {
    type: 'bar',
    data: {
        labels: analyticsData.products.top_products.slice(0, 5).map(item => item.name),
        datasets: [{
            label: 'Quantity Sold',
            data: analyticsData.products.top_products.slice(0, 5).map(item => item.total_sold),
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Category Performance Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'bar',
    data: {
        labels: analyticsData.products.category_performance.map(item => item.category),
        datasets: [{
            label: 'Revenue (₱)',
            data: analyticsData.products.category_performance.map(item => item.total_revenue),
            backgroundColor: 'rgba(255, 99, 132, 0.8)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₱' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Sales Volume Chart
const salesVolumeCtx = document.getElementById('salesVolumeChart').getContext('2d');
const salesVolumeChart = new Chart(salesVolumeCtx, {
    type: 'line',
    data: {
        labels: analyticsData.sales.monthly.map(item => {
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${monthNames[item.month - 1]} ${item.year}`;
        }).reverse(),
        datasets: [{
            label: 'Quantity Sold',
            data: analyticsData.sales.monthly.map(item => item.total_quantity).reverse(),
            borderColor: 'rgb(255, 159, 64)',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Stock Levels Chart
const stockLevelsCtx = document.getElementById('stockLevelsChart').getContext('2d');
const stockLevelsChart = new Chart(stockLevelsCtx, {
    type: 'bar',
    data: {
        labels: analyticsData.inventory.stock_levels.slice(0, 10).map(item => item.name),
        datasets: [{
            label: 'Current Stock',
            data: analyticsData.inventory.stock_levels.slice(0, 10).map(item => item.stock_quantity),
            backgroundColor: function(context) {
                const value = context.parsed.y;
                return value <= 10 ? 'rgba(220, 53, 69, 0.8)' : 'rgba(40, 167, 69, 0.8)';
            },
            borderColor: function(context) {
                const value = context.parsed.y;
                return value <= 10 ? 'rgba(220, 53, 69, 1)' : 'rgba(40, 167, 69, 1)';
            },
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Utility functions
function refreshCharts() {
    location.reload();
}

function exportData() {
    try {
        // Simple CSV export functionality
        const csvContent = generateCSV();
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;

        // Generate filename with current date
        const date = new Date();
        const dateStr = date.toISOString().split('T')[0];
        a.download = `analytics_data_${dateStr}.csv`;

        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);

        // Show success notification
        showNotification('Analytics data exported successfully!');
    } catch (error) {
        console.error('Export error:', error);
        showNotification('Error exporting data. Please try again.', 'error');
    }
}

function generateCSV() {
    let csv = 'Metric,Value\n';
    csv += `Total Revenue (This Month),₱${analyticsData.revenue.current_month.toFixed(2)}\n`;
    csv += `Growth Percentage,${analyticsData.revenue.growth_percentage}%\n`;

    // Calculate total orders
    const totalOrders = analyticsData.orders.monthly_orders.reduce((sum, item) => sum + item.order_count, 0);
    csv += `Total Orders,${totalOrders}\n`;

    csv += `Active Products,${analyticsData.products.top_products.length}\n`;
    csv += `Low Stock Items,${analyticsData.inventory.low_stock.length}\n`;

    // Add monthly revenue data
    csv += '\nMonthly Revenue\n';
    csv += 'Month,Year,Revenue\n';
    analyticsData.revenue.monthly_revenue.forEach(item => {
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        csv += `${monthNames[item.month - 1]},${item.year},₱${item.revenue.toFixed(2)}\n`;
    });

    // Add top products
    csv += '\nTop Selling Products\n';
    csv += 'Product Name,Quantity Sold\n';
    analyticsData.products.top_products.slice(0, 10).forEach(item => {
        csv += `"${item.name}",${item.total_sold}\n`;
    });

    // Add order status distribution
    csv += '\nOrder Status Distribution\n';
    csv += 'Status,Count\n';
    analyticsData.orders.status_distribution.forEach(item => {
        csv += `${item.status},${item.order_count}\n`;
    });

    // Add low stock items
    if (analyticsData.inventory.low_stock.length > 0) {
        csv += '\nLow Stock Items\n';
        csv += 'Product Name,Category,Stock Quantity\n';
        analyticsData.inventory.low_stock.forEach(item => {
            csv += `"${item.name}","${item.category}",${item.stock_quantity}\n`;
        });
    }

    return csv;
}

function changeChartPeriod(chartType, period) {
    event.preventDefault();

    if (chartType === 'revenue') {
        let monthsToShow = 12;
        if (period === '6months') monthsToShow = 6;
        if (period === '3months') monthsToShow = 3;

        // Filter the data
        const filteredData = analyticsData.revenue.monthly_revenue.slice(-monthsToShow);

        // Update chart data
        revenueChart.data.labels = filteredData.map(item => {
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${monthNames[item.month - 1]} ${item.year}`;
        }).reverse();

        revenueChart.data.datasets[0].data = filteredData.map(item => item.revenue).reverse();
        revenueChart.update();

        // Show success message
        showNotification(`Chart updated to show last ${monthsToShow} months`);
    }
}

function showNotification(message, type = 'success') {
    // Create a simple notification
    const notification = document.createElement('div');
    notification.className = type === 'error' ? 'alert alert-danger' : 'alert alert-success';
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

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}
</script>

<style>
.chart-area {
    position: relative;
    height: 300px;
}

.chart-bar {
    position: relative;
    height: 300px;
}

.chart-pie {
    position: relative;
    height: 300px;
}

.text-success {
    color: #27ae60 !important;
}

.text-danger {
    color: #e74c3c !important;
}
</style>
@endsection
