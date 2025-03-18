<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
  
        
    public function createManager(Request $request)
{
    try {
        // Manually validate and handle validation errors properly
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'=> 'required|string|in:Manager,Employee'
        ]);

        // Check if email exists manually (redundant because of `unique:users`)
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'errors' => ['email' => ['Email already exists.']]
            ], 400);
        }

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'Manager'
        ]);

        // Generate token (optional)
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Manager created successfully!',
            'user' => $user,
            'token' => $token,
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