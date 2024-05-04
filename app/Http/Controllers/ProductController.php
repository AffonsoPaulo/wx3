<?php

namespace App\Http\Controllers;

use App\Models\Product;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $product = Product::all();
        if ($product->isEmpty())
            return response()->json(['error' => 'Product not found'], 404);
        return response()->json($product);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|unique:products,name',
            'color' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg',
            'price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'description' => 'required|string|max:255',
            'weight' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $image = $request->file('image')->store('images', 'public');

        $imageUrl = Storage::url($image);

        $product = Product::create(array_merge($request->except('image'), ['image' => $imageUrl]));
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $product = Product::find($id);
        if ($product == null)
            return response()->json(['error' => 'Product not found'], 404);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $product = Product::find($id);
        if ($product == null)
            return response()->json(['error' => 'Product not found'], 404);

        $request->validate([
            'name' => 'required|string|unique:products,name',
            'color' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg',
            'price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'description' => 'required|string|max:255',
            'weight' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        Storage::delete($product->image);
        $image = $request->file('image')->store('images', 'public');
        $imageUrl = Storage::url($image);

        $product->update(array_merge($request->except('image'), ['image' => $imageUrl]));
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $product = Product::find($id);
        if ($product == null)
            return response()->json(['error' => 'Product not found'], 404);
        $image = explode('/', $product->image);
        Storage::disk('public')->delete($image[2] . '/' . $image[3]);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
