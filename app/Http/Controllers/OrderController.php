<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ItemOrder;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{

    public function index()//Gets all orders with their users
    {
        $orders = Order::with('user')->simplePaginate(24);
    
        // Format the `updated_at` field for each order
        $orders->getCollection()->transform(function ($order) {
            $order->created_at = \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i:s');
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
            ->simplePaginate(24);
    
        // Format the `updated_at` field for each order
        $orders->getCollection()->transform(function ($order) {
            $order->created_at = \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i:s');
            return $order;
        });
       
    
        // Return the orders as a JSON response
        return response()->json($orders);
    }

    
    
    



    // Method for saving the order and its items via API
    public function saveOrder(Request $request)
    {
        try {
            // Validate the incoming data (e.g., cart items)
            $validator = Validator::make($request->all(), [
                'cart' => 'required|array',
                'cart.*.item_id' => 'required|exists:items,id',
                'cart.*.quantity' => 'required|integer|min:1',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            // Get the authenticated user
            $user = Auth::user();
    
            if (!$user) {
                return redirect()->route('login')->with('error', 'You need to log in to place an order.');
            }
    
            // Calculate the total price of the cart items
            $totalPrice = array_sum(array_map(function ($item) {
                return $item['price'] * $item['quantity'];
            }, $request->cart));
    
            // Begin database transaction
            DB::beginTransaction();
    
            // Create a new order for the authenticated user
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending', // Default status
                'total_amount' => $totalPrice, // Store the total price of the order
            ]);
    
            // Loop through each item in the cart and save it in the itemorder table
            foreach ($request->cart as $cartItem) {
                // Reduce the quantity of the item in the items table
                $item = Item::find($cartItem['item_id']);
                if ($item) {
                    if ($item->quantity < $cartItem['quantity']) {
                        throw new Exception('Insufficient stock for item: ' . $item->name);
                    }
    
                    $item->quantity -= $cartItem['quantity'];
                    $item->save();
                }
    
                // Save the item order
                ItemOrder::create([
                    'order_id' => $order->id,
                    'item_id' => $cartItem['item_id'], // Item ID from the request
                    'quantity' => $cartItem['quantity'], // Item quantity from the request
                ]);
            }
    
            // Commit transaction
            DB::commit();
    
            session()->forget('cart');
    
            // Redirect to an order confirmation page or dashboard
            return redirect()->route('myorders', ['order' => $order->id])
                             ->with('success', 'Order placed successfully!');
        }  catch (Exception $e) {
            // Redirect back with an error message
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updatePending(Request $request, $id)
    {
        // Fetch the order record
        $order = Order::findOrFail($id);
    
        // Update the order status to 'pending'
        $order->status = 'shipped';
        $order->save();
    
        // Return a success response
        return response()->json(['message' => 'Order status updated to shipped']);
    }

    public function destroy($id)
{
    // Find the order by ID or fail
    $order = Order::findOrFail($id);

    // Check if the order status is 'pending'
    if ($order->status !== 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'Order cannot be deleted because it is not in a pending status.'
        ], 400); // 400 Bad Request
    }

    // Detach all associated items from the order (deletes from the itemorder table)
    $order->items()->detach();

    // Delete the order itself
    $order->delete();

    return response()->json([
        'success' => true,
        'redirect_url' => url('/orders') // Adjust as needed
    ]);
}

    
    
    
 

    
    
}
