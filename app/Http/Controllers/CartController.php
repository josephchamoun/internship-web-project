<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class CartController extends Controller
{
    public function addToCart(Request $request, $itemId)
    {
        // Assuming you get the item from the database
        $item = Item::find($itemId);
        
        if (!$item) {
            return redirect()->back()->with('error', 'Item not found');
        }

        // Retrieve the cart from the session or initialize it as an empty array
        $cart = session()->get('cart', []);

        // Add the item to the cart, with the quantity
        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity']++;
        } else {
            $cart[$itemId] = [
                'name' => $item->name,
                'quantity' => 1,
                'price' => $item->price,
                'image' => $item->image, // Assuming you have an image column for items
            ];
        }

        // Save the updated cart in the session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Item added to cart');
    }

    public function viewCart()
    {
        // Retrieve the cart from the session
        $cart = session()->get('cart', []);
    
        // Get the count of items in the cart
        $cartCount = count($cart);
    
        // Calculate the total price of the cart items
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
    
        // Return the view and pass the cart, cart count, and total price
        return view('cart', compact('cart', 'cartCount', 'totalPrice'));
    }
    
    
    

    public function removeFromCart($itemId)
    {
        $cart = session()->get('cart');
        unset($cart[$itemId]);
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Item removed from cart');
    }
}
