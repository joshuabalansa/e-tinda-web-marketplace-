@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Product Management</h2>
            <a href="{{ route('farmer.products.create') }}" class="btn btn-primary">
                <i class="entypo-plus me-2"></i> Add New Product
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <!-- <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="entypo-search"></i>
                            </button> -->
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price/Unit</th>
                                <th>Stock</th>
                                <th>Category</th>
                                <th>Harvest Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>
                                        @if($product->image_url)
                                            <a href="#" class="image-preview" data-toggle="modal" data-target="#imagePreviewModal" data-image="{{ asset('storage/' . $product->image_url) }}" data-name="{{ $product->name }}">
                                                <img src="{{ asset('storage/' . $product->image_url) }}"
                                                     alt="{{ $product->name }}"
                                                     class="rounded"
                                                     style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;">
                                            </a>
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="entypo-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $product->name }}</td>
                                    <td class="align-middle">${{ number_format($product->price_per_unit, 2) }}/{{ $product->unit_type }}</td>
                                    <td class="align-middle">{{ $product->stock_quantity }} {{ $product->unit_type }}</td>
                                    <td class="align-middle">{{ $product->category }}</td>
                                    <td class="align-middle">{{ \Carbon\Carbon::parse($product->harvest_date)->format('M d, Y') }}</td>
                                    <td class="align-middle">
                                        <span class="badge rounded-pill {{ $product->status === 'available' ? 'bg-success' : ($product->status === 'out_of_stock' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('farmer.products.show', $product) }}" class="btn btn-info btn-sm" title="View">
                                                <i class="entypo-eye"></i>
                                            </a>
                                            <a href="{{ route('farmer.products.edit', $product) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="entypo-pencil"></i>
                                            </a>
                                            <form action="{{ route('farmer.products.destroy', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="entypo-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="entypo-database" style="font-size: 2rem;"></i>
                                        <p class="mt-2">No products found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {!! $products->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" class="img-fluid" alt="Product Image">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview functionality
    $('.image-preview').on('click', function(e) {
        e.preventDefault();
        var imageUrl = $(this).data('image');
        var productName = $(this).data('name');

        $('#imagePreviewModal .modal-title').text(productName);
        $('#imagePreviewModal img').attr('src', imageUrl);
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
@endpush
@endsection
