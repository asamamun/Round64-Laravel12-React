<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);
        
        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by brand if provided
        if ($request->has('brand')) {
            $query->where('brand_id', $request->brand);
        }
        
        // Search by name or description if provided
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        // Sort products
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        
        switch ($sort) {
            case 'price':
                $query->orderBy('price', $order);
                break;
            case 'name':
                $query->orderBy('name', $order);
                break;
            case 'rating':
                $query->orderBy('rating', $order);
                break;
            default:
                $query->orderBy('created_at', $order);
                break;
        }
        
        $products = $query->with(['category', 'brand', 'images'])
            ->paginate(12); // Paginate with 12 products per page
            
        return response()->json([
            'success' => true,
            'data' => $products
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        $product->load([
            'category', 
            'brand', 
            'variants' => function ($query) {
                $query->where('is_active', true);
            }, 
            'images', 
            'specifications', 
            'tags', 
            'reviews' => function ($query) {
                $query->where('is_approved', true)->with('user');
            }
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}