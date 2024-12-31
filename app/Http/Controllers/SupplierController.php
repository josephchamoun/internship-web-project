<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        // Paginate items, 20 per page
        $suppliers = Supplier::simplePaginate(20);

        // Return the paginated items as a JSON response
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

}