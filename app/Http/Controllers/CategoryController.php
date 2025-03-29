<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;



class CategoryController extends Controller
{
   
    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
    
        // Generate a unique cache key based on the search term
        $cacheKey = $searchTerm ? "categories_search_{$searchTerm}" : "categories_list";
    
        // Cache the results for 10 minutes
        $categories = Cache::remember($cacheKey, 600, function () use ($searchTerm) {
            if ($searchTerm) {
                return Category::where('name', 'like', '%' . $searchTerm . '%')->get();
            } else {
                return Category::all();
            }
        });
    
        return response()->json($categories);
    }
    
    

    public function edit($id)
{
    $cacheKey = "category_{$id}";

    $category = Cache::remember($cacheKey, 1800, function () use ($id) {
        return Category::findOrFail($id);
    });

    return view('categories.editcategory', compact('category'));
}


public function update(Request $request, $id)
{
    // Create a validator instance
    $validator = Validator::make($request->all(), [
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('categories')->ignore($id),
        ],
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $category = Category::findOrFail($id);
    $category->update($request->all());

    // Clear cache for updated category and category list
    Cache::forget("category_{$id}");
    Cache::forget("categories_list");

    return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
}


  
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|unique:categories,name|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $category = new Category();
    $category->name = $request->input('name');
    $category->save();

    // Clear the category list cache
    Cache::forget("categories_list");

    return response()->json(['message' => 'Category added successfully', 'category' => $category]);
}


public function destroy($id)
{
    $category = Category::findOrFail($id);
    $category->delete();

    // Clear cache for deleted category and category list
    Cache::forget("category_{$id}");
    Cache::forget("categories_list");

    return response()->json(['success' => true, 'redirect_url' => route('categories')]);
}


}
