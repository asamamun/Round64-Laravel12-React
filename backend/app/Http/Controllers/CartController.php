<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get the user's cart
     */
    public function getCart(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        // Get or create cart for user
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id]
        );

        // Load cart items with product details
        $cart->load(['items.product.images', 'items.product.category', 'items.product.brand']);

        return response()->json([
            'success' => true,
            'data' => $cart
        ]);
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1'
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        // Check if product exists and is active
        $product = Product::where('id', $productId)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found or inactive'
            ], 404);
        }

        // Check stock availability
        if ($product->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock'
            ], 400);
        }

        // Get or create cart
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id]
        );

        // Check if item already exists in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $quantity;
            
            if ($product->stock < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock for requested quantity'
                ], 400);
            }
            
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->images->first()->image_url ?? null,
            ]);
        }

        // Load updated cart
        $cart->load(['items.product.images', 'items.product.category', 'items.product.brand']);

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'data' => $cart
        ], 201);
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $cartItem = CartItem::find($request->cart_item_id);

        // Verify the cart item belongs to the user
        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($request->quantity == 0) {
            // Remove item if quantity is 0
            $cartItem->delete();
            $message = 'Item removed from cart';
        } else {
            // Check stock availability
            if ($cartItem->product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock'
                ], 400);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();
            $message = 'Cart updated';
        }

        // Load updated cart
        $cart = Cart::where('user_id', $user->id)
            ->with(['items.product.images', 'items.product.category', 'items.product.brand'])
            ->first();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $cart
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id'
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $cartItem = CartItem::find($request->cart_item_id);

        // Verify the cart item belongs to the user
        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $cartItem->delete();

        // Load updated cart
        $cart = Cart::where('user_id', $user->id)
            ->with(['items.product.images', 'items.product.category', 'items.product.brand'])
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'data' => $cart
        ]);
    }

    /**
     * Clear all items from cart
     */
    public function clearCart(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared'
        ]);
    }
}
