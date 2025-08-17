@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Welcome to E-Tinda Marketplace</h4>
                </div>
                <div class="panel-options">
                    <span class="text-muted">{{ now()->format('F j, Y') }}</span>
                </div>
            </div>
            <div class="panel-body">
                <div class="well">
                    <h3>Welcome to the site <strong>{{ ucwords(Auth::user()->name) }}</strong></h3>
                    <p class="text-muted">Manage your farm products and track your orders from this dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-9">
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default" data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">Product Overview</div>
                        <div class="panel-options">
                            <a href="{{ route('farmer.products.index') }}" data-rel="collapse">
                                <i class="entypo-down-open"></i>
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <center><span class="chart"></span></center>
                        <div class="text-center mt-3">
                            <a href="{{ route('farmer.products.index') }}" class="btn btn-primary btn-sm">
                                <i class="entypo-newspaper"></i> Manage Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="panel panel-default" data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">Recent Orders</div>
                        <div class="panel-options">
                            <a href="{{ route('farmer.orders.index') }}" data-rel="collapse">
                                <i class="entypo-down-open"></i>
                            </a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td><a href="{{ route('farmer.orders.index') }}" class="btn btn-sm btn-info">View</a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td><span class="badge badge-success">Delivered</span></td>
                                    <td><a href="{{ route('farmer.orders.index') }}" class="btn btn-sm btn-info">View</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>Farm Performance Overview</h4>
                    <br />
                    <small>Current month statistics</small>
                </h4>
                </div>
                <div class="panel-options">
                    <a href="#" data-rel="collapse">
                        <i class="entypo-down-open"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body no-padding">
                <div id="rickshaw-chart-demo-2">
                    <div id="rickshaw-legend"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="tile-progress tile-primary">
            <div class="tile-header">
                <h3>Total Products</h3>
                <span>Your farm inventory</span>
            </div>
            <div class="tile-progressbar">
                <span data-fill="75%"></span>
            </div>
            <div class="tile-footer">
                <h4>15</h4>
                <span>products listed</span>
            </div>
        </div>

        <div class="tile-progress tile-success">
            <div class="tile-header">
                <h3>Active Orders</h3>
                <span>Currently processing</span>
            </div>
            <div class="tile-progressbar">
                <span data-fill="45%"></span>
            </div>
            <div class="tile-footer">
                <h4>8</h4>
                <span>orders active</span>
            </div>
        </div>

        <div class="tile-progress tile-info">
            <div class="tile-header">
                <h3>Monthly Revenue</h3>
                <span>This month's earnings</span>
            </div>
            <div class="tile-progressbar">
                <span data-fill="60%"></span>
            </div>
            <div class="tile-footer">
                <h4>₱12,450</h4>
                <span>total earned</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">Quick Actions</div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center">
                            <a href="{{ route('farmer.products.create') }}" class="btn btn-primary btn-lg btn-block">
                                <i class="entypo-plus" style="font-size: 2rem;"></i>
                                <br>Add Product
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center">
                            <a href="{{ route('farmer.products.index') }}" class="btn btn-info btn-lg btn-block">
                                <i class="entypo-newspaper" style="font-size: 2rem;"></i>
                                <br>Manage Products
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center">
                            <a href="{{ route('farmer.orders.index') }}" class="btn btn-success btn-lg btn-block">
                                <i class="entypo-mail" style="font-size: 2rem;"></i>
                                <br>View Orders
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="text-center">
                            <a href="#" class="btn btn-warning btn-lg btn-block">
                                <i class="entypo-doc-text" style="font-size: 2rem;"></i>
                                <br>Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .panel {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .tile-progress {
        margin-bottom: 20px;
    }
    .btn-lg {
        padding: 20px;
        font-size: 1rem;
        border-radius: 8px;
    }
    .well {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
    }
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    .table {
        margin-bottom: 0;
    }
    .table th {
        border-top: none;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tile progress bars
    $('.tile-progressbar span').each(function() {
        var fill = $(this).data('fill');
        $(this).css('width', fill);
    });

    // Initialize chart (if you have chart data)
    if (typeof Rickshaw !== 'undefined') {
        var seriesData = [ [], [] ];
        var random = new Rickshaw.Fixtures.RandomData(50);

        for (var i = 0; i < 30; i++) {
            random.addData(seriesData);
        }

        var graph = new Rickshaw.Graph({
            element: document.getElementById("rickshaw-chart-demo-2"),
            height: 217,
            renderer: 'area',
            stroke: false,
            preserve: true,
            series: [{
                color: '#00a651',
                data: seriesData[0],
                name: 'Sales'
            }, {
                color: '#359ade',
                data: seriesData[1],
                name: 'Orders'
            }]
        });

        graph.render();
    }
});
</script>
@endpush