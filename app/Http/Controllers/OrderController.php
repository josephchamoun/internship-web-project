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
use Illuminate\Support\Facades\Cache;
use Exception;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        
        $cacheKey = "orders_list_{$status}";
        
        $orders = Cache::remember($cacheKey, 600, function () use ($status) {
            $query = Order::with('user')->orderBy('created_at', 'desc');
            if ($status !== 'all') {
                $query->where('status', $status);
            }
            return $query->paginate(12);
        });

        $stats = Cache::remember('order_stats', 600, function () {
            return [
                'totalOrders' => Order::count(),
                'pendingOrders' => Order::where('status', 'pending')->count(),
                'shippedOrders' => Order::where('status', 'shipped')->count(),
            ];
        });

        return response()->json(array_merge(['orders' => $orders], $stats));
    }

    public function MyOrdersindex()
    {
        $user = Auth::user();
        $cacheKey = "user_orders_{$user->id}";

        $orders = Cache::remember($cacheKey, 600, function () use ($user) {
            return Order::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->with('user')
                ->simplePaginate(24);
        });
        
        return response()->json($orders);
    }

    public function saveOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cart' => 'required|array',
                'cart.*.item_id' => 'required|exists:items,id',
                'cart.*.quantity' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $totalPrice = collect($request->cart)->sum(fn($item) => Item::find($item['item_id'])->price * $item['quantity']);

            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => $totalPrice,
            ]);

            foreach ($request->cart as $cartItem) {
                $item = Item::find($cartItem['item_id']);
                if ($item->quantity < $cartItem['quantity']) {
                    throw new Exception("Insufficient stock for {$item->name}");
                }
                $item->decrement('quantity', $cartItem['quantity']);
                ItemOrder::create(['order_id' => $order->id, 'item_id' => $cartItem['item_id'], 'quantity' => $cartItem['quantity']]);
            }

            DB::commit();

            // Clear cached data
            Cache::forget('order_stats');
            Cache::forget("user_orders_{$user->id}");
            Cache::forget('orders_list_all');

            return response()->json(['success' => true, 'order' => $order], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updatePending(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'shipped';
        $order->save();

        // Clear cached data
        Cache::forget('order_stats');
        Cache::forget('orders_list_all');

        return response()->json(['message' => 'Order status updated to shipped']);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Order cannot be deleted because it is not in a pending status.'], 400);
        }

        foreach ($order->items as $item) {
            $item->increment('quantity', $item->pivot->quantity);
        }
        $order->items()->detach();
        $order->delete();

        // Clear cached data
        Cache::forget('order_stats');
        Cache::forget('orders_list_all');
        Cache::forget("user_orders_{$order->user_id}");

        return response()->json(['success' => true, 'redirect_url' => url('/orders')]);
    }
}