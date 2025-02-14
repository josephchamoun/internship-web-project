<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

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
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|integer',
            'gender' => 'required|string',
            'age' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,bmp|max:2048', // Validate image
        ]);
    
        // Initialize the image path variable
        $imagePath = null;
    
        // Store the image if it exists
        if ($request->hasFile('image')) {
            // Get the uploaded image file
            $image = $request->file('image');
            
            // Store the image in 'public/uploads/items' folder
            $imagePath = $image->store('uploads/items', 'public');
        }
    
        // Create a new item record with the form data
        $item = Item::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'category_id' => $validated['category'],
            'gender' => $validated['gender'],
            'age' => $validated['age'],
            'image_url' => $imagePath,  // Save the image path in the database
        ]);
    
        return response()->json([
            'message' => 'Item added successfully',
            'item' => $item, // Return the item information as a response
        ]);
    }
    

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('items.edititem', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        $item = Item::findOrFail($id);
        $item->update($request->all());

        return response()->json(['message' => 'Item updated successfully', 'item' => $item], 200);
    }
    
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
    
        return response()->json(['success' => true, 'redirect_url' => route('dashboard')]);
    }
    


    


}
