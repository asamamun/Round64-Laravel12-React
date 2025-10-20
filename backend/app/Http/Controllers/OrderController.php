<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // If user is admin, show all orders
        if ($user->role === 'admin') {
            $orders = Order::with(['items.product.images', 'items.product.category', 'items.product.brand', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // For customers, show only their orders
            $orders = Order::where('user_id', $user->id)
                ->with(['items.product.images', 'items.product.category', 'items.product.brand'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
            
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'shipping_address' => 'required|array',
            'shipping_address.first_name' => 'required|string|max:255',
            'shipping_address.last_name' => 'required|string|max:255',
            'shipping_address.address' => 'required|string|max:255',
            'shipping_address.city' => 'required|string|max:255',
            'shipping_address.state' => 'required|string|max:255',
            'shipping_address.zip' => 'required|string|max:20',
        ]);

        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            
            // Generate order number
            $orderNumber = 'ORD-' . strtoupper(uniqid());
            
            // Create the shipping address
            $address = Address::create([
                'user_id' => $user->id,
                'name' => $request->shipping_address['first_name'] . ' ' . $request->shipping_address['last_name'],
                'address' => $request->shipping_address['address'],
                'city' => $request->shipping_address['city'],
                'state' => $request->shipping_address['state'],
                'zip_code' => $request->shipping_address['zip'],
                'country' => 'USA', // Default country, you might want to add this to the form
                'phone' => '', // Default empty phone, you might want to add this to the form
            ]);
            
            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'subtotal' => $request->total_amount,
                'total' => $request->total_amount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'credit_card',
                'shipping_address_id' => $address->id,
            ]);
            
            // Create order items
            foreach ($request->items as $item) {
                // Get product name for the order item
                $product = \App\Models\Product::find($item['product_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'name' => $product->name,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);
            }
            
            // Clear the user's cart
            Cart::where('user_id', $user->id)->delete();
            
            // Load the order with items and address
            $order->load(['items.product.images', 'items.product.category', 'items.product.brand', 'shippingAddress']);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'data' => $order
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $user = Auth::user();
        
        // If user is admin, allow viewing any order
        if ($user->role !== 'admin') {
            // Ensure user can only view their own orders
            if ($order->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
        }
        
        $order->load(['items.product.images', 'items.product.category', 'items.product.brand', 'shippingAddress', 'user']);
        
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $user = Auth::user();
        
        // Only admins can update order status
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admins can update order status.'
            ], 403);
        }
        
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|string|in:pending,paid,failed,refunded'
        ]);
        
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status
        ]);
        
        // Load the updated order with relationships
        $order->load(['items.product.images', 'items.product.category', 'items.product.brand', 'shippingAddress', 'user']);
        
        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully!',
            'data' => $order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}