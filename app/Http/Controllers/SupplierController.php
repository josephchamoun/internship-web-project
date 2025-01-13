<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
    
        if ($searchTerm) {
            $suppliers = Supplier::where('name', 'like', '%' . $searchTerm . '%')->simplePaginate(20);
        } else {
            $suppliers = Supplier::simplePaginate(20);
        }
    
        return response()->json($suppliers);
    }
    
    public function store(Request $request)
    {
        $supplier = new Supplier();
        $supplier->name = $request->input('name');
        $supplier->email = $request->input('email');
        $supplier->phone = $request->input('phone');
        $supplier->save();
        
    }

    
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.editsupplier', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:suppliers,phone,' . $id,
            'email' => 'required|email|max:255|unique:suppliers,email,' . $id
            
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());

        return response()->json(['message' => 'Supplier updated successfully', 'supplier' => $supplier], 200);
    }
    
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
    
        return response()->json(['success' => true, 'redirect_url' => route('itemsupplier')]);
    }
    
}