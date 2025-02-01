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
        <a href="/usersorders" class="bg-white/90 text-blue-600 px-6 py-2 rounded-xl shadow-md hover:bg-white transition-all duration-200 flex items-center w-fit">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            Back to Orders
        </a>
    </div>

    <!-- Order Info Section -->
    <div id="order-info" class="bg-gradient-to-r from-blue-200 to-pink-200 shadow-lg rounded-2xl p-8 mb-8 flex justify-between items-center">
        <div class="space-y-2">
            <h2 class="text-3xl font-bold text-blue-800 mb-3">Order Details</h2>
            <p class="text-blue-900 text-lg">
                <span class="font-semibold">Order Number:</span> 
                <span id="order-number" class="text-pink-700 font-medium">N/A</span>
            </p>
            <p class="text-blue-900 text-lg">
                <span class="font-semibold">Customer Name:</span> 
                <span id="customer-name" class="text-pink-700 font-medium">N/A</span>
            </p>
            <p class="text-blue-900 text-lg">
                <span class="font-semibold">Total Price:</span> 
                $<span id="total-price" class="text-pink-700 font-medium">0.00</span>
            </p>
        </div>
        <div class="flex flex-col items-end gap-4">
            <div id="btns" class="space-x-4"></div>
            <div id="order-status"></div>
        </div>
    </div>

    <!-- Items Section -->
    <div id="items-container" class="space-y-6">
        <!-- Items will be populated by JavaScript -->
    </div>

    <!-- Shipping Method Section -->
    <div id="shipping-method" class="bg-gradient-to-r from-blue-200 to-pink-200 shadow-lg rounded-2xl p-6 mt-8">
        <h3 class="text-2xl font-bold text-blue-800 mb-4">Free Shipping</h3>
        <div class="w-full p-4 border-2 border-pink-300 rounded-xl bg-white/80">
            <p class="text-pink-700 font-semibold">
                Shipping Cost: $0.00
            </p>
        </div>
    </div>
</div>

<script>
const params = new URLSearchParams(window.location.search);
const status = params.get('status');
const order_id = @json($order_id);
const shippingCost = 0;
let currentPage = 1;

if (status === "shipped") {
    const shippedLabel = `<span class="bg-green-100 text-green-800 text-lg font-semibold px-4 py-2 rounded-xl">🚚 Shipped</span>`;
    document.getElementById('order-status').innerHTML = shippedLabel;
    document.getElementById('btns').style.display = "none";
} else {
    const btns = `
        <button id="remove-order" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-3 rounded-xl transition-colors duration-200 shadow-md">
            🗑 Remove Order
        </button>
        <button id="ship-order" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition-colors duration-200 shadow-md">
            🚢 Ship Order
        </button>
    `;
    document.getElementById('btns').innerHTML = btns;
    document.getElementById('order-status').style.display = "none";
}

async function fetchItems(page = 1) {
    try {
        const response = await fetch(`/api/orders/userorder/details/${order_id}?page=${page}`, {
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
<div class="bg-white/90 shadow-lg rounded-xl p-6 border-2 border-blue-200">
    <div class="flex items-center gap-6">
        <div class="w-32 h-32 bg-pink-50 rounded-xl overflow-hidden border-2 border-pink-200">
            <img src="${itemOrder.item.image_url || 'https://via.placeholder.com/150'}" 
                 alt="${itemOrder.item.name}" 
                 class="w-full h-full object-cover">
        </div>
        <div class="flex-1">
            <h5 class="text-xl font-bold text-blue-800 mb-2">${itemOrder.item.name}</h5>
            <div class="flex items-center gap-4 mb-3">
                <span class="text-pink-700 font-medium">Quantity:</span>
                <span class="px-3 py-1 border-2 border-pink-300 rounded-lg text-blue-800 bg-pink-50">
                    ${itemOrder.quantity}
                </span>
            </div>
            <p class="text-pink-700 text-xl font-bold">
                $${(itemOrder.quantity * itemOrder.item.price).toFixed(2)}
            </p>
        </div>
    </div>
</div>`;
            itemsContainer.innerHTML += itemCard;
        });

    } catch (error) {
        console.error('Error fetching items:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => fetchItems(currentPage));

document.getElementById('remove-order').addEventListener('click', async () => {
    try {
        const response = await fetch(`/api/orders/delete/${order_id}`, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) throw new Error('Network response was not ok');
        alert('Order has been cancelled successfully.');
        window.location.href = '/usersorders';
    } catch (error) {
        console.error('Error cancelling order:', error);
        alert('Failed to cancel the order.');
    }
});

document.getElementById('ship-order').addEventListener('click', async () => {
    try {
        const response = await fetch(`/api/orders/userorder/update/${order_id}`, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) throw new Error('Network response was not ok');
       
        window.location.href = '/usersorders';
    } catch (error) {
        console.error('Error shipping order:', error);
        alert('Failed to ship the order.');
    }
});
</script>

<script src="https://cdn.tailwindcss.com"></script>
</body>
</html>