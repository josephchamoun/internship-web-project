<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;


class ItemController extends Controller
{



   

    public function index(Request $request)
{
    $searchTerm = $request->query('search');
    $age = $request->query('age');
    $gender = $request->query('gender');
    $category = $request->query('category');

    $query = Item::query();

    if ($searchTerm) {
        $query->where('name', 'like', '%' . $searchTerm . '%');
    }

    if ($age) {
        $query->where('age', $age);
    }

    if ($gender) {
        $query->where('gender', $gender);
    }

    if ($category) {
        $query->where('category_id', $category);
    }

    $items = $query->simplePaginate(20);

    // Convert each item to include full image URL
    $items->getCollection()->transform(function ($item) {
        $item->image_url = $item->image_url ? asset("storage/{$item->image_url}") : null;
        return $item;
    });

    return response()->json($items);
}

    public function index2(Request $request)
    {
        $items = Item::all();
        return response()->json($items);
    }

    public function store(Request $request)
{
    // Validate the request manually to return JSON errors instead of redirecting
    $validator = Validator::make($request->all(), [
        'name' => 'required|unique:items,name|string',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0|max:999999.99',
        'category' => 'required|integer',
        'gender' => 'required|string',
        'age' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422); // 422 Unprocessable Entity for validation errors
    }

    // Store image if exists
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('uploads/items', 'public');
    }

    // Create item
    $item = Item::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'category_id' => $request->category,
        'gender' => $request->gender,
        'quantity'=> 0,
        'age' => $request->age,
        'image_url' => $imagePath,
    ]);

    return response()->json([
        'message' => 'Item added successfully',
        'item' => $item,
    ]);
}


    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        return view('items.edititem', compact('item', 'categories'));
    }

    
   
public function update(Request $request, $id)
{
    // Update validation rules to include gender, category_id, and age
    $validator = Validator::make($request->all(), [
        'name' => 'required|unique:items,name,' . $id . '|string',
        'description' => 'required|string|max:255',
        'price' => 'required|numeric|min:0|max:999999.99',
        'quantity' => 'required|numeric|min:0|max:999999.99',
        'gender' => 'required|in:both,male,female',
        'category_id' => 'required|exists:categories,id',
        'age' => 'required|in:0-3,3-6,6-9,9-12,13-17,18+',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422); 
    }

    // Find the item and update it with validated data
    $item = Item::findOrFail($id);
    $item->update($request->only(['name', 'description', 'price', 'quantity', 'gender', 'category_id', 'age']));

    return response()->json(['message' => 'Item updated successfully', 'item' => $item], 200);
}
    
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
    
        return response()->json(['success' => true, 'redirect_url' => route('dashboard')]);
    }
    


    


}
