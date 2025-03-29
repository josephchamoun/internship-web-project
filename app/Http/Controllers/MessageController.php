<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{

    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
        
        if ($searchTerm) {
            $messages = Message::with('user') // Eager load the related user
                ->where('subject', 'like', '%' . $searchTerm . '%')
                ->simplePaginate(20);
        } else {
            $messages = Message::with('user') // Eager load the related user
                ->simplePaginate(20);
        }
    
        return response()->json($messages);
    }
    
    
    public function store(Request $request)
{
    $user = auth()->user();

    // Validate the incoming data
    $request->validate([
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
        'receiver_id' => 'nullable|exists:users,id',
    ]);

    // Create the message
    Message::create([
        'sender_id' => $user->id,
        'receiver_id' => $request->receiver_id,
        'message' => $request->message,
        'subject' => $request->subject,
    ]);

    // Respond with a success message and redirection URL
    return response()->json([
        'success' => true,
        'message' => 'Message sent successfully!',
        'redirect_url' => route('contact')  // Include the redirection URL
    ]);
}

    
}
