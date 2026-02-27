<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)  // GET/api/products -> list products
    {
        $query = Product::query();

        // Search by name or SKU
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->query('sort', 'name');
        $sortDir   = $request->query('direction', 'asc');

        $allowed = ['name', 'sku', 'price', 'stock', 'created_at'];
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'desc' ? 'desc' : 'asc');
        }

        // Paginate
        $perPage = min((int) $request->query('per_page', 10), 50);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    public function store(Request $request)  // POST/api/products -> create product
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'sku'   => 'required|string|unique:products',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product created.',
            'product' => $product,
        ], 201);
    }

    public function show(Product $product)  // GET/api/products -> get one product
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product)  // PUT/api/products/{id} -> edit a product
    {
        $validated = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'sku'   => 'sometimes|string|unique:products,sku,' . $product->id,
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated.',
            'product' => $product,
        ]);
    }

    public function destroy(Product $product)  // DELETE/api/products/{id} -> delete a product
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted.',
        ]);
    }
}