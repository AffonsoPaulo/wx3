<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variation;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $product = Product::with('variation')->get();
        if ($product->isEmpty())
            return response()->json(['message' => 'Product not found'], 404);
        return response()->json($product);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request['variation'] = json_decode($request['variation'], true);
        $request->validate([
            'name' => 'required|string|unique:products,name',
            'color' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg',
            'price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'description' => 'required|string|max:255',
            'weight' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'variation' => 'required|array|min:1',
            'variation.*.size' => 'required|string',
            'variation.*.quantity' => 'required|numeric|min:0',
        ]);

        $variations = array_map(function($variation) {
            return new Variation($variation);
        }, $request['variation']);

        $image = $request->file('image')->store('images', 'public');

        $imageUrl = Storage::url($image);

        $product = Product::create(array_merge($request->except('image'), ['image' => $imageUrl]));
        $product->save();
        $product->variation()->saveMany($variations);

        return response()->json(['message' => 'Product created successfully', $product], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $product = Product::find($id);
        if ($product == null)
            return response()->json(['message' => 'Product not found'], 404);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $product = Product::find($id);
        if ($product == null)
            return response()->json(['message' => 'Product not found'], 404);

        $request->validate([
            'name' => ['required', 'string', Rule::unique('products')->ignore($product->id)],
            'color' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg',
            'price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:100',
            'description' => 'required|string|max:255',
            'weight' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'variation' => 'required|array|min:1',
            'variation.*.id' => 'required|exists:variations,id',
            'variation.*.size' => 'required|string',
            'variation.*.quantity' => 'required|numeric|min:0',
        ]);

        foreach($request['variation'] as $variation) {
            $product->variation()->where('id', $variation['id'])->update($variation);
        }

        $image = explode('/', $product->image);
        Storage::disk('public')->delete($image[2] . '/' . $image[3]);
        $image = $request->file('image')->store('images', 'public');
        $imageUrl = Storage::url($image);

        $product->update(array_merge($request->except('image'), ['image' => $imageUrl]));
        return response()->json(['message' => 'Product updated successfully', $product]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $product = Product::find($id);
        if ($product == null)
            return response()->json(['message' => 'Product not found'], 404);
        $image = explode('/', $product->image);
        Storage::disk('public')->delete($image[2] . '/' . $image[3]);
        $product->variation()->delete();
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
