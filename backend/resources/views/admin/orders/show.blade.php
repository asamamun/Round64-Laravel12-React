@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
    <!-- Sidebar -->
    @include('admin.partials.navigation')

    <!-- Main content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Order #{{ $item->order_number }}</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Orders
                    </a>
                    <a href="{{ route('admin.orders.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Edit Order
                    </a>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Order Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->items as $orderItem)
                                        <tr>
                                            <td>{{ $orderItem->name }}</td>
                                    <td>${{ number_format($orderItem->price, 2) }}</td>
                                    <td>{{ $orderItem->quantity }}</td>
                                    <td>${{ number_format($orderItem->price * $orderItem->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ 
                                $item->status === 'pending' ? 'warning' : 
                                $item->status === 'processing' ? 'info' : 
                                $item->status === 'shipped' ? 'primary' : 
                                $item->status === 'delivered' ? 'success' : 'danger' 
                            }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </p>
                        <p><strong>Payment Status:</strong> 
                            <span class="badge bg-{{ 
                                $item->payment_status === 'pending' ? 'warning' : 
                                $item->payment_status === 'paid' ? 'success' : 
                                $item->payment_status === 'failed' ? 'danger' : 'secondary' 
                            }}">
                                {{ ucfirst($item->payment_status) }}
                            </span>
                        </p>
                        <p><strong>Order Date:</strong> {{ $item->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Subtotal:</strong> ${{ number_format($item->subtotal, 2) }}</p>
                        <p><strong>Shipping:</strong> ${{ number_format($item->shipping, 2) }}</p>
                        <p><strong>Tax:</strong> ${{ number_format($item->tax, 2) }}</p>
                        <p><strong>Discount:</strong> ${{ number_format($item->discount, 2) }}</p>
                        <p><strong>Total:</strong> ${{ number_format($item->total, 2) }}</p>
                        @if($item->tracking_number)
                            <p><strong>Tracking Number:</strong> {{ $item->tracking_number }}</p>
                        @endif
                        @if($item->notes)
                            <p><strong>Notes:</strong> {{ $item->notes }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5>Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        @if($item->shippingAddress)
                            <p><strong>Name:</strong> {{ $item->shippingAddress->name }}</p>
                            <p><strong>Address:</strong> {{ $item->shippingAddress->address }}</p>
                            <p><strong>City:</strong> {{ $item->shippingAddress->city }}</p>
                            <p><strong>State:</strong> {{ $item->shippingAddress->state }}</p>
                            <p><strong>Zip Code:</strong> {{ $item->shippingAddress->zip_code }}</p>
                            <p><strong>Country:</strong> {{ $item->shippingAddress->country }}</p>
                        @else
                            <p>No shipping address provided.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection