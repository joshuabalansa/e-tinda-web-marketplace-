@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><i class="entypo-eye"></i> Inventory Record Details</h4>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5><i class="entypo-info"></i> Transaction Information</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Product</label>
                                            <p class="form-control-static">
                                                <strong>{{ $inventory->product->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $inventory->product->category }}</small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Transaction Type</label>
                                            <p class="form-control-static">
                                                <span class="badge badge-{{ $inventory->transaction_type === 'adjustment' ? 'info' : ($inventory->transaction_type === 'purchase' ? 'success' : ($inventory->transaction_type === 'sale' ? 'primary' : 'warning')) }}">
                                                    {{ ucfirst($inventory->transaction_type) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Transaction Date</label>
                                            <p class="form-control-static">{{ $inventory->transaction_date->format('F d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Reference Number</label>
                                            <p class="form-control-static">{{ $inventory->reference_number ?: 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($inventory->notes)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Notes</label>
                                            <p class="form-control-static">{{ $inventory->notes }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Quantity Information -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5><i class="entypo-basket"></i> Quantity Information</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group text-center">
                                            <label class="control-label">Quantity In</label>
                                            <p class="form-control-static">
                                                <span class="text-success" style="font-size: 1.5em; font-weight: bold;">
                                                    {{ $inventory->quantity_in > 0 ? '+' . $inventory->quantity_in : '0' }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $inventory->product->unit_type }}</small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group text-center">
                                            <label class="control-label">Quantity Out</label>
                                            <p class="form-control-static">
                                                <span class="text-danger" style="font-size: 1.5em; font-weight: bold;">
                                                    {{ $inventory->quantity_out > 0 ? '-' . $inventory->quantity_out : '0' }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $inventory->product->unit_type }}</small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group text-center">
                                            <label class="control-label">Current Stock</label>
                                            <p class="form-control-static">
                                                <span class="text-primary" style="font-size: 1.5em; font-weight: bold;">
                                                    {{ $inventory->current_stock }}
                                                </span>
                                                <br>
                                                <small class="text-muted">{{ $inventory->product->unit_type }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Cost Information -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5><i class="entypo-tag"></i> Cost Information</h5>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label">Unit Cost</label>
                                    <p class="form-control-static">
                                        <strong style="font-size: 1.2em;">₱{{ number_format($inventory->unit_cost, 2) }}</strong>
                                        <br>
                                        <small class="text-muted">per {{ $inventory->product->unit_type }}</small>
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Total Value</label>
                                    <p class="form-control-static">
                                        <strong style="font-size: 1.5em; color: #28a745;">₱{{ number_format($inventory->total_value, 2) }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5><i class="entypo-box"></i> Product Details</h5>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label">Product Name</label>
                                    <p class="form-control-static">{{ $inventory->product->name }}</p>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Category</label>
                                    <p class="form-control-static">{{ $inventory->product->category }}</p>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Unit Type</label>
                                    <p class="form-control-static">{{ $inventory->product->unit_type }}</p>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Current Stock</label>
                                    <p class="form-control-static">
                                        <strong>{{ $inventory->product->stock_quantity }}</strong> {{ $inventory->product->unit_type }}
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">Status</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-{{ $inventory->product->status === 'available' ? 'success' : ($inventory->product->status === 'out_of_stock' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $inventory->product->status)) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5><i class="entypo-cog"></i> Actions</h5>
                            </div>
                            <div class="panel-body">
                                <div class="btn-group-vertical btn-block">
                                    <a href="{{ route('farmer.inventory.edit', $inventory) }}" class="btn btn-warning">
                                        <i class="entypo-pencil"></i> Edit Record
                                    </a>
                                    <a href="{{ route('farmer.inventory.index') }}" class="btn btn-default">
                                        <i class="entypo-arrow-left"></i> Back to Inventory
                                    </a>
                                    <a href="{{ route('farmer.products.show', $inventory->product) }}" class="btn btn-info">
                                        <i class="entypo-eye"></i> View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction History for this Product -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5><i class="entypo-clock"></i> Recent Transactions for {{ $inventory->product->name }}</h5>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Quantity In</th>
                                                <th>Quantity Out</th>
                                                <th>Stock After</th>
                                                <th>Reference</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $recentTransactions = \App\Models\Inventory::where('product_id', $inventory->product_id)
                                                    ->where('farmer_id', auth()->id())
                                                    ->orderBy('transaction_date', 'desc')
                                                    ->limit(5)
                                                    ->get();
                                            @endphp
                                            @forelse($recentTransactions as $transaction)
                                                <tr class="{{ $transaction->id === $inventory->id ? 'table-info' : '' }}">
                                                    <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $transaction->transaction_type === 'adjustment' ? 'info' : ($transaction->transaction_type === 'purchase' ? 'success' : ($transaction->transaction_type === 'sale' ? 'primary' : 'warning')) }}">
                                                            {{ ucfirst($transaction->transaction_type) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-success">{{ $transaction->quantity_in > 0 ? '+' . $transaction->quantity_in : '-' }}</td>
                                                    <td class="text-danger">{{ $transaction->quantity_out > 0 ? '-' . $transaction->quantity_out : '-' }}</td>
                                                    <td class="text-primary"><strong>{{ $transaction->current_stock }}</strong></td>
                                                    <td>{{ $transaction->reference_number ?: '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">No other transactions found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
    .form-control-static {
        padding-top: 7px;
        padding-bottom: 7px;
        margin-bottom: 0;
        background-color: transparent;
        border: none;
        font-size: 14px;
    }

    .panel-heading h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .panel-heading h5 i {
        margin-right: 8px;
    }

    .badge {
        font-weight: 500;
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 0;
    }

    .badge-info {
        background-color: #17a2b8;
        color: white;
    }

    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-primary {
        background-color: #007bff;
        color: white;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .btn {
        border-radius: 0;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .btn-group-vertical .btn {
        margin-bottom: 5px;
    }

    .btn-group-vertical .btn:last-child {
        margin-bottom: 0;
    }

    .btn-warning {
        background-color: #f39c12;
        border-color: #f39c12;
        color: white;
    }

    .btn-warning:hover {
        background-color: #e67e22;
        border-color: #e67e22;
    }

    .btn-default {
        background-color: #f8f9fa;
        border-color: #ddd;
        color: #333;
    }

    .btn-default:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background-color: #138496;
        border-color: #138496;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-primary {
        color: #007bff !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .table-info {
        background-color: #d1ecf1 !important;
    }

    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
    }

    .table td {
        vertical-align: middle;
    }

    .control-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
</style>
@endpush
