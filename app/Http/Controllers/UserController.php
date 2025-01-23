<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
  
        
    public function createManager(Request $request)
{
    // Validate the input data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ]);

    // Create the user
    $user = new User();
    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->password = bcrypt($validated['password']);
    $user->role = $request->input('role', 'Manager'); // Default to 'Manager' if not provided
    $user->save();

    // Generate a token (if applicable)
    $token = $user->createToken('authToken')->plainTextToken; // Direct access to plainTextToken

    // Return a JSON response
    return response()->json([
        'success' => true,
        'message' => 'Manager created successfully!',
        'user' => $user,
        'token' => $token,
    ], 201);
}

    
    

    








    public function index(Request $request)
    {   
        $userCount = User::count();
        $searchTerm = $request->query('search');
    
        if ($searchTerm) {
            $users = User::where('name', 'like', '%' . $searchTerm . '%')->simplePaginate(20);
        } else {
            $users = User::simplePaginate(12);
        }
    
        return response()->json([
            'users' => $users,
            'userCount' => $userCount
        ]);
    }

    public function destroy($id)
   {
    $user = User::findOrFail($id);

    if (auth()->user()->role !== 'Manager') {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $user->delete();

    return response()->json(['success' => true, 'redirect_url' => route('users.index')]);
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


}