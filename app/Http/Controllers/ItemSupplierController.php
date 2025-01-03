<?php

namespace App\Http\Controllers;


use App\Models\ItemSupplier;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class ItemSupplierController extends Controller
{


    public function index(Request $request)
    {
        // Paginate items, 20 per page
        $items = ItemSupplier::with(['item', 'supplier'])->paginate(20);

        // Return the paginated items as a JSON response
        return response()->json($items);
    }


    public function store(Request $request)
    {
        $request->validate([
            'itemname' => 'required|string|max:255',
            'suppliername' => 'required|string|max:255',
            'buyprice' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);
    
        DB::transaction(function () use ($request) {
           
            $item = Item::where('name', $request->itemname)->first();
    
         
            if (!$item) {
              
                return response()->json(['error' => 'Item does not exist.'], 400);
            }
    
          
            $item->quantity += $request->quantity;
            $item->save();
    
            
            $supplier = Supplier::where('name', $request->suppliername)->first();
    
          
            if (!$supplier) {
                return response()->json(['error' => 'Supplier does not exist.'], 400);
            }
    
           
            ItemSupplier::create([
                'item_id' => $item->id,
                'supplier_id' => $supplier->id,
                'buyprice' => $request->buyprice,
                'quantity' => $request->quantity,
            ]);
        });
    
        return response()->json(['message' => 'Supply added successfully'], 201);
    }
    
    
    


    public function edit($id)
    {
        $itemsupplier = ItemSupplier::findOrFail($id);
        
        if (!is_object($itemsupplier)) {
            return response()->json(['message' => 'ItemSupplier not found'], 404);
        }

        return view('items.editsupply', compact('itemsupplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'itemname' => 'required|string|max:255',
            'suppliername' => 'required|string|max:255',
            'buyprice' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
        ]);

        $itemsupplier = ItemSupplier::findOrFail($id);
        
        if (!is_object($itemsupplier)) {
            return response()->json(['message' => 'ItemSupplier not found'], 404);
        }

        $itemsupplier->update($request->all());

        return response()->json(['message' => 'ItemSupplier updated successfully', 'itemsupplier' => $itemsupplier], 200);
    }
    
}
    

