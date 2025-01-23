<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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

          
            $order = Order::with('user:id,name')->where('user_id', auth()->id())->findOrFail($orderId);

          
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

        public function OrderDetails($orderId)
        {
        
            $itemOrderDetails = ItemOrder::with(['item:id,name,price']) 
                ->where('order_id', $orderId)
                ->paginate(10); 

            if ($itemOrderDetails->isEmpty()) {
                return response()->json(['message' => 'Item orders not found'], 404);
            }

          
            $order = Order::with('user:id,name')->where('user_id', auth()->id())->findOrFail($orderId);

          
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

        public function update(Request $request, $id)
        {
            // Fetch the order record
            $order = Order::where('id', $id)
              ->where('user_id', Auth::id())
              ->firstOrFail();




        
            // Check if the order has already been shipped
            if ($order->status === 'shipped') {
                return response()->json(['message' => 'This order has already been shipped and cannot be updated.'], 403);
            }
        
            // Log the request data to check what is being received
            Log::info('Update Order Request:', ['order_id' => $id, 'items' => $request->items]);
        
            // Collect item IDs from the request
            $requestedItemIds = collect($request->items)->pluck('id')->toArray();
        
            // Find all existing ItemOrder rows for this order
            $existingOrderItems = ItemOrder::where('order_id', $id)->get();
        
            // Loop through the existing ItemOrder rows
            foreach ($existingOrderItems as $existingOrderItem) {
                // Check if the item's ID exists in the request
                if (!in_array($existingOrderItem->item_id, $requestedItemIds)) {
                    // Adjust the item's quantity back in the items table
                    $itemRecord = Item::find($existingOrderItem->item_id);
                    if ($itemRecord) {
                        $itemRecord->quantity += $existingOrderItem->quantity;
                        $itemRecord->save();
                    }
        
                    // Delete the ItemOrder row
                    $existingOrderItem->delete();
                }
            }
        
            // Loop through the items in the request to update the quantities and total_amount
            foreach ($request->items as $item) {
                // Find the corresponding ItemOrder
                $orderItem = ItemOrder::where('order_id', $id)
                    ->where('item_id', $item['id'])
                    ->first();
        
                // If the ItemOrder exists, update the quantity
                if ($orderItem) {
                    $oldQuantity = $orderItem->quantity;  // Save the old quantity
                    $orderItem->quantity = $item['quantity']; // Update the new quantity
                    $orderItem->save();
        
                    // Get the item's price
                    $itemPrice = $orderItem->item->price;
        
                    // Calculate the total amount for the current item before and after the update
                    $oldItemTotal = $oldQuantity * $itemPrice; // Old total for this item
                    $newItemTotal = $orderItem->quantity * $itemPrice; // New total for this item
        
                    // Calculate the difference to update the order's total_amount
                    $amountDifference = $newItemTotal - $oldItemTotal;
        
                    // Update the order's total_amount
                    $order->total_amount += $amountDifference;
        
                    // Update the quantity in the items table
                    $itemRecord = Item::find($item['id']);
                    if ($itemRecord) {
                        // Update the available quantity in the items table
                        $itemRecord->quantity -= ($item['quantity'] - $oldQuantity);
                        $itemRecord->save();
                    }
                }
            }
        
            // Save the updated order
            $order->save();
        
            // Return a success response
            return response()->json(['message' => 'Order updated successfully']);
        }
        
        
        
        
        






}
