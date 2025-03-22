<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
        $page = $request->query('page', 1);
        $cacheKey = $searchTerm ? "suppliers_search_{$searchTerm}_page_{$page}" : "suppliers_page_{$page}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($searchTerm) {
            if ($searchTerm) {
                return Supplier::where('name', 'like', '%' . $searchTerm . '%')->simplePaginate(20);
            }
            return Supplier::simplePaginate(15);
        });
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:suppliers',
                'phone' => 'required|string|max:15'
            ]);

            $supplier = Cache::rememberForever("supplier_{$validated['email']}", function () use ($validated) {
                return Supplier::create($validated);
            });

            $this->clearSupplierCache($supplier);

            return response()->json(['success' => true, 'message' => 'Supplier created successfully!', 'supplier' => $supplier], 201);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $supplier = Cache::remember("supplier_{$id}", now()->addMinutes(10), function () use ($id) {
            return Supplier::findOrFail($id);
        });

        return view('suppliers.editsupplier', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'email' => 'required|email|max:255|unique:suppliers,email,' . $id
            ]);

            $supplier = Supplier::findOrFail($id);
            $supplier->update($validated);
            
            Cache::forget("supplier_{$id}");
            Cache::forget("supplier_{$supplier->email}");
            $this->clearSupplierCache($supplier);

            return response()->json(['success' => true, 'message' => 'Supplier updated successfully', 'supplier' => $supplier], 200);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        
        Cache::forget("supplier_{$id}");
        Cache::forget("supplier_{$supplier->email}");
        $this->clearSupplierCache($supplier);

        return response()->json(['success' => true, 'redirect_url' => route('itemsupplier')]);
    }

    private function clearSupplierCache($supplier)
    {
        Cache::forget("suppliers_page_1");
        Cache::forget("suppliers_search_{$supplier->name}_page_1");

        for ($i = 1; $i <= 10; $i++) {
            Cache::forget("suppliers_page_{$i}");
            Cache::forget("suppliers_search_{$supplier->name}_page_{$i}");
        }
    }
}