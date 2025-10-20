@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <!-- Sidebar -->
    @include('admin.partials.navigation')

    <!-- Main content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Edit Product</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Products
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Product Form -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.products.update', $item->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $item->name) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sku" class="form-label">SKU</label>
                                        <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $item->sku) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $item->description) }}</textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Price ($)</label>
                                                <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ old('price', $item->price) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="compare_price" class="form-label">Compare Price ($)</label>
                                                <input type="number" class="form-control" id="compare_price" name="compare_price" step="0.01" value="{{ old('compare_price', $item->compare_price) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="cost_price" class="form-label">Cost Price ($)</label>
                                                <input type="number" class="form-control" id="cost_price" name="cost_price" step="0.01" value="{{ old('cost_price', $item->cost_price) }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="quantity" class="form-label">Quantity</label>
                                                <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', $item->quantity) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">Category</label>
                                                <select class="form-select" id="category_id" name="category_id" required>
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="brand_id" class="form-label">Brand</label>
                                                <select class="form-select" id="brand_id" name="brand_id" required>
                                                    <option value="">Select Brand</option>
                                                    @foreach($brands as $brand)
                                                        <option value="{{ $brand->id }}" {{ old('brand_id', $item->brand_id) == $brand->id ? 'selected' : '' }}>
                                                            {{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="featured" name="featured" {{ old('featured', $item->featured) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="featured">Featured Product</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" id="published" name="published" {{ old('published', $item->published) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="published">Published</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>SEO Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="seo_title" class="form-label">SEO Title</label>
                                                <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ old('seo_title', $item->seo_title) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="seo_description" class="form-label">SEO Description</label>
                                                <textarea class="form-control" id="seo_description" name="seo_description" rows="3">{{ old('seo_description', $item->seo_description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h5>Shipping Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="weight" class="form-label">Weight (kg)</label>
                                                <input type="number" class="form-control" id="weight" name="weight" step="0.01" value="{{ old('weight', $item->weight) }}">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="length" class="form-label">Length (cm)</label>
                                                        <input type="number" class="form-control" id="length" name="length" step="0.01" value="{{ old('length', $item->length) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="width" class="form-label">Width (cm)</label>
                                                        <input type="number" class="form-control" id="width" name="width" step="0.01" value="{{ old('width', $item->width) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label for="height" class="form-label">Height (cm)</label>
                                                        <input type="number" class="form-control" id="height" name="height" step="0.01" value="{{ old('height', $item->height) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection