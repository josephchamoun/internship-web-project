<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shopping Cart</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="font-sans antialiased">
  <div class="min-h-screen bg-gradient-to-r from-blue-300 to-pink-300 py-8">
    <div class="container mx-auto mt-10">
      <div class="sm:flex shadow-lg my-10 bg-white rounded-xl">
        <div class="w-full sm:w-3/4 bg-white px-10 py-10 rounded-l-xl">
          <div class="flex justify-between border-b-2 border-blue-100 pb-8">
            <h1 class="font-semibold text-3xl text-blue-600">Shopping Cart</h1>
            <h2 class="font-semibold text-3xl text-pink-500" id="cart-count">0 Items</h2>
          </div>
          <div id="cart-items"></div>
          <a href="/dashboard" class="inline-flex items-center font-semibold text-pink-600 hover:text-pink-800 mt-10 transition-colors">
            <svg class="fill-current mr-2 text-pink-600 w-5" viewBox="0 0 448 512">
              <path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"/>
            </svg>
            Continue Shopping
          </a>
        </div>
        <div id="summary" class="w-full sm:w-1/4 md:w-1/2 px-8 py-10 bg-white rounded-r-xl shadow-lg">
          <h1 class="font-semibold text-2xl text-blue-600 border-b-2 border-blue-100 pb-8">Order Summary</h1>
          <div class="flex justify-between mt-10 mb-5">
            <span class="font-semibold text-sm uppercase text-blue-600" id="summary-count">0 Items</span>
            <span class="font-semibold text-sm text-pink-600" id="summary-total-price">$0.00</span>
          </div>
          <div class="border-t-2 border-blue-100 mt-8">
            <div class="flex font-semibold justify-between py-6 text-sm uppercase">
              <span class="text-blue-600">Total cost</span>
              <span class="text-pink-600" id="total-cost">$0.00</span>
            </div>
            <form id="checkout-form" action="{{ url('/api/orders/addorder') }}" method="POST" class="space-y-4">
              @csrf
              <div id="cart-items-inputs"></div> <!-- This will hold hidden inputs dynamically -->
              <input type="hidden" name="total_price" id="total_price" value="0">
              <button type="submit" class="w-full bg-gradient-to-r from-pink-400 to-blue-400 hover:from-pink-500 hover:to-blue-500 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                Checkout Now
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
        loadCart();
        updateFormInputs();

        document.getElementById("checkout-form").addEventListener("submit", function (event) {
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            if (cart.length === 0) {
                alert("Your cart is empty!");
                event.preventDefault(); // Stop form submission if cart is empty
            } else {
                localStorage.removeItem("cart"); // Clear cart after successful checkout
            }
        });
    });

    function loadCart() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let cartItemsContainer = document.getElementById('cart-items');
        let cartCount = document.getElementById('cart-count');
        let summaryCount = document.getElementById('summary-count');
        let summaryTotalPrice = document.getElementById('summary-total-price');
        let totalCost = document.getElementById('total-cost');
        let totalPrice = 0;
        
        cartItemsContainer.innerHTML = '';
        cart.forEach((item, index) => {
            totalPrice += item.price * item.quantity;
            cartItemsContainer.innerHTML += `
              <div class="md:flex items-stretch py-8 border-t-2 border-blue-50">
                <div class="md:pl-6 md:w-8/12 flex flex-col justify-center">
                  <div class="flex items-center justify-between w-full">
                    <p class="text-lg font-bold text-blue-800">${item.name}</p>
                    <input type="number" value="${item.quantity}" min="1" class="border-2 border-pink-300 text-center w-16 text-blue-800 font-bold rounded-md" readonly>
                  </div>
                  <p class="text-sm text-pink-600 pt-2">Price: $${item.price.toFixed(2)}</p>
                  <div class="flex items-center justify-between pt-5">
                    <button onclick="removeItem(${index})" class="text-sm text-pink-600 hover:text-pink-800 transition-colors">
                      <i class="fas fa-trash-alt mr-1"></i>Remove
                    </button>
                    <p class="text-lg font-bold text-blue-800">$${(item.price * item.quantity).toFixed(2)}</p>
                  </div>
                </div>
              </div>`;
        });
        
        cartCount.textContent = `${cart.length} Items`;
        summaryCount.textContent = `${cart.length} Items`;
        summaryTotalPrice.textContent = `$${totalPrice.toFixed(2)}`;
        totalCost.textContent = `$${totalPrice.toFixed(2)}`;

        updateFormInputs(); // Update form inputs after reloading the cart
    }

    function removeItem(index) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart(); // Reload cart display
        updateFormInputs(); // Update hidden form inputs for checkout
    }

    function updateFormInputs() {
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        const cartItemsInputs = document.getElementById("cart-items-inputs");
        const totalPriceInput = document.getElementById("total_price");

        cartItemsInputs.innerHTML = ""; // Clear previous inputs
        let total = 0;

        cart.forEach((item, index) => {
            total += item.price * item.quantity;
            cartItemsInputs.innerHTML += `
                <input type="hidden" name="cart[${index}][item_id]" value="${item.id}">
                <input type="hidden" name="cart[${index}][quantity]" value="${item.quantity}">
                <input type="hidden" name="cart[${index}][price]" value="${item.price}">
            `;
        });

        totalPriceInput.value = total.toFixed(2); // Update total price
    }
  </script>
</body>
</html>
