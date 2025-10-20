<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
    public function __construct()
    {
        $this->model = Product::class;
        $this->viewPath = 'admin.products';
        $this->routePrefix = 'admin.products';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view("{$this->viewPath}.create", compact('categories', 'brands'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        return view("{$this->viewPath}.edit", compact('item', 'categories', 'brands'));
    }

    /**
     * Apply search to the query
     */
    protected function applySearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
    }

    /**
     * Apply filters to the query
     */
    protected function applyFilters($query, $request)
    {
        if ($request->has('category_id') && $request->get('category_id')) {
            $query = $query->where('category_id', $request->get('category_id'));
        }
        
        if ($request->has('brand_id') && $request->get('brand_id')) {
            $query = $query->where('brand_id', $request->get('brand_id'));
        }
        
        if ($request->has('status') && $request->get('status') !== '') {
            $query = $query->where('published', $request->get('status'));
        }
        
        return $query;
    }

    /**
     * Validate store request
     */
    protected function validateStore(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'featured' => 'nullable|boolean',
            'published' => 'nullable|boolean',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);
    }

    /**
     * Validate update request
     */
    protected function validateUpdate(Request $request, $item)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $item->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'featured' => 'nullable|boolean',
            'published' => 'nullable|boolean',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateStore($request);
        
        // Set default values
        $validatedData['slug'] = Str::slug($validatedData['name']);
        $validatedData['featured'] = $validatedData['featured'] ?? false;
        $validatedData['published'] = $validatedData['published'] ?? false;
        
        $item = $this->model::create($validatedData);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'data' => $item
            ]);
        }
        
        return redirect()->route("{$this->routePrefix}.index")->with('success', 'Product created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        
        $validatedData = $this->validateUpdate($request, $item);
        
        // Update slug if name changed
        if ($validatedData['name'] !== $item->name) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }
        
        $item->update($validatedData);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'data' => $item
            ]);
        }
        
        return redirect()->route("{$this->routePrefix}.index")->with('success', 'Product updated successfully!');
    }
}