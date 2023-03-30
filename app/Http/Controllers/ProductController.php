<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreProductRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::create($request->validated());

        return response()->json(['message' => 'Product created', 'product' => $product]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $product_id)
    {
        $product = Product::find($product_id);

        if (!$product) {
            response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, int $product_id)
    {
        $product = Product::find($product_id);

        if (!$product) {
            response()->json(['message' => 'Product not found'], 404);
        }

        $product->fill($request->validated())->save();

        return response()->json(['message' => 'Product updated', 'product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $product_id)
    {
        $product = Product::find($product_id);

        if (!$product) {
            response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
