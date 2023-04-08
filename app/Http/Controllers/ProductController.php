<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetProductRequest $request)
    {
        $products = Product::query();

        $request = $request->validated();

        if (Arr::exists($request, 'name')) {
            $products->where('name', 'LIKE', '%' . $request['name'] . '%');
        }

        if (Arr::exists($request, 'sort_by')) {
            $sort_by = $request['sort_by'];
            $sort_dir = $request['sort_dir'] ?? 'asc';
            $products->orderBy($sort_by, $sort_dir);
        }

        $products = ProductResource::collection($products->get());

        return response()->json($products, $products->isEmpty() ? 204 : 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return response()->json(['message' => 'Product created', 'product' => $product], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $productId)
    {
        try {
            $product = Product::findOrFail($productId);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, int $productId)
    {
        try {
            $product = Product::find($productId);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->fill($request->validated())->save();

        return response()->json(['message' => 'Product updated', 'product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $productId)
    {
        try {
            $product = Product::findOrFail($productId);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
