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
        $request = $request->validated();

        $sort_by = $request['sort_by'] ?? 'id';
        $sort_dir = $request['sort_dir'] ?? 'asc';

        $products = Product::when(isset($request['name']), function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request['name'] . '%');
            })
            ->orderBy($sort_by, $sort_dir)
            ->get();

        return response()->json(ProductResource::collection($products), $products->isEmpty() ? 204 : 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return response()->json(['message' => 'Product created', 'product' => new ProductResource($product)], 201);
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

        return response()->json(new ProductResource($product));
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

        return response()->json(['message' => 'Product updated', 'product' => new ProductResource($product)]);
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
