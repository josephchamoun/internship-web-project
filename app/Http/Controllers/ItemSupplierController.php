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
        $items = ItemSupplier::with(['item', 'supplier'])->simplePaginate(20);

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
        $itemsupplier = ItemSupplier::with(['item', 'supplier'])->findOrFail($id);
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

        // Fetch the item and supplier by their names
        $item = Item::where('name', $request->itemname)->first();
        $supplier = Supplier::where('name', $request->suppliername)->first();

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }


        $oldquantity=$item->quantity;
        $quantitytoremove=$itemsupplier->quantity;
        $newoldquantity=$oldquantity-$quantitytoremove;
        $quantitytoadd=$request->quantity;

        $finalquantity=$newoldquantity+$quantitytoadd;
        $item->quantity=$finalquantity;
        $item->save();
        

        // Update the ItemSupplier record with the item_id and supplier_id
        $itemsupplier->item_id = $item->id;
        $itemsupplier->supplier_id = $supplier->id;
        $itemsupplier->buyprice = $request->buyprice;
        $itemsupplier->quantity = $request->quantity;
        $itemsupplier->save();

        return response()->json(['message' => 'ItemSupplier updated successfully', 'itemsupplier' => $itemsupplier], 200);
    }



    
    public function destroy($id)
   {
    $itemsupplier = ItemSupplier::findOrFail($id);
    $item = Item::where('id', $itemsupplier->item_id)->first();
    if (!$item) {
        return response()->json(['message' => 'Item not found'], 404);
    }

    $item->quantity =$item->quantity - $itemsupplier->quantity;
    $item->save();

    $itemsupplier->delete();

    return response()->json(['success' => true, 'redirect_url' => route('itemsupplier')]);
   }
}
    

