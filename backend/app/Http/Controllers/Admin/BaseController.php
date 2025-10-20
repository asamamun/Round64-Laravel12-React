<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseController extends Controller
{
    protected $model;
    protected $viewPath;
    protected $routePrefix;
    protected $paginationCount = 10;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->model::query();
        
        // Apply search if provided
        if ($request->has('search')) {
            $search = $request->get('search');
            $query = $this->applySearch($query, $search);
        }
        
        // Apply filters if provided
        $query = $this->applyFilters($query, $request);
        
        // Apply sorting if provided
        if ($request->has('sort')) {
            $sort = $request->get('sort');
            $direction = $request->get('direction', 'asc');
            $query = $query->orderBy($sort, $direction);
        }
        
        $items = $query->paginate($this->paginationCount)->appends($request->except('page'));
        
        return view("{$this->viewPath}.index", compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("{$this->viewPath}.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateStore($request);
        
        $item = $this->model::create($validatedData);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item created successfully!',
                'data' => $item
            ]);
        }
        
        return redirect()->route("{$this->routePrefix}.index")->with('success', 'Item created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = $this->model::findOrFail($id);
        return view("{$this->viewPath}.show", compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = $this->model::findOrFail($id);
        return view("{$this->viewPath}.edit", compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $item = $this->model::findOrFail($id);
        
        $validatedData = $this->validateUpdate($request, $item);
        
        $item->update($validatedData);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully!',
                'data' => $item
            ]);
        }
        
        return redirect()->route("{$this->routePrefix}.index")->with('success', 'Item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $item = $this->model::findOrFail($id);
        $item->delete();
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully!'
            ]);
        }
        
        return redirect()->route("{$this->routePrefix}.index")->with('success', 'Item deleted successfully!');
    }

    /**
     * Apply search to the query
     */
    protected function applySearch($query, $search)
    {
        // Override in child classes to implement search functionality
        return $query;
    }

    /**
     * Apply filters to the query
     */
    protected function applyFilters($query, $request)
    {
        // Override in child classes to implement filter functionality
        return $query;
    }

    /**
     * Validate store request
     */
    protected function validateStore(Request $request)
    {
        // Override in child classes to implement validation
        return $request->all();
    }

    /**
     * Validate update request
     */
    protected function validateUpdate(Request $request, $item)
    {
        // Override in child classes to implement validation
        return $request->all();
    }
}