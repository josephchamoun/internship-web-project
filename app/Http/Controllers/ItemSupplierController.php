<?php

namespace App\Http\Controllers;

use App\Models\ItemSupplier;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;

class ItemSupplierController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
        $cacheKey = $searchTerm ? "itemsupplier_search_" . md5($searchTerm) : "itemsupplier_all";

        return Cache::remember($cacheKey, 600, function () use ($searchTerm) {
            $query = ItemSupplier::with(['item', 'supplier'])->orderBy('created_at', 'desc');
            if ($searchTerm) {
                $query->whereHas('item', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                });
            }
            return $query->simplePaginate(8);
        });
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'itemname' => 'required|string|max:255',
            'suppliername' => 'required|string|max:255',
            'buyprice' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
        ]);

        return DB::transaction(function () use ($validated) {
            $item = Item::where('name', $validated['itemname'])->firstOrFail();
            $supplier = Supplier::where('name', $validated['suppliername'])->firstOrFail();

            $item->increment('quantity', $validated['quantity']);

            ItemSupplier::create([
                'item_id' => $item->id,
                'supplier_id' => $supplier->id,
                'buyprice' => $validated['buyprice'],
                'quantity' => $validated['quantity'],
            ]);

            Cache::forget("itemsupplier_all");
            return response()->json(['message' => 'Supply added successfully'], 201);
        });
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'itemname' => 'required|string|max:255',
            'suppliername' => 'required|string|max:255',
            'buyprice' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
        ]);

        return DB::transaction(function () use ($validated, $id) {
            $itemsupplier = ItemSupplier::findOrFail($id);
            $oldItem = Item::findOrFail($itemsupplier->item_id);
            $supplier = Supplier::where('name', $validated['suppliername'])->firstOrFail();
            
            if ($itemsupplier->item->name !== $validated['itemname']) {
                $newItem = Item::where('name', $validated['itemname'])->firstOrFail();
                $oldItem->decrement('quantity', $itemsupplier->quantity);
                $newItem->increment('quantity', $validated['quantity']);
                $itemsupplier->item_id = $newItem->id;
            } else {
                $oldItem->quantity = ($oldItem->quantity - $itemsupplier->quantity + $validated['quantity']);
                $oldItem->save();
            }

            $itemsupplier->supplier_id = $supplier->id;
            $itemsupplier->buyprice = $validated['buyprice'];
            $itemsupplier->quantity = $validated['quantity'];
            $itemsupplier->save();

            Cache::forget("itemsupplier_all");
            return response()->json(['message' => 'ItemSupplier updated successfully']);
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $itemsupplier = ItemSupplier::findOrFail($id);
            $item = Item::findOrFail($itemsupplier->item_id);

            $item->decrement('quantity', $itemsupplier->quantity);
            $itemsupplier->delete();

            Cache::forget("itemsupplier_all");
            return response()->json(['success' => true]);
        });
    }
}
