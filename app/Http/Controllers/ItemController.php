<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
     
       $items = Item::simplepaginate(20);
       return view('dashboard', compact('items'));
    }



    
/*
    public function destroy($id)//delete
    {
        $user = User::find($id);

        if ($user) {
            $user->delete(); // Deletes the user
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }
    }



    public function edit($id)
    {
        $user = User::findOrFail($id); // Find the user or fail
        return view('users.edit', compact('user'));
    }

    // Handle the update request
    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);

        // Update the user
        $user = User::findOrFail($id);
        $user->update($validated);

        // Redirect with success message
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
*/

}
