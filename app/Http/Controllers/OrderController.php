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

    
   
public function index(Request $request)
{
    $status = $request->query('status', 'all');
    $query = Order::with('user')->orderBy('created_at', 'desc');

    if ($status !== 'all') {
        $query->where('status', $status);
    }

    $orders = $query->paginate(12); // Adjust the pagination size as needed

    return response()->json([
        'orders' => $orders,
        'totalOrders' => Order::count(),
        'pendingOrders' => Order::where('status', 'pending')->count(),
        'shippedOrders' => Order::where('status', 'shipped')->count(),
    ]);
}


    public function MyOrdersindex() // Gets all orders for the authenticated user
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Get all orders belonging to the authenticated user, with pagination
        $orders = Order::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
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
        // Validate the request
        $validator = Validator::make($request->all(), [
            'cart' => 'required|array',
            'cart.*.item_id' => 'required|exists:items,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get authenticated user
        $user = Auth::user() ?? User::find($request->user_id); 

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('login')->with('error', 'You need to log in.');
        }

        // Calculate total price
        $totalPrice = collect($request->cart)->sum(fn($item) => Item::find($item['item_id'])->price * $item['quantity']);

        // Begin transaction
        DB::beginTransaction();

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_amount' => $totalPrice,
        ]);

        // Save each item in the order
        foreach ($request->cart as $cartItem) {
            $item = Item::find($cartItem['item_id']);
            if ($item->quantity < $cartItem['quantity']) {
                throw new Exception("Insufficient stock for {$item->name}");
            }

            $item->decrement('quantity', $cartItem['quantity']);

            ItemOrder::create([
                'order_id' => $order->id,
                'item_id' => $cartItem['item_id'],
                'quantity' => $cartItem['quantity'],
            ]);
        }

        DB::commit();

        // Clear session cart (for Laravel view)
        session()->forget('cart');

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'order' => $order], 201);
        }

        return redirect()->route('myorders', ['order' => $order->id])->with('success', 'Order placed successfully!');
    } catch (Exception $e) {
        DB::rollBack();

        if ($request->wantsJson()) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

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

    // Return the item quantities
    foreach ($order->items as $item) {
        $item->increment('quantity', $item->pivot->quantity);
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
