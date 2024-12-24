<?php

namespace App\Http\Controllers;


use App\Models\ItemSupplier;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ItemSupplierController extends Controller
{


    public function index()
    {
        $itemsuppliers = ItemSupplier::with(['item', 'supplier'])->paginate(20);
        return view('items.items', compact('itemsuppliers'));
    }


    public function destroy($id)//delete
    {
        $itemsupplier = ItemSupplier::find($id);

        if ($itemsupplier) {
            $itemsupplier->delete(); // Deletes the user
            return redirect()->route('items.index')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->route('items.index')->with('error', 'User not found.');
        }
    }



    public function edit($id)
    {
        $itemsupply = ItemSupplier::with('item')->findOrFail($id); // Ensure 'item' relationship is loaded
        
        return view('items.editsupply', compact('itemsupply'));
    }
    

    // Handle the update request
    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'itemname' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'itemsupplier' => 'required|string|max:255',
        ]);
    
        // Check if the supplier exists
        $supplier = Supplier::where('name', $validated['itemsupplier'])->first();
        if (!$supplier) {
            return redirect()->back()->withErrors(['itemsupplier' => 'The supplier does not exist.']);
        }
    
        // Check if the item exists, if not create a new item
        $item = Item::where('name', $validated['itemname'])->first();
        if (!$item) {
            $item = Item::create(['name' => $validated['itemname']]);
        }
    
        // Update the ItemSupply record
        $itemsupply = ItemSupplier::findOrFail($id);
        $itemsupply->item_id = $item->id; // Associate with the item
        $itemsupply->quantity = $validated['quantity'];
        $itemsupply->buyprice = $validated['price'];
        $itemsupply->supplier_id = $supplier->id; // Associate with the supplier
        $itemsupply->save();
    
        // Redirect with success message
        return redirect()->route('items.index')->with('success', 'Item supply updated successfully.');
    }
    
}
