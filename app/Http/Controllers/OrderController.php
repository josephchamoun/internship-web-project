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

    public function index()//Gets all orders with their users
    {
        $orders = Order::with('user')->simplePaginate(20);
    
        // Format the `updated_at` field for each order
        $orders->getCollection()->transform(function ($order) {
            $order->updated_at = \Carbon\Carbon::parse($order->updated_at)->format('Y-m-d H:i:s');
            return $order;
        });
    
        return response()->json($orders);
    }


    public function MyOrdersindex() // Gets all orders for the authenticated user
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Get all orders belonging to the authenticated user, with pagination
        $orders = Order::where('user_id', $user->id)
            ->with('user') // Load the user relationship (optional, since you already have the user)
            ->simplePaginate(20);
    
        // Format the `updated_at` field for each order
        $orders->getCollection()->transform(function ($order) {
            $order->updated_at = \Carbon\Carbon::parse($order->updated_at)->format('Y-m-d H:i:s');
            return $order;
        });
       
    
        // Return the orders as a JSON response
        return response()->json($orders);
    }
    
    



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
