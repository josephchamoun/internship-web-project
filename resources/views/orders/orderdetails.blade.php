<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-r from-blue-300 to-pink-300 min-h-screen">

<div class="container mx-auto px-4 py-8">
     <!-- Back Button -->
     <div class="mb-6">
        <a href="/myorders" class="bg-white/90 text-blue-600 px-6 py-2 rounded-xl shadow-md hover:bg-white transition-all duration-200 flex items-center w-fit">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            Back to My Orders
        </a>
    </div>
    <!-- Order Info Section -->
    <div id="order-info" class="bg-gradient-to-r from-blue-200 to-pink-200 shadow-lg rounded-2xl p-8 mb-8 flex justify-between items-center">
        <div class="space-y-2">
            <h2 class="text-3xl font-bold text-blue-600 mb-3">Order Details</h2>
            <p class="text-blue-800 text-lg">
                <span class="font-semibold">Order Number:</span> 
                <span id="order-number" class="text-pink-600 font-medium">N/A</span>
            </p>
            <p class="text-blue-800 text-lg">
                <span class="font-semibold">Customer Name:</span> 
                <span id="customer-name" class="text-pink-600 font-medium">N/A</span>
            </p>
            <p class="text-blue-800 text-lg">
                <span class="font-semibold">Total Price:</span> 
                $<span id="total-price" class="text-pink-600 font-medium">0.00</span>
            </p>
        </div>
        <div id="btns" class="space-x-4"></div>
        <div id="order-status"></div>
    </div>

    <!-- Items Section -->
    <div id="items-container" class="space-y-6">
        <!-- Items will be populated by JavaScript -->
    </div>

    <!-- Shipping Method Section -->
    <div id="shipping-method" class="bg-gradient-to-r from-blue-100 to-pink-100 shadow-lg rounded-2xl p-6 mt-8">
        <h3 class="text-2xl font-bold text-blue-600 mb-4">Shipping</h3>
        <p id="shipping-select" class="w-full p-3 border-2 border-pink-300 rounded-xl focus:ring-2 focus:ring-pink-400 focus:border-pink-400 text-blue-800 bg-white">
            
        Shipping Cost: Free ($0.00)
        </p>
    </div>
</div>

<script>
const params = new URLSearchParams(window.location.search);
const status = params.get('status');
const order_id = @json($order_id);
const shippingCost = 0;
let currentPage = 1;

if (status === "shipped") {
    const shippedLabel = `<span class="bg-green-100 text-green-800 text-lg font-semibold px-4 py-2 rounded-xl">Shipped</span>`;
    document.getElementById('order-status').innerHTML = shippedLabel;
    document.getElementById('btns').style.display = "none";
} else {
    const btns = `
        <button id="cancel-order" class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-xl transition-colors duration-200 shadow-md">
            Cancel Order
        </button>
        <button id="save-changes" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl transition-colors duration-200 shadow-md">
            Save Changes
        </button>
    `;
    document.getElementById('btns').innerHTML = btns;
    document.getElementById('order-status').style.display = "none";
}

async function fetchItems(page = 1) {
    try {
        const response = await fetch(`/api/orders/myorder/details/${order_id}?page=${page}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });

        if (!response.ok) throw new Error('Network response was not ok');
        const data = await response.json();

        const itemsContainer = document.getElementById('items-container');
        const orderInfo = data.order_info;

        // Update order info
        document.getElementById('order-number').textContent = orderInfo.order_number || 'N/A';
        document.getElementById('customer-name').textContent = orderInfo.customer_name || 'N/A';
        document.getElementById('total-price').textContent = (parseFloat(orderInfo.total_amount) + shippingCost).toFixed(2);

        // Populate items
        itemsContainer.innerHTML = '';
        data.data.forEach(itemOrder => {
            const itemCard = `
<div class="bg-white shadow-lg rounded-xl p-6 border-2 border-blue-100">
    <div class="flex items-center gap-6">
        <div class="w-32 h-32 bg-blue-50 rounded-xl overflow-hidden border-2 border-pink-100">
            <img src="${itemOrder.item.image_url || 'https://via.placeholder.com/150'}" 
                 alt="${itemOrder.item.name}" 
                 class="w-full h-full object-cover">
        </div>
        <div class="flex-1">
            <h5 class="text-xl font-bold text-blue-800 mb-2">${itemOrder.item.name}</h5>
            <div class="flex items-center gap-4 mb-3">
                <span class="text-pink-600 font-medium">Quantity:</span>
                ${status === "shipped" ? 
                    `<span class="px-3 py-1 border-2 border-pink-200 rounded-lg text-blue-800">${itemOrder.quantity}</span>` : 
                    `<input type="number" 
                           value="${itemOrder.quantity}" 
                           min="1" 
                           class="quantity-input w-20 px-3 py-1 border-2 border-pink-300 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-pink-400 text-blue-800"
                           data-id="${itemOrder.item.id}" 
                           data-price="${itemOrder.item.price}">`}
            </div>
            <p class="text-pink-600 text-xl font-bold">
                $${(itemOrder.quantity * itemOrder.item.price).toFixed(2)}
            </p>
            ${status !== "shipped" ? `
            <button class="remove-item mt-3 bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                Remove Item
            </button>` : ''}
        </div>
    </div>
</div>`;
            itemsContainer.innerHTML += itemCard;
        });

        if (status !== "shipped") {
            document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('input', (event) => {
        const quantity = event.target.value;
        const price = event.target.getAttribute('data-price');
        const totalPriceElement = event.target.closest('.flex-1').querySelector('.text-pink-600.text-xl.font-bold');
        totalPriceElement.textContent = `$${(quantity * price).toFixed(2)}`;
        updateTotalPrice();
    });

            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', (event) => {
                    event.target.closest('.bg-white').remove();
                    updateTotalPrice();
                });
            });
        }

        function updateTotalPrice() {
            let totalPrice = 0;
            document.querySelectorAll('.quantity-input').forEach(input => {
                totalPrice += input.value * input.getAttribute('data-price');
            });
            document.getElementById('total-price').textContent = (totalPrice + shippingCost).toFixed(2);
        }

    } catch (error) {
        console.error('Error fetching items:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => fetchItems(currentPage));

document.addEventListener('click', async (event) => {
    if (event.target.id === 'cancel-order') {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch(`/api/orders/delete/${order_id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            if (!response.ok) throw new Error('Cancellation failed');
            window.location.href = '/myorders';
        } catch (error) {
            console.error('Error cancelling order:', error);
            alert('Failed to cancel order');
        }
    }
});

document.addEventListener('click', async (event) => {
    if (event.target.id === 'save-changes') {
        try {
            const items = [];
            document.querySelectorAll('.quantity-input').forEach(input => {
                items.push({
                    id: input.getAttribute('data-id'),
                    quantity: parseInt(input.value)
                });
            });

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const response = await fetch(`/api/orders/update/${order_id}`, {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ items })
            });

            if (!response.ok) throw new Error('Update failed');
      
            window.location.href = '/myorders';
        } catch (error) {
            console.error('Error updating order:', error);
            alert('Failed to update order');
        }
    }
});


//iza l order details 2a2al mn 1 lezim ma y5ali ychilo w bas e3mel edit la quantity aw chil item lezim yen2as l total amount
</script>

<script src="https://cdn.tailwindcss.com"></script>
</body>
</html>







