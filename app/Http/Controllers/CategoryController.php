<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
   
public function index(Request $request)
{
    $searchTerm = $request->query('search');

    if ($searchTerm) {
        $categories = Category::where('name', 'like', '%' . $searchTerm . '%')->simplePaginate(20);
    } else {
        $categories = Category::simplePaginate(16);
    }

    return response()->json($categories);
}

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.editcategory', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return response()->json(['message' => 'category updated successfully', 'category' => $category], 200);
    }



  
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
    
        $categories = new Category();
        $categories->name = $request->input('name');
        $categories->save();
        return redirect()->route('categories');
    }

    public function destroy($id)
{
    $category = Category::findOrFail($id);
    $category->delete();

    return response()->json(['success' => true, 'redirect_url' => route('categories')]);
}

}
