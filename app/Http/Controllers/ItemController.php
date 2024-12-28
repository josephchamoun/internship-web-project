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
    



    


}
