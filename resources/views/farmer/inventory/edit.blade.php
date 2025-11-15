@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4><i class="entypo-pencil"></i> Edit Inventory Record</h4>
                </div>
            </div>
            <div class="panel-body">
                <form action="{{ route('farmer.inventory.update', $inventory) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_id" class="control-label">Product <span class="text-danger">*</span></label>
                                <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                    <option value="">Select a product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id', $inventory->product_id) == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} ({{ $product->category }}) - Current Stock: {{ $product->stock_quantity }} {{ $product->unit_type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transaction_type" class="control-label">Transaction Type <span class="text-danger">*</span></label>
                                <select name="transaction_type" id="transaction_type" class="form-control @error('transaction_type') is-invalid @enderror" required>
                                    <option value="">Select transaction type</option>
                                    <option value="adjustment" {{ old('transaction_type', $inventory->transaction_type) == 'adjustment' ? 'selected' : '' }}>Stock Adjustment</option>
                                    <option value="purchase" {{ old('transaction_type', $inventory->transaction_type) == 'purchase' ? 'selected' : '' }}>Purchase</option>
                                    <option value="sale" {{ old('transaction_type', $inventory->transaction_type) == 'sale' ? 'selected' : '' }}>Sale</option>
                                    <option value="loss" {{ old('transaction_type', $inventory->transaction_type) == 'loss' ? 'selected' : '' }}>Loss/Damage</option>
                                </select>
                                @error('transaction_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity_in" class="control-label">Quantity In</label>
                                <input type="number" name="quantity_in" id="quantity_in" class="form-control @error('quantity_in') is-invalid @enderror"
                                       value="{{ old('quantity_in', $inventory->quantity_in) }}" min="0" step="1">
                                @error('quantity_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter quantity added to inventory</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity_out" class="control-label">Quantity Out</label>
                                <input type="number" name="quantity_out" id="quantity_out" class="form-control @error('quantity_out') is-invalid @enderror"
                                       value="{{ old('quantity_out', $inventory->quantity_out) }}" min="0" step="1">
                                @error('quantity_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter quantity removed from inventory</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unit_cost" class="control-label">Unit Cost (₱)</label>
                                <input type="number" name="unit_cost" id="unit_cost" class="form-control @error('unit_cost') is-invalid @enderror"
                                       value="{{ old('unit_cost', $inventory->unit_cost) }}" min="0" step="0.01">
                                @error('unit_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Cost per unit for this transaction</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transaction_date" class="control-label">Transaction Date <span class="text-danger">*</span></label>
                                <input type="date" name="transaction_date" id="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror"
                                       value="{{ old('transaction_date', $inventory->transaction_date->format('Y-m-d')) }}" required>
                                @error('transaction_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reference_number" class="control-label">Reference Number</label>
                                <input type="text" name="reference_number" id="reference_number" class="form-control @error('reference_number') is-invalid @enderror"
                                       value="{{ old('reference_number', $inventory->reference_number) }}" placeholder="e.g., PO-001, INV-123">
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Optional reference for tracking</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="control-label">Notes</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
                                          rows="3" placeholder="Additional notes about this transaction...">{{ old('notes', $inventory->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Current Stock Display -->
                    <div class="row" id="currentStockInfo" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5><i class="entypo-info"></i> Current Stock Information</h5>
                                <p class="mb-1"><strong>Product:</strong> <span id="selectedProductName"></span></p>
                                <p class="mb-1"><strong>Current Stock:</strong> <span id="currentStockQuantity"></span> <span id="currentStockUnit"></span></p>
                                <p class="mb-0"><strong>New Stock After Transaction:</strong> <span id="newStockQuantity" class="text-primary"></span> <span id="newStockUnit"></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="entypo-check"></i> Update Record
                            </button>
                            <a href="{{ route('farmer.inventory.index') }}" class="btn btn-default">
                                <i class="entypo-cancel"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-control {
        border-radius: 0;
        border: 1px solid #ddd;
        font-size: 14px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #359ade;
        box-shadow: 0 0 0 0.2rem rgba(53, 154, 222, 0.25);
        outline: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
        font-size: 13px;
    }

    .control-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .btn {
        border-radius: 0;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #359ade;
        border-color: #359ade;
        color: white;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
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

    .text-danger {
        color: #dc3545 !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    .text-primary {
        color: #007bff !important;
    }

    .alert {
        border-radius: 0;
        border: 1px solid transparent;
    }

    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize with current product data
    updateProductInfo();

    // Product selection change handler
    $('#product_id').on('change', function() {
        updateProductInfo();
    });

    // Quantity change handlers
    $('#quantity_in, #quantity_out').on('input', updateNewStock);

    function updateProductInfo() {
        var productId = $('#product_id').val();
        if (productId) {
            // Get product details from the option text
            var optionText = $('#product_id').find('option:selected').text();
            var parts = optionText.split(' - Current Stock: ');
            var productName = parts[0];
            var stockInfo = parts[1];

            if (stockInfo) {
                var stockParts = stockInfo.split(' ');
                var currentStock = stockParts[0];
                var unit = stockParts[1];

                $('#selectedProductName').text(productName);
                $('#currentStockQuantity').text(currentStock);
                $('#currentStockUnit').text(unit);
                $('#newStockUnit').text(unit);

                $('#currentStockInfo').show();
                updateNewStock();
            }
        } else {
            $('#currentStockInfo').hide();
        }
    }

    function updateNewStock() {
        var currentStock = parseInt($('#currentStockQuantity').text()) || 0;
        var quantityIn = parseInt($('#quantity_in').val()) || 0;
        var quantityOut = parseInt($('#quantity_out').val()) || 0;

        var newStock = currentStock + quantityIn - quantityOut;
        $('#newStockQuantity').text(newStock);

        // Color coding for stock levels
        if (newStock < 0) {
            $('#newStockQuantity').removeClass('text-primary text-warning text-success').addClass('text-danger');
        } else if (newStock <= 10) {
            $('#newStockQuantity').removeClass('text-primary text-danger text-success').addClass('text-warning');
        } else {
            $('#newStockQuantity').removeClass('text-warning text-danger text-success').addClass('text-primary');
        }
    }

    // Auto-calculate total value
    $('#quantity_in, #unit_cost').on('input', function() {
        var quantityIn = parseInt($('#quantity_in').val()) || 0;
        var unitCost = parseFloat($('#unit_cost').val()) || 0;
        var totalValue = quantityIn * unitCost;

        // You can display this somewhere if needed
        console.log('Total Value: ₱' + totalValue.toFixed(2));
    });

    // Form validation
    $('form').on('submit', function(e) {
        var quantityIn = parseInt($('#quantity_in').val()) || 0;
        var quantityOut = parseInt($('#quantity_out').val()) || 0;

        if (quantityIn === 0 && quantityOut === 0) {
            e.preventDefault();
            alert('Please enter at least one quantity (in or out).');
            return false;
        }

        var currentStock = parseInt($('#currentStockQuantity').text()) || 0;
        var newStock = currentStock + quantityIn - quantityOut;

        if (newStock < 0) {
            e.preventDefault();
            alert('This transaction would result in negative stock. Please adjust the quantities.');
            return false;
        }
    });
});
</script>
@endpush
