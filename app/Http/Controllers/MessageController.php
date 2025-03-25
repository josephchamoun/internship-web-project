<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
        $cacheKey = $searchTerm ? "messages_search_" . md5($searchTerm) : "messages_all";

        $messages = Cache::remember($cacheKey, 600, function () use ($searchTerm) {
            $query = Message::with('user'); // Eager load the related user
            
            if ($searchTerm) {
                $query->where('subject', 'like', '%' . $searchTerm . '%');
            }

            return $query->simplePaginate(20);
        });
    
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
    
        // Clear cache for messages
        Cache::forget("messages_all");
        Cache::flush(); // Optional: Flush all cache if needed
    
        // Respond with a success message and redirection URL
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
            'redirect_url' => route('contact') // Include the redirection URL
        ]);
    }
}
