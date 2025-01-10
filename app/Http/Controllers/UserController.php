<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /*
    public function storeManager(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new manager user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'Manager', // Set the role to Manager
        ]);

        // Respond with success or the created user data
        return response()->json([
            'message' => 'Manager created successfully!',
            'user' => $user,
        ], 201); // 201 HTTP code indicates resource creation
    }
        */
        
    public function createManager(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->role = $request->input('role', 'Manager'); // Default to Manager if missing
        $user->save();
        
    }
    

    








    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
    
        if ($searchTerm) {
            $users = User::where('name', 'like', '%' . $searchTerm . '%')->simplePaginate(20);
        } else {
            $users = User::simplePaginate(12);
        }
    
        return response()->json($users);
    }

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


}