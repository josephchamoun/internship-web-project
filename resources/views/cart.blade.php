<script src="https://cdn.tailwindcss.com"></script>
<div class="container mx-auto mt-10">
  <div class="sm:flex shadow-md my-10">
    <div class="w-full sm:w-3/4 bg-white px-10 py-10">
      <div class="flex justify-between border-b pb-8">
        <h1 class="font-semibold text-2xl">Shopping Cart</h1>
        <h2 class="font-semibold text-2xl">{{ $cartCount }} Items</h2>
      </div>

      <!-- Items Section -->
      @foreach ($cart as $itemId => $item)
      <div class="md:flex items-stretch py-8 md:py-10 lg:py-8 border-t border-gray-50">
        <div class="md:w-4/12 2xl:w-1/4 w-full">
          <!-- Item Image -->
        </div>
        <div class="md:pl-3 md:w-8/12 2xl:w-3/4 flex flex-col justify-center">
          <div class="flex items-center justify-between w-full">
            <p class="text-base font-black leading-none text-gray-800">{{ $item['name'] }}</p>
            <div class="flex items-center">
              <input type="number" value="{{ $item['quantity'] }}" min="1" class="quantity-input border text-center w-12" data-price="{{ $item['price'] }}" readonly>
            </div>
          </div>
          <p class="text-xs leading-3 text-gray-600 pt-2">Price: ${{ number_format($item['price'], 2) }}</p>
          <div class="flex items-center justify-between pt-5">
            <div class="flex items-center">
              <p class="text-xs leading-3 underline text-gray-800 cursor-pointer">Add to favorites</p>
              <form action="{{ route('cart.remove', $itemId) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs leading-3 underline text-red-500 pl-5 cursor-pointer">Remove</button>
              </form>
            </div>
            <p class="text-base font-black leading-none text-gray-800 item-total-price">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
          </div>
        </div>
      </div>
      @endforeach

      <a href="/dashboard" class="flex font-semibold text-indigo-600 text-sm mt-10">
        <svg class="fill-current mr-2 text-indigo-600 w-4" viewBox="0 0 448 512">
          <path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z" />
        </svg>
        Continue Shopping
      </a>
    </div>
    <div id="summary" class="w-full sm:w-1/4 md:w-1/2 px-8 py-10">
      <h1 class="font-semibold text-2xl border-b pb-8">Order Summary</h1>
      <div class="flex justify-between mt-10 mb-5">
        <span class="font-semibold text-sm uppercase">{{ $cartCount }} Items</span>
        <span class="font-semibold text-sm" id="summary-total-price">${{ number_format($totalPrice, 2) }}</span>
      </div>
      <div>
        <label class="font-medium inline-block mb-3 text-sm uppercase">Shipping</label>
        <select class="block p-2 text-gray-600 w-full text-sm">
          <option>Standard shipping - $10.00</option>
        </select>
      </div>
      <div class="py-10">
        <label for="promo" class="font-semibold inline-block mb-3 text-sm uppercase">Promo Code</label>
        <input type="text" id="promo" placeholder="Enter your code" class="p-2 text-sm w-full" />
      </div>
      <button class="bg-red-500 hover:bg-red-600 px-5 py-2 text-sm text-white uppercase">Apply</button>
      <div class="border-t mt-8">
        <div class="flex font-semibold justify-between py-6 text-sm uppercase">
          <span>Total cost</span>
          <span id="total-cost">${{ number_format($totalPrice + 10, 2) }}</span> <!-- Adding shipping cost -->
        </div>
        <form action="{{ url('/api/orders/addorder') }}" method="POST">
          @csrf
          @foreach ($cart as $itemId => $item)
          <input type="hidden" name="cart[{{ $loop->index }}][item_id]" value="{{ $itemId }}">
          <input type="hidden" name="cart[{{ $loop->index }}][quantity]" value="{{ $item['quantity'] }}">
          <input type="hidden" name="cart[{{ $loop->index }}][price]" value="{{ $item['price'] }}">
          @endforeach
          <input type="hidden" name="total_price" id="total_price" value="{{ $totalPrice + 10 }}">
          <button type="submit" class="bg-indigo-500 font-semibold hover:bg-indigo-600 py-3 text-sm text-white uppercase w-full">Checkout</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function redirectAfterSubmit(event) {
  event.preventDefault(); // Prevent the default form submission
  const form = event.target;

  // Submit the form using JavaScript
  fetch(form.action, {
    method: form.method,
    body: new FormData(form),
    headers: {
      'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
    },
  })
    .then(response => {
      if (response.ok) {
        // Redirect to the desired URL after successful submission
        window.location.href = '/myorders';
      } else {
        // Handle errors if needed
        alert('Error processing your order.');
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
}
</script>