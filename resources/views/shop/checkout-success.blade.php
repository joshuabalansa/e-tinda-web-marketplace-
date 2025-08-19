@extends('layouts.shop')
@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
        <h1>{{ __('shop.order_placed_successfully') }}</h1>
        <p class="lead">{{ __('shop.thank_you_order_number', ['id' => $order->id]) }}</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">{{ __('shop.order_details') }}</h5>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>{{ __('shop.shipping_information') }}</h6>
                            <p class="mb-1">{{ $order->shipping_address }}</p>
                            <p class="mb-1">{{ __('shop.contact') }}: {{ $order->contact_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __('shop.payment_information') }}</h6>
                            <p class="mb-1">{{ __('shop.payment_method') }}: {{ strtoupper($order->payment_method) }}</p>
                            <p class="mb-1">{{ __('shop.order_status') }}: {{ ucfirst($order->status) }}</p>
                        </div>
                    </div>

                    <h6 class="mb-3">{{ __('shop.order_items') }}</h6>
                    @foreach($order->items as $item)
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                <p class="text-muted mb-0">
                                    {{ $item->quantity }} {{ $item->product->unit_type }} x ₱{{ number_format($item->price, 2) }}
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <p class="mb-0">₱{{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>{{ __('shop.total_amount') }}</strong>
                        <strong>₱{{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('shop.index') }}" class="btn btn-primary">
                    {{ __('shop.continue_shopping') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection