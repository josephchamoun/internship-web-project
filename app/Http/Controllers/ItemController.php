<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
        $age = $request->query('age');
        $gender = $request->query('gender');
        $category = $request->query('category');

        $cacheKey = "items_search_{$searchTerm}_{$age}_{$gender}_{$category}";
        
        $items = Cache::remember($cacheKey, 300, function () use ($searchTerm, $age, $gender, $category) {
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
            $items->getCollection()->transform(function ($item) {
                $item->image_url = $item->image_url ? asset("storage/{$item->image_url}") : null;
                return $item;
            });
            return $items;
        });

        return response()->json($items);
    }

    public function index2()
    {
        $items = Cache::remember('all_items', 300, function () {
            return Item::all();
        });
        return response()->json($items);
    }

    public function store(Request $request)
    {
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
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('uploads/items', 'public') : null;

        $item = Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category,
            'gender' => $request->gender,
            'quantity' => 0,
            'age' => $request->age,
            'image_url' => $imagePath,
        ]);

        Cache::forget('all_items');
        return response()->json(['message' => 'Item added successfully', 'item' => $item]);
    }

    public function edit($id)
    {
        $item = Cache::remember("item_{$id}", 300, function () use ($id) {
            return Item::findOrFail($id);
        });
        return view('items.edititem', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:items,name,' . $id . '|string',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999.99',
            'quantity' => 'required|numeric|min:0|max:999999.99',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item = Item::findOrFail($id);
        $item->update($request->all());

        Cache::forget("item_{$id}");
        Cache::forget('all_items');

        return response()->json(['message' => 'Item updated successfully', 'item' => $item], 200);
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        Cache::forget("item_{$id}");
        Cache::forget('all_items');

        return response()->json(['success' => true, 'redirect_url' => route('dashboard')]);
    }
}
