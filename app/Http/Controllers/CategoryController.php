<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();
        if($category->isEmpty())
            return response()->json(['message' => 'Category not found'], 404);
        return response()->json($category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories',
            'description' => 'required|min:10'
        ]);

        $category = Category::create($request->all());

        return response()->json(['message' => 'Category created successfully', $category], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if($category == null)
            return response()->json(['message' => 'Category not found'], 404);
        return response()->json($category);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        if($category == null)
            return response()->json(['message' => 'Category not found'], 404);
        $request->validate([
            'name' => ['required', 'string', Rule::unique('categories')->ignore($category->id)],
            'description' => 'required|min:10'
        ]);
        $category->update($request->all());
        return response()->json(['message' => 'Category updated successfully', $category]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if($category == null)
            return response()->json(['message' => 'Category not found'], 404);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
