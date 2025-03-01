<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class PersonController extends Controller
{
    public function updatePerson(Request $request)
    {
        $Auser = Auth::user();
        $userid = $Auser->id;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
           
        ]);

        $user = User::findOrFail($userid);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            
        ]);

        return response()->json(['message' => 'Success', 'user' => $user]);
    }


    public function updateUserPassword(Request $request)
    {
        $user = Auth::user(); // Get the logged-in user
    
        // Validate request data
        $request->validate([
            'oldpassword' => 'required|string|min:8', // No need for confirmation here
            'newpassword' => 'required|string|min:8|confirmed', // Ensures 'newpassword_confirmation' matches
        ]);
    
        // Check if the old password is correct
        if (!Hash::check($request->oldpassword, $user->password)) {
            return response()->json(['message' => 'Old password is incorrect'], 400);
        }
    
        // Update the password
        $user->update([
            'password' => Hash::make($request->newpassword),
        ]);
    
        return response()->json(['message' => 'Password updated successfully']);
    }

    public function deleteUser(Request $request)
    {
        $user = Auth::user();
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
