<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    
    public function show(string $slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($product);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product = Product::create($request->only([
            'name',
            'description',
            'price',
            'stock',
            'category_id',
        ]));

        return response()->json([
            'message' => 'Product created',
            'product' => $product,
        ], 201);
    }

    
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'sometimes|numeric|min:0',
            'stock'       => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $product->update($request->only([
            'name',
            'description',
            'price',
            'stock',
            'category_id',
        ]));

        return response()->json([
            'message' => 'Product updated',
            'product' => $product,
        ]);
    }

    
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted',
        ]);
    }
}