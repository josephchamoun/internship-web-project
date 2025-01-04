<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ItemOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    // Method for saving the order and its items via API
    public function saveOrder(Request $request)
    {
        // Validate the incoming data (e.g., cart items)
        $validator = Validator::make($request->all(), [
            'cart' => 'required|array',
            'cart.*.item_id' => 'required|exists:items,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }
    
        // Get the authenticated user
        $user = Auth::user();
    
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated.'
            ], 401);
        }
    
        // Calculate the total price of the cart items
        $totalPrice = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $request->cart));
    
        // Create a new order for the authenticated user
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending', // Default status
            'total_price' => $totalPrice, // Store the total price of the order
        ]);
    
        // Loop through each item in the cart and save it in the itemorder table
        foreach ($request->cart as $cartItem) {
            ItemOrder::create([
                'order_id' => $order->id,
                'item_id' => $cartItem['item_id'],  // Item ID from the request
                'quantity' => $cartItem['quantity'], // Item quantity from the request
            ]);
        }
    
        // Return a successful response with the order data
        return response()->json([
            'status' => 'success',
            'message' => 'Order placed successfully.',
            'data' => $order
        ], 201);
    }
}
