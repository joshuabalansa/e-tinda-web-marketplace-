@extends('layouts.dashboard')
@section('content')

<div class="main-content">
    <div class="row">
        <!-- Profile Info and Notifications -->
        <div class="col-md-6 col-sm-8 clearfix">
            <ul class="user-info pull-left pull-none-xsm">
                <!-- Profile Info -->
                <li class="profile-info dropdown">
                    <h3>
                        Welcome, {{ ucwords(Auth::user()->name) }}
                    </h3>
                    <ul class="dropdown-menu">
                        <!-- Reverse Caret -->
                        <li class="caret"></li>
                        <!-- Profile sub-links -->
                        <li>
                            <a href="{{ route('profile.edit') }}">
                                <i class="entypo-user"></i>
                                Edit Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('buyer.orders') }}">
                                <i class="entypo-basket"></i>
                                My Orders
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('buyer.wishlist') }}">
                                <i class="entypo-heart"></i>
                                Wishlist
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- Raw Links -->
        <div class="col-md-6 col-sm-4 clearfix hidden-xs">
            <ul class="list-inline links-list pull-right">
                <li class="sep"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0"
                        onsubmit="return confirm('Are you sure you want to log out?');">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm"
                            style="background-color: #e74c3c; color: white; border: none; padding: 8px 15px; border-radius: 4px; font-size: 13px; transition: all 0.3s ease;">
                            {{ __('Log Out') }} <i class="entypo-logout right"></i>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <hr />

    <!-- Buyer Dashboard Stats -->
    <div class="row">
        <div class="col-sm-3 col-xs-6">
            <div class="tile-stats tile-red">
                <div class="icon"><i class="entypo-basket"></i></div>
                <div class="num" data-start="0" data-end="{{ Auth::user()->orders()->count() }}" data-postfix="" data-duration="1500" data-delay="0">0</div>
                <h3>Total Orders</h3>
                <p>Your purchase history</p>
            </div>
        </div>

        <div class="col-sm-3 col-xs-6">
            <div class="tile-stats tile-green">
                <div class="icon"><i class="entypo-heart"></i></div>
                <div class="num" data-start="0" data-end="{{ Auth::user()->wishlist()->count() ?? 0 }}" data-postfix="" data-duration="1500" data-delay="600">0</div>
                <h3>Wishlist Items</h3>
                <p>Saved for later</p>
            </div>
        </div>

        <div class="clear visible-xs"></div>

        <div class="col-sm-3 col-xs-6">
            <div class="tile-stats tile-aqua">
                <div class="icon"><i class="entypo-star"></i></div>
                <div class="num" data-start="0" data-end="{{ Auth::user()->reviews()->count() ?? 0 }}" data-postfix="" data-duration="1500" data-delay="1200">0</div>
                <h3>Reviews Given</h3>
                <p>Your feedback count</p>
            </div>
        </div>

        <div class="col-sm-3 col-xs-6">
            <div class="tile-stats tile-blue">
                <div class="icon"><i class="entypo-users"></i></div>
                <div class="num" data-start="0" data-end="{{ \App\Models\User::where('role', 'farmer')->count() }}" data-postfix="" data-duration="1500" data-delay="1800">0</div>
                <h3>Active Farmers</h3>
                <p>Available sellers</p>
            </div>
        </div>
    </div>

    <br />

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">Quick Actions</div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="{{ route('shop.index') }}" class="btn btn-success btn-lg btn-block" style="margin-bottom: 15px;">
                                <i class="entypo-shop"></i> Browse Products
                            </a>
                            <a href="{{ route('buyer.orders') }}" class="btn btn-info btn-lg btn-block" style="margin-bottom: 15px;">
                                <i class="entypo-basket"></i> View Orders
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('buyer.wishlist') }}" class="btn btn-warning btn-lg btn-block" style="margin-bottom: 15px;">
                                <i class="entypo-heart"></i> My Wishlist
                            </a>
                            <a href="{{ route('forums.index') }}" class="btn btn-primary btn-lg btn-block" style="margin-bottom: 15px;">
                                <i class="entypo-chat"></i> Farmer Forums
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>
                            Recent Activity
                            <br />
                            <small>Your latest actions</small>
                        </h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item">
                            <i class="entypo-basket text-success"></i> Order #1234 placed
                            <small class="text-muted pull-right">2 hours ago</small>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="entypo-heart text-danger"></i> Added tomatoes to wishlist
                            <small class="text-muted pull-right">1 day ago</small>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="entypo-star text-warning"></i> Reviewed organic rice
                            <small class="text-muted pull-right">3 days ago</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br />

    <!-- Recent Orders and Popular Products -->
    <div class="row">
        <div class="col-sm-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">Recent Orders</div>
                    <div class="panel-options">
                        <a href="{{ route('buyer.orders') }}" class="btn btn-sm btn-default">View All</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Product</th>
                                    <th>Farmer</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(Auth::user()->orders()->latest()->take(5)->get() as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->items->first()->product->name ?? 'N/A' }}</td>
                                    <td>{{ $order->items->first()->product->user->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="label label-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No orders yet. <a href="{{ route('shop.index') }}">Start shopping!</a></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">Popular Categories</div>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <a href="{{ route('shop.index') }}?category=Crop+Farming" class="list-group-item">
                            <i class="entypo-leaf text-success"></i> Crop Farming
                            <span class="badge pull-right">{{ \App\Models\Product::where('category', 'Crop Farming')->count() }}</span>
                        </a>
                        <a href="{{ route('shop.index') }}?category=Livestock" class="list-group-item">
                            <i class="entypo-users text-info"></i> Livestock
                            <span class="badge pull-right">{{ \App\Models\Product::where('category', 'Livestock')->count() }}</span>
                        </a>
                        <a href="{{ route('shop.index') }}?category=Organic+Farming" class="list-group-item">
                            <i class="entypo-star text-warning"></i> Organic Farming
                            <span class="badge pull-right">{{ \App\Models\Product::where('category', 'Organic Farming')->count() }}</span>
                        </a>
                        <a href="{{ route('shop.index') }}?category=Market+Prices" class="list-group-item">
                            <i class="entypo-chart-line text-primary"></i> Market Prices
                            <span class="badge pull-right">{{ \App\Models\Product::where('category', 'Market Prices')->count() }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br />

    <!-- Footer -->
    <footer class="main">
        &copy; {{ date('Y') }} <strong>E-Tinda Marketplace</strong> - Buyer Dashboard
    </footer>
</div>

@endsection
