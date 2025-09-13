@extends('layouts.farmer')

@section('content')
<!-- Add New Inventory Record Button -->
<div class="row mb-4">
    <div class="col-sm-12">
        <div class="text-left">
            <a href="{{ route('farmer.inventory.create') }}" class="btn btn-primary btn-sm" style="margin-bottom: 20px;">
                <i class="entypo-plus"></i> Add Stock
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><i class="entypo-box"></i> Inventory Management</h4>
                </div>
            </div>
            <div class="panel-body">
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-sm-3">
                        <div class="tile-progress tile-aqua">
                            <div class="tile-header">
                                <h3>Total Products</h3>
                                <span>Products in inventory</span>
                            </div>
                            <div class="tile-progressbar">
                                <span data-fill="100%"></span>
                            </div>
                            <div class="tile-footer">
                                <h4>{{ $stats['total_products'] }}</h4>
                                <span>products</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="tile-progress tile-red">
                            <div class="tile-header">
                                <h3>Low Stock</h3>
                                <span>Products needing restock</span>
                            </div>
                            <div class="tile-progressbar">
                                <span data-fill="{{ $stats['total_products'] > 0 ? ($stats['low_stock_products'] / $stats['total_products'] * 100) : '0' }}%"></span>
                            </div>
                            <div class="tile-footer">
                                <h4>{{ $stats['low_stock_products'] }}</h4>
                                <span>need attention</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="tile-progress tile-green">
                            <div class="tile-header">
                                <h3>Total Value</h3>
                                <span>Inventory worth</span>
                            </div>
                            <div class="tile-progressbar">
                                <span data-fill="100%"></span>
                            </div>
                            <div class="tile-footer">
                                <h4>₱{{ number_format($stats['total_inventory_value'], 2) }}</h4>
                                <span>total value</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="tile-progress tile-orange">
                            <div class="tile-header">
                                <h3>Recent Transactions</h3>
                                <span>Last 30 days</span>
                            </div>
                            <div class="tile-progressbar">
                                <span data-fill="100%"></span>
                            </div>
                            <div class="tile-footer">
                                <h4>{{ $stats['recent_transactions'] }}</h4>
                                <span>transactions</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="row mb-3">
                    <div class="col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="searchInput" class="control-label">Search Records</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="entypo-search"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by product name...">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label for="transactionTypeFilter" class="control-label">Transaction Type</label>
                            <select id="transactionTypeFilter" class="form-control">
                                <option value="">All Types</option>
                                <option value="adjustment">Adjustment</option>
                                <option value="purchase">Purchase</option>
                                <option value="sale">Sale</option>
                                <option value="loss">Loss</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label for="dateFilter" class="control-label">Date Range</label>
                            <input type="date" id="dateFilter" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-4">
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label>
                            <button class="btn btn-default btn-block" id="clearFilters" style="height: 34px; line-height: 1.42857143; text-align: center;">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Inventory Records Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Quantity In</th>
                                <th>Quantity Out</th>
                                <th>Current Stock</th>
                                <th>Unit Cost</th>
                                <th>Total Value</th>
                                <th>Reference</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inventoryRecords as $record)
                                <tr>
                                    <td>{{ $record->transaction_date->format('M d, Y') }}</td>
                                    <td>
                                        <strong>{{ $record->product->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $record->product->category }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $record->transaction_type === 'adjustment' ? 'info' : ($record->transaction_type === 'purchase' ? 'success' : ($record->transaction_type === 'sale' ? 'primary' : 'warning')) }}">
                                            {{ ucfirst($record->transaction_type) }}
                                        </span>
                                    </td>
                                    <td class="text-success">{{ $record->quantity_in > 0 ? '+' . $record->quantity_in : '-' }}</td>
                                    <td class="text-danger">{{ $record->quantity_out > 0 ? '-' . $record->quantity_out : '-' }}</td>
                                    <td class="text-primary"><strong>{{ $record->current_stock }}</strong></td>
                                    <td>₱{{ number_format($record->unit_cost, 2) }}</td>
                                    <td class="text-success"><strong>₱{{ number_format($record->total_value, 2) }}</strong></td>
                                    <td>{{ $record->reference_number ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('farmer.inventory.show', $record) }}" class="btn btn-info btn-sm" title="View Details">
                                                <i class="entypo-eye"></i>
                                            </a>
                                            <a href="{{ route('farmer.inventory.edit', $record) }}" class="btn btn-warning btn-sm" title="Edit Record">
                                                <i class="entypo-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" title="Delete Record"
                                                    onclick="if(confirm('Are you sure you want to delete this inventory record?')) { document.getElementById('delete-form-{{ $record->id }}').submit(); }">
                                                <i class="entypo-trash"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $record->id }}" action="{{ route('farmer.inventory.destroy', $record) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i class="entypo-box text-muted mb-3" style="font-size: 3rem;"></i>
                                        <h5 class="text-muted mb-2">No inventory records found</h5>
                                        <p class="text-muted mb-3 small">Start by adding your first inventory record.</p>
                                        <a href="{{ route('farmer.inventory.create') }}" class="btn btn-primary btn-sm">
                                            <i class="entypo-plus"></i> Add First Record
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($inventoryRecords->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Inventory pagination">
                            {{ $inventoryRecords->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>

    /* Table Styling */
    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
    }

    .table td {
        vertical-align: middle;
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

    /* Form Controls */
    .form-control {
        border-radius: 0;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #359ade;
        box-shadow: 0 0 0 0.2rem rgba(53, 154, 222, 0.25);
    }

    .input-group-addon {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-right: none;
    }

    .input-group .form-control {
        border-left: none;
    }

    /* Buttons */
    .btn {
        border-radius: 0;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .btn-group .btn {
        border-radius: 0 !important;
        padding: 5px 8px;
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }

        .btn-group .btn {
            padding: 3px 6px;
            font-size: 11px;
            margin-right: 1px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Search and filter functionality
    function filterRecords() {
        var searchValue = $('#searchInput').val().toLowerCase().trim();
        var transactionType = $('#transactionTypeFilter').val();
        var dateFilter = $('#dateFilter').val();

        $('tbody tr').each(function() {
            var $row = $(this);
            var productName = $row.find('td:eq(1)').text().toLowerCase();
            var type = $row.find('td:eq(2) .badge').text().toLowerCase();
            var date = $row.find('td:eq(0)').text();

            var matchesSearch = !searchValue || productName.includes(searchValue);
            var matchesType = !transactionType || type.includes(transactionType);
            var matchesDate = !dateFilter || date.includes(dateFilter);

            if (matchesSearch && matchesType && matchesDate) {
                $row.show();
            } else {
                $row.hide();
            }
        });
    }

    // Bind filter events
    $('#searchInput').on('input', filterRecords);
    $('#transactionTypeFilter').on('change', filterRecords);
    $('#dateFilter').on('change', filterRecords);

    // Clear filters
    $('#clearFilters').on('click', function() {
        $('#searchInput').val('');
        $('#transactionTypeFilter').val('');
        $('#dateFilter').val('');
        $('tbody tr').show();
    });
});
</script>
@endpush
