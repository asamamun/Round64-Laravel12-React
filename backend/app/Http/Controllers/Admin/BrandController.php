<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends BaseController
{
    public function __construct()
    {
        $this->model = Brand::class;
        $this->viewPath = 'admin.brands';
        $this->routePrefix = 'admin.brands';
    }

    /**
     * Apply search to the query
     */
    protected function applySearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
    }

    /**
     * Validate store request
     */
    protected function validateStore(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'slug' => 'nullable|string|max:255|unique:brands',
            'description' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'published' => 'nullable|boolean',
        ]);
    }

    /**
     * Validate update request
     */
    protected function validateUpdate(Request $request, $item)
    {
        return $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $item->id,
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $item->id,
            'description' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'published' => 'nullable|boolean',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateStore($request);
        
        // Generate slug if not provided
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }
        
        // Set default values
        $validatedData['featured'] = $validatedData['featured'] ?? false;
        $validatedData['published'] = $validatedData['published'] ?? false;
        
        $item = $this->model::create($validatedData);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand created successfully!',
                'data' => $item
            ]);
        }
        
        return redirect()->route("{$this->routePrefix}.index")->with('success', 'Brand created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        
        $validatedData = $this->validateUpdate($request, $item);
        
        // Generate slug if not provided or if name changed
        if (empty($validatedData['slug']) || $validatedData['name'] !== $item->name) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }
        
        $item->update($validatedData);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand updated successfully!',
                'data' => $item
            ]);
        }
        
        return redirect()->route("{$this->routePrefix}.index")->with('success', 'Brand updated successfully!');
    }
}