<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // Paginate items, 20 per page
        $items = Item::simplePaginate(20);

        // Return the paginated items as a JSON response
        return response()->json($items);
    }
    public function store(Request $request)
    {
        // Check if an item with the same name already exists
        $existingItem = Item::where('name', $request->input('name'))->first();
        
        if ($existingItem) {
            // Return an error message or handle it as needed
            return response()->json(['error' => 'Item name already exists.'], 400);
        }
    
        // If the item name doesn't exist, create a new item
        $item = new Item();
        $item->name = $request->input('name');
        $item->description = $request->input('description');
        $item->price = $request->input('price');
        $item->quantity = 0;
        $item->save();
    
        // Return a success response
        return response()->json(['success' => 'Item added successfully.'], 201);
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
    



    


}
