<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ItemOrderController extends Controller
{
    public function MyOrderDetails($orderId)
    {
        $cacheKey = "user_order_details_" . auth()->id() . "_" . $orderId;
        
        $response = Cache::remember($cacheKey, 3600, function () use ($orderId) {
            $itemOrderDetails = ItemOrder::with(['item:id,name,price'])
                ->where('order_id', $orderId)
                ->paginate(100);

            if ($itemOrderDetails->isEmpty()) {
                return ['message' => 'Item orders not found'];
            }

            $order = Order::with('user:id,name')->where('user_id', auth()->id())->findOrFail($orderId);

            return [
                'order_info' => [
                    'order_number' => $order->id,
                    'customer_name' => $order->user->name,
                    'total_amount' => $order->total_amount,
                ],
                'data' => $itemOrderDetails->items(),
                'current_page' => $itemOrderDetails->currentPage(),
                'prev_page_url' => $itemOrderDetails->previousPageUrl(),
                'next_page_url' => $itemOrderDetails->nextPageUrl(),
            ];
        });

        return response()->json($response);
    }

    public function OrderDetails($orderId)
    {
        $cacheKey = "order_details_" . $orderId;
        
        $response = Cache::remember($cacheKey, 3600, function () use ($orderId) {
            $itemOrderDetails = ItemOrder::with(['item:id,name,price'])
                ->where('order_id', $orderId)
                ->paginate(100);

            if ($itemOrderDetails->isEmpty()) {
                return ['message' => 'Item orders not found'];
            }

            $order = Order::with('user:id,name')->findOrFail($orderId);

            return [
                'order_info' => [
                    'order_number' => $order->id,
                    'customer_name' => $order->user->name,
                    'total_amount' => $order->total_amount,
                ],
                'data' => $itemOrderDetails->items(),
                'current_page' => $itemOrderDetails->currentPage(),
                'prev_page_url' => $itemOrderDetails->previousPageUrl(),
                'next_page_url' => $itemOrderDetails->nextPageUrl(),
            ];
        });

        return response()->json($response);
    }

    public function update(Request $request, $id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status === 'shipped') {
            return response()->json(['message' => 'This order has already been shipped and cannot be updated.'], 403);
        }

        DB::transaction(function () use ($request, $order, $id) {
            $requestedItemIds = collect($request->items)->pluck('id')->toArray();
            $existingOrderItems = ItemOrder::where('order_id', $id)->get();

            foreach ($existingOrderItems as $existingOrderItem) {
                if (!in_array($existingOrderItem->item_id, $requestedItemIds)) {
                    $itemRecord = Item::find($existingOrderItem->item_id);
                    if ($itemRecord) {
                        $itemRecord->increment('quantity', $existingOrderItem->quantity);
                    }
                    $order->decrement('total_amount', $existingOrderItem->quantity * $existingOrderItem->item->price);
                    $existingOrderItem->delete();
                }
            }

            foreach ($request->items as $item) {
                $orderItem = ItemOrder::where('order_id', $id)->where('item_id', $item['id'])->first();
                if ($orderItem && $orderItem->item->quantity >= $item['quantity']) {
                    $oldQuantity = $orderItem->quantity;
                    $orderItem->update(['quantity' => $item['quantity']]);
                    $amountDifference = ($item['quantity'] - $oldQuantity) * $orderItem->item->price;
                    $order->increment('total_amount', $amountDifference);
                    $orderItem->item->decrement('quantity', $item['quantity'] - $oldQuantity);
                }
            }

            $order->save();
        });

        Cache::forget("user_order_details_" . auth()->id() . "_" . $id);
        Cache::forget("order_details_" . $id);

        return response()->json(['message' => 'Order updated successfully']);
    }
}
