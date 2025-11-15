@extends('layouts.dashboard')
@section('content')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Farmer Dashboard</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Farmer Overview</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="products-count">0</h3>
                            <p class="stat-text">Total Products</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="daily-visitors">0</h3>
                            <p class="stat-text">Daily Visitors</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="new-messages">0</h3>
                            <p class="stat-text">New Messages</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="subscribers">0</h3>
                            <p class="stat-text">Subscribers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Sales Analytics</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="refresh-chart">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Product Categories</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Tasks -->
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Orders</h5>
                    <div class="card-tools">
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="activity-feed" id="recent-orders">
                        <!-- Orders will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Tasks</h5>
                </div>
                <div class="card-body">
                    <div class="task-list" id="task-list">
                        <div class="task-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="task1">
                                <label class="form-check-label" for="task1">
                                    Review product listings
                                </label>
                            </div>
                        </div>
                        <div class="task-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="task2">
                                <label class="form-check-label" for="task2">
                                    Update inventory
                                </label>
                            </div>
                        </div>
                        <div class="task-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="task3">
                                <label class="form-check-label" for="task3">
                                    Respond to customer inquiries
                                </label>
                            </div>
                        </div>
                        <div class="task-item">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="task4">
                                <label class="form-check-label" for="task4">
                                    Process pending orders
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <input type="text" class="form-control" id="new-task" placeholder="Add new task...">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Weather & Market Info</h5>
                </div>
                <div class="card-body">
                    <div class="weather-info">
                        <div class="weather-item d-flex align-items-center mb-3">
                            <i class="fas fa-sun text-warning me-3"></i>
                            <div>
                                <h6 class="mb-0">Today's Weather</h6>
                                <small class="text-muted">Sunny, 25°C - Perfect for farming</small>
                            </div>
                        </div>
                        <div class="market-item d-flex align-items-center mb-3">
                            <i class="fas fa-chart-line text-success me-3"></i>
                            <div>
                                <h6 class="mb-0">Market Trends</h6>
                                <small class="text-muted">Organic produce demand +15% this week</small>
                            </div>
                        </div>
                        <div class="crop-item d-flex align-items-center">
                            <i class="fas fa-leaf text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0">Crop Health</h6>
                                <small class="text-muted">All crops in excellent condition</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Products</h5>
                    <div class="card-tools">
                        <a href="#" class="btn btn-primary btn-sm">Add New Product</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="products-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="products-tbody">
                                <!-- Products will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.stat-card {
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.stat-icon i {
    font-size: 24px;
    color: white;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    color: #333;
}

.stat-text {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.card {
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.card-title {
    margin: 0;
    font-weight: 600;
}

.task-item {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.task-item:last-child {
    border-bottom: none;
}

.activity-feed {
    max-height: 300px;
    overflow-y: auto;
}

.weather-info .weather-item,
.weather-info .market-item,
.weather-info .crop-item {
    padding: 15px;
    border-radius: 8px;
    background: #f8f9fa;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #333;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard
    initializeDashboard();

    // Event listeners
    document.getElementById('refresh-chart').addEventListener('click', refreshCharts);
    document.getElementById('new-task').addEventListener('keypress', handleNewTask);

    // Initialize charts
    initializeCharts();

    // Load sample data
    loadSampleData();
});

function initializeDashboard() {
    // Animate stats on load
    animateStats();

    // Set up auto-refresh for stats
    setInterval(updateStats, 30000); // Update every 30 seconds
}

function animateStats() {
    const stats = [
        { id: 'products-count', target: 83, duration: 1500 },
        { id: 'daily-visitors', target: 135, duration: 1500 },
        { id: 'new-messages', target: 23, duration: 1500 },
        { id: 'subscribers', target: 52, duration: 1500 }
    ];

    stats.forEach(stat => {
        animateNumber(stat.id, stat.target, stat.duration);
    });
}

function animateNumber(elementId, target, duration) {
    const element = document.getElementById(elementId);
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current);
    }, 16);
}

function initializeCharts() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Sales',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Vegetables', 'Fruits', 'Grains', 'Dairy'],
            datasets: [{
                data: [30, 25, 25, 20],
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
                    position: 'bottom'
                }
            }
        }
    });

    // Store charts globally for refresh
    window.salesChart = salesChart;
    window.categoryChart = categoryChart;
}

function loadSampleData() {
    // Load recent orders
    const recentOrders = [
        { id: 1, customer: 'John Doe', product: 'Organic Tomatoes', amount: '$25.00', status: 'Completed' },
        { id: 2, customer: 'Jane Smith', product: 'Fresh Lettuce', amount: '$15.00', status: 'Pending' },
        { id: 3, customer: 'Mike Johnson', product: 'Carrots Bundle', amount: '$20.00', status: 'Processing' }
    ];

    displayRecentOrders(recentOrders);

    // Load products
    const products = [
        { name: 'Organic Tomatoes', category: 'Vegetables', price: '$5.99/lb', stock: '50 lbs', status: 'In Stock' },
        { name: 'Fresh Lettuce', category: 'Vegetables', price: '$2.99/head', stock: '25 heads', status: 'In Stock' },
        { name: 'Carrots Bundle', category: 'Vegetables', price: '$3.99/bundle', stock: '30 bundles', status: 'Low Stock' }
    ];

    displayProducts(products);
}

function displayRecentOrders(orders) {
    const container = document.getElementById('recent-orders');
    container.innerHTML = '';

    orders.forEach(order => {
        const orderElement = document.createElement('div');
        orderElement.className = 'd-flex align-items-center mb-3';
        orderElement.innerHTML = `
            <div class="flex-shrink-0">
                <div class="avatar avatar-sm bg-primary rounded-circle">
                    <i class="fas fa-shopping-cart text-white"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="mb-0">${order.customer}</h6>
                <small class="text-muted">${order.product} - ${order.amount}</small>
                <span class="badge bg-${getStatusColor(order.status)} ms-2">${order.status}</span>
            </div>
        `;
        container.appendChild(orderElement);
    });
}

function displayProducts(products) {
    const tbody = document.getElementById('products-tbody');
    tbody.innerHTML = '';

    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm bg-success rounded-circle me-2">
                        <i class="fas fa-seedling text-white"></i>
                    </div>
                    <span>${product.name}</span>
                </div>
            </td>
            <td>${product.category}</td>
            <td>${product.price}</td>
            <td>${product.stock}</td>
            <td><span class="badge bg-${getStatusColor(product.status)}">${product.status}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function getStatusColor(status) {
    switch(status.toLowerCase()) {
        case 'completed':
        case 'in stock':
            return 'success';
        case 'pending':
        case 'processing':
            return 'warning';
        case 'low stock':
            return 'danger';
        default:
            return 'secondary';
    }
}

function refreshCharts() {
    // Refresh chart data
    if (window.salesChart) {
        const newData = Array.from({length: 6}, () => Math.floor(Math.random() * 20) + 1);
        window.salesChart.data.datasets[0].data = newData;
        window.salesChart.update();
    }

    // Show refresh feedback
    const btn = document.getElementById('refresh-chart');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i> Updated';
    btn.classList.add('btn-success');

    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.classList.remove('btn-success');
    }, 2000);
}

function handleNewTask(e) {
    if (e.key === 'Enter' && e.target.value.trim()) {
        addTask(e.target.value.trim());
        e.target.value = '';
    }
}

function addTask(title) {
    const taskList = document.getElementById('task-list');
    const taskItem = document.createElement('div');
    taskItem.className = 'task-item';
    taskItem.innerHTML = `
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="task-${Date.now()}">
            <label class="form-check-label" for="task-${Date.now()}">
                ${title}
            </label>
        </div>
    `;

    taskList.appendChild(taskItem);
}

function updateStats() {
    // Simulate real-time updates
    const stats = [
        { id: 'daily-visitors', change: Math.floor(Math.random() * 10) - 5 },
        { id: 'new-messages', change: Math.floor(Math.random() * 5) },
        { id: 'subscribers', change: Math.floor(Math.random() * 3) }
    ];

    stats.forEach(stat => {
        const element = document.getElementById(stat.id);
        const currentValue = parseInt(element.textContent);
        const newValue = Math.max(0, currentValue + stat.change);
        element.textContent = newValue;
    });
}

// Task completion tracking
document.addEventListener('change', function(e) {
    if (e.target.type === 'checkbox') {
        const label = e.target.nextElementSibling;
        if (e.target.checked) {
            label.style.textDecoration = 'line-through';
            label.style.color = '#6c757d';
        } else {
            label.style.textDecoration = 'none';
            label.style.color = '#212529';
        }
    }
});
</script>
@endpush