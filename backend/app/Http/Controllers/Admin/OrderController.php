<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    public function __construct()
    {
        $this->model = Order::class;
        $this->viewPath = 'admin.orders';
        $this->routePrefix = 'admin.orders';
    }

    /**
     * Apply search to the query
     */
    protected function applySearch($query, $search)
    {
        return $query->where('order_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('email', 'LIKE', "%{$search}%");
                    });
    }

    /**
     * Apply filters to the query
     */
    protected function applyFilters($query, $request)
    {
        if ($request->has('status') && $request->get('status') !== '') {
            $query = $query->where('status', $request->get('status'));
        }
        
        if ($request->has('payment_status') && $request->get('payment_status') !== '') {
            $query = $query->where('payment_status', $request->get('payment_status'));
        }
        
        return $query;
    }

    /**
     * Validate update request
     */
    protected function validateUpdate(Request $request, $item)
    {
        return $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|string|in:pending,paid,failed,refunded',
            'tracking_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
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
                'message' => 'Order updated successfully!',
                'data' => $item
            ]);
        }
        
        return redirect()->route("{$this->routePrefix}.index")->with('success', 'Order updated successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $item = $this->model::with(['user', 'shippingAddress', 'items.product'])->findOrFail($id);
        return view("{$this->viewPath}.show", compact('item'));
    }
}