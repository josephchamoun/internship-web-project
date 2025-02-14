<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shopping Cart</title>
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="font-sans antialiased">
  <div class="min-h-screen bg-gradient-to-r from-blue-300 to-pink-300 py-8">
    <div class="container mx-auto mt-10">
      <div class="sm:flex shadow-lg my-10 bg-white rounded-xl">
        <!-- Cart Items Section -->
        <div class="w-full sm:w-3/4 bg-white px-10 py-10 rounded-l-xl">
          <div class="flex justify-between border-b-2 border-blue-100 pb-8">
            <h1 class="font-semibold text-3xl text-blue-600">Shopping Cart</h1>
            <h2 class="font-semibold text-3xl text-pink-500">{{ $cartCount }} Items</h2>
          </div>
          
          <!-- Items Section -->
          @foreach ($cart as $itemId => $item)
          <div class="md:flex items-stretch py-8 md:py-10 lg:py-8 border-t-2 border-blue-50">
            <div class="md:w-4/12 2xl:w-1/4 w-full">
              <img src="{{ $item['image_url'] ?? 'uploads/items/No_Image_Available.jpg' }}" 
                   alt="{{ $item['name'] }}" 
                   class="w-full h-auto object-cover border-4 border-pink-100 rounded-lg shadow-sm">
            </div>
            <div class="md:pl-6 md:w-8/12 2xl:w-3/4 flex flex-col justify-center">
              <div class="flex items-center justify-between w-full">
                <p class="text-lg font-bold text-blue-800">{{ $item['name'] }}</p>
                <div class="flex items-center">
                  <input type="number" 
                         value="{{ $item['quantity'] }}" 
                         min="1" 
                         class="quantity-input border-2 border-pink-300 text-center w-16 text-blue-800 font-bold rounded-md"
                         data-price="{{ $item['price'] }}" 
                         readonly>
                </div>
              </div>
              <p class="text-sm text-pink-600 pt-2">Price: ${{ number_format($item['price'], 2) }}</p>
              <div class="flex items-center justify-between pt-5">
                <div class="flex items-center space-x-4">
                  <button class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                    <i class="far fa-heart mr-1"></i>Add to favorites
                  </button>
                  <form action="{{ route('cart.remove', $itemId) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-pink-600 hover:text-pink-800 transition-colors">
                      <i class="fas fa-trash-alt mr-1"></i>Remove
                    </button>
                  </form>
                </div>
                <p class="text-lg font-bold text-blue-800 item-total-price">
                  ${{ number_format($item['price'] * $item['quantity'], 2) }}
                </p>
              </div>
            </div>
          </div>
          @endforeach

          <!-- Continue Shopping Button -->
          <a href="/dashboard" class="inline-flex items-center font-semibold text-pink-600 hover:text-pink-800 mt-10 transition-colors">
            <svg class="fill-current mr-2 text-pink-600 w-5" viewBox="0 0 448 512">
              <path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/>
            </svg>
            Continue Shopping
          </a>
        </div>

        <!-- Order Summary Section -->
        <div id="summary" class="w-full sm:w-1/4 md:w-1/2 px-8 py-10 bg-white rounded-r-xl shadow-lg">
          <h1 class="font-semibold text-2xl text-blue-600 border-b-2 border-blue-100 pb-8">Order Summary</h1>
          <div class="flex justify-between mt-10 mb-5">
            <span class="font-semibold text-sm uppercase text-blue-600">{{ $cartCount }} Items</span>
            <span class="font-semibold text-sm text-pink-600" id="summary-total-price">${{ number_format($totalPrice, 2) }}</span>
          </div>
          <div class="mb-8">
            <label class="font-medium inline-block mb-3 text-sm uppercase text-blue-600">Shipping</label>
            <p class="w-full p-3 text-sm text-pink-600 border-2 border-blue-100 rounded-lg focus:border-pink-300 focus:ring-pink-300">
              Free Shipping $0.00
            </p>
          </div>

          <div class="border-t-2 border-blue-100 mt-8">
            <div class="flex font-semibold justify-between py-6 text-sm uppercase">
              <span class="text-blue-600">Total cost</span>
              <span class="text-pink-600" id="total-cost">${{ number_format($totalPrice, 2) }}</span>
            </div>
            <form action="{{ url('/api/orders/addorder') }}" method="POST" class="space-y-4">
              @csrf
              @foreach ($cart as $itemId => $item)
              <input type="hidden" name="cart[{{ $loop->index }}][item_id]" value="{{ $itemId }}">
              <input type="hidden" name="cart[{{ $loop->index }}][quantity]" value="{{ $item['quantity'] }}">
              <input type="hidden" name="cart[{{ $loop->index }}][price]" value="{{ $item['price'] }}">
              @endforeach
              <input type="hidden" name="total_price" id="total_price" value="{{ $totalPrice}}">
              <button type="submit" class="w-full bg-gradient-to-r from-pink-400 to-blue-400 hover:from-pink-500 hover:to-blue-500 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                Checkout Now
              </button>
            </form>
            @if (session('error'))
            <div class="mt-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-700 rounded">
              <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>