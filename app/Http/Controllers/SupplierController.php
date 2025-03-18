<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
    
        if ($searchTerm) {
            $suppliers = Supplier::where('name', 'like', '%' . $searchTerm . '%')->simplePaginate(20);
        } else {
            $suppliers = Supplier::simplePaginate(15);
        }
    
        return response()->json($suppliers);
    }
    
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:suppliers',
                'phone' => 'required|string|max:15'
            ]);

            // Check if email exists manually (redundant because of `unique:suppliers`)
            if (Supplier::where('email', $request->email)->exists()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['email' => ['Email already exists.']]
                ], 400);
            }

            // Create the supplier
            $supplier = Supplier::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Supplier created successfully!',
                'supplier' => $supplier,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.editsupplier', compact('supplier'));
    }

    public function update(Request $request, $id)
{
    try {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255|unique:suppliers,email,' . $id
        ]);

        // Additional manual check if email already exists (extra safety)
        if (Supplier::where('email', $request->email)->where('id', '!=', $id)->exists()) {
            return response()->json([
                'success' => false,
                'errors' => ['email' => ['Email is already in use by another supplier.']]
            ], 400);
        }

        // Find supplier
        $supplier = Supplier::findOrFail($id);
        $supplier->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully',
            'supplier' => $supplier
        ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
    
        return response()->json(['success' => true, 'redirect_url' => route('itemsupplier')]);
    }
    
}