
<script src="https://cdn.tailwindcss.com"></script>
<div class="container mx-auto mt-10">
  <div class="sm:flex shadow-md my-10">
    <div class="  w-full  sm:w-3/4 bg-white px-10 py-10">
      <div class="flex justify-between border-b pb-8">
        <h1 class="font-semibold text-2xl">Shopping Cart</h1>
        <h2 class="font-semibold text-2xl">{{ $cartCount }} Items</h2>

      </div>


      <!-- mn hon bi balcho l items-->
      @foreach ($cart as $itemId => $item)
   <div class="md:flex items-stretch py-8 md:py-10 lg:py-8 border-t border-gray-50">
     <div class="md:w-4/12 2xl:w-1/4 w-full">
       <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="h-full object-center object-cover md:block hidden" />
       <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="md:hidden w-full h-full object-center object-cover" />
     </div>
     <div class="md:pl-3 md:w-8/12 2xl:w-3/4 flex flex-col justify-center">

       <div class="flex items-center justify-between w-full">
         <p class="text-base font-black leading-none text-gray-800">{{ $item['name'] }}</p>
         <select aria-label="Select quantity" class="py-2 px-1 border border-gray-200 mr-6 focus:outline-none">
             <option>{{ $item['quantity'] }}</option>
             <!-- You can dynamically populate other quantities here, or just show the current quantity -->
         </select>
       </div>
       <p class="text-xs leading-3 text-gray-600 pt-2">Price: ${{ number_format($item['price'], 2) }}</p>

       <div class="flex items-center justify-between pt-5">
         <div class="flex items-center">
           <!-- Add to favorites and Remove buttons (you can make these functional later) -->
           <p class="text-xs leading-3 underline text-gray-800 cursor-pointer">Add to favorites</p>
           <form action="{{ route('cart.remove', $itemId) }}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-xs leading-3 underline text-red-500 pl-5 cursor-pointer">Remove</button>
          </form>

         </div>
         <p class="text-base font-black leading-none text-gray-800">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
       </div>
     </div>
   </div>
@endforeach


      <a href="/dashboard" class="flex font-semibold text-indigo-600 text-sm mt-10">
        <svg class="fill-current mr-2 text-indigo-600 w-4" viewBox="0 0 448 512">
          <path
            d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z" />
        </svg>
        Continue Shopping
      </a>
    </div>
    <div id="summary" class="w-full sm:w-1/4 md:w-1/2 px-8 py-10">
    <h1 class="font-semibold text-2xl border-b pb-8">Order Summary</h1>
    <div class="flex justify-between mt-10 mb-5">
        <span class="font-semibold text-sm uppercase">{{ $cartCount }} Items</span>
        <span class="font-semibold text-sm">${{ number_format($totalPrice, 2) }}</span>
    </div>
    <div>
        <label class="font-medium inline-block mb-3 text-sm uppercase">
            Shipping
        </label>
        <select class="block p-2 text-gray-600 w-full text-sm">
            <option>Standard shipping - $10.00</option>
        </select>
    </div>
    <div class="py-10">
        <label for="promo" class="font-semibold inline-block mb-3 text-sm uppercase">
            Promo Code
        </label>
        <input type="text" id="promo" placeholder="Enter your code" class="p-2 text-sm w-full" />
    </div>
    <button class="bg-red-500 hover:bg-red-600 px-5 py-2 text-sm text-white uppercase">
        Apply
    </button>
    <div class="border-t mt-8">
        <div class="flex font-semibold justify-between py-6 text-sm uppercase">
            <span>Total cost</span>
            <span>${{ number_format($totalPrice + 10, 2) }}</span> <!-- Adding shipping cost -->
        </div>
        <button class="bg-indigo-500 font-semibold hover:bg-indigo-600 py-3 text-sm text-white uppercase w-full">
            Checkout
        </button>
    </div>
</div>

  </div>
</div>


