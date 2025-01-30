<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Log;
class CartController extends Controller
{
    public function addToCart(Request $request, $itemId)
    {
        // Assuming you get the item from the database
        $item = Item::find($itemId);
        
        if (!$item) {
            return redirect()->back()->with('error', 'Item not found');
        }

        // Check if the image_url already contains the 'uploads/items/' prefix
        if (strpos($item->image_url, 'uploads/items/') === false) {
            $imageUrl = asset('uploads/items/' . $item->image_url);
        } else {
            $imageUrl = asset($item->image_url);
        }

        // Check if the image file exists
        if (!file_exists(public_path('uploads/items/' . basename($imageUrl)))) {
            $imageUrl = asset('uploads/items/No_Image_Available.jpg');
        }
    
        Log::info('Adding item to cart', ['item_id' => $itemId, 'image_url' => $imageUrl]);

        // Retrieve the cart from the session or initialize it as an empty array
        $cart = session()->get('cart', []);
    
        // Get the quantity from the request
        $quantity = $request->input('quantity', 1);
    
        // Add the item to the cart, with the quantity
        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] += $quantity;
        } else {
            $cart[$itemId] = [
                'name' => $item->name,
                'quantity' => $quantity,
                'price' => $item->price,
                'image_url' => $imageUrl, // Add image_url to the cart
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

        // Initialize an array to store image URLs
        $cartImages = [];

        // Iterate through the cart items to retrieve image URLs
        foreach ($cart as $itemId => $item) {
            $cartImages[$itemId] = $item['image_url'];
        }

        // Calculate the total price of the cart items
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // Return the view and pass the cart, cart count, total price, and cart images
        return view('cart', compact('cart', 'cartCount', 'totalPrice', 'cartImages'));
    }
    
    
    

    public function removeFromCart($itemId)
    {
        $cart = session()->get('cart');
        unset($cart[$itemId]);
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Item removed from cart');
    }
}
