<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemOrder;
use App\Models\Order;

class ItemOrderController extends Controller
{
            public function MyOrderDetails($orderId)
        {
        
            $itemOrderDetails = ItemOrder::with(['item:id,name,price']) 
                ->where('order_id', $orderId)
                ->paginate(10); 

            if ($itemOrderDetails->isEmpty()) {
                return response()->json(['message' => 'Item orders not found'], 404);
            }

          
            $order = Order::with('user:id,name')->findOrFail($orderId);

          
            $response = [
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

            return response()->json($response);
        }
}
