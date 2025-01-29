<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
</head>
<body>

<div class="container mx-auto px-4">
    <!-- Order Info Section -->
    <div id="order-info" class="bg-white shadow rounded-lg p-6 mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-semibold mb-2">Order Details</h2>
            <p class="text-gray-700 text-lg">
                <span class="font-bold">Order Number:</span> <span id="order-number"></span>
            </p>
            <p class="text-gray-700 text-lg">
                <span class="font-bold">Customer Name:</span> <span id="customer-name"></span>
            </p>
            <p class="text-gray-700 text-lg">
                <span class="font-bold">Total Price:</span> $ <span id="total-price"></span>
            </p>
        </div>
        <div id="btns">
            
        </div>
        <div id="order-status">
            <!-- Order status will be populated by JavaScript -->
        </div>
    </div>

    <!-- Items Section -->
    <div id="items-container" class="space-y-4">
        <!-- Items will be populated by JavaScript -->
    </div>

    <!-- Shipping Method Section -->
    <div id="shipping-method" class="bg-white shadow rounded-lg p-4 mt-6">
        <h3 class="text-xl font-semibold mb-2">Shipping Method</h3>
        <select id="shipping-select" class="border rounded px-4 py-2">
            <option value="10">Standard Shipping - $10</option>
        </select>
    </div>
</div>
<script>

const params = new URLSearchParams(window.location.search);

// Extract the 'status' parameter
const status = params.get('status');

// Check if the order is shipped
if (status === "shipped") {
    const shippedLabel = `<span class="bg-green-100 text-green-800 text-base font-semibold mr-2 px-4 py-1 rounded">Shipped</span>`;

    // Add the shipped label to the desired element
    document.getElementById('order-status').innerHTML = shippedLabel;

    const btnsdiv = document.getElementById('btns');
    if (btnsdiv) {
        btnsdiv.style.display = "none"; // Hides the element
    }
} else {
    

    const btns='<button id="cancel-order" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 mr-2">Cancel Order</button><button id="save-order" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Save Order</button>';
    document.getElementById('btns').innerHTML = btns;
    const orderStatusDiv = document.getElementById('order-status');
    if (orderStatusDiv) {
        orderStatusDiv.style.display = "none"; // Hides the element
    }
}



    let currentPage = 1; // Track the current page
    const order_id = @json($order_id); // Embed the order_id in the JavaScript context
    const shippingCost = 10; // Shipping cost

    async function fetchItems(page = 1) {
        try {
            const response = await fetch(`/api/orders/myorder/details/${order_id}?page=${page}`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token') // Include token for authenticated requests
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();

            const itemsContainer = document.getElementById('items-container');
            const orderInfo = data.order_info; // Assuming order info is returned in `data.order_info`

            // Clear existing items
            itemsContainer.innerHTML = '';

            // Populate order info
            document.getElementById('order-number').textContent = orderInfo.order_number || 'N/A';
            document.getElementById('customer-name').textContent = orderInfo.customer_name || 'N/A';
            document.getElementById('total-price').textContent = (parseFloat(orderInfo.total_amount) + shippingCost).toFixed(2);

            // Populate items
            data.data.forEach(itemOrder => {
                const itemCard = `
<div class="bg-white shadow rounded-lg flex items-center p-4">
    <div class="w-32 h-32 bg-gray-100 flex items-center justify-center rounded-lg overflow-hidden">
        <img src="${itemOrder.item.image_url || 'https://via.placeholder.com/150'}" alt="${itemOrder.item.name}" class="h-full w-auto object-cover">
    </div>
    <div class="ml-4 flex-1">
        <h5 class="font-semibold text-lg">${itemOrder.item.name}</h5>
<p class="text-gray-500 text-sm">Quantity: 
    <input type="number" value="${itemOrder.quantity}" min="1" class="quantity-input border rounded px-2 py-1" 
           data-id="${itemOrder.item.id}" data-price="${itemOrder.item.price}">
</p>

        <p class="text-gray-900 font-bold text-xl item-total-price">$${(itemOrder.quantity * itemOrder.item.price).toFixed(2)}</p>
        
        <button class="remove-item bg-red-500 text-white px-2 py-1 rounded-lg hover:bg-red-600 mt-2">Remove</button>
            
    </div>
</div>`;
                itemsContainer.innerHTML += itemCard;
            });

            // Add event listeners to quantity inputs
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('input', (event) => {
                    const quantity = event.target.value;
                    const price = event.target.getAttribute('data-price');
                    const totalPriceElement = event.target.closest('.flex-1').querySelector('.item-total-price');
                    totalPriceElement.textContent = `$${(quantity * price).toFixed(2)}`;
                    updateTotalPrice();
                });
            });

            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', (event) => {
                    const itemElement = event.target.closest('.bg-white');
                    itemElement.remove();
                    updateTotalPrice();
                });
            });

            function updateTotalPrice() {
                let totalPrice = 0;
                document.querySelectorAll('.quantity-input').forEach(input => {
                    const quantity = input.value;
                    const price = input.getAttribute('data-price');
                    totalPrice += quantity * price;
                });
                totalPrice += shippingCost; // Add shipping cost
                document.getElementById('total-price').textContent = totalPrice.toFixed(2);
            }

        } catch (error) {
            console.error('Error fetching items:', error);
        }
    }

    // Load items when the page loads
    document.addEventListener('DOMContentLoaded', () => fetchItems(currentPage));

    // Cancel order functionality
    document.getElementById('cancel-order').addEventListener('click', async () => {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const token = localStorage.getItem('token');
        const order_id = @json($order_id); // Replace this with your dynamic value
        
        console.log('Order ID:', order_id);
        console.log('CSRF Token:', csrfToken);
        console.log('Token:', token);

        const response = await fetch(`/api/orders/delete/${order_id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            throw new Error('Network response was not ok');
        }

        alert('Order has been cancelled successfully.');
    } catch (error) {
        console.error('Error cancelling order:', error);
        alert('Failed to cancel the order.');
    }
});


// Save changes functionality
document.getElementById('save-changes').addEventListener('click', async () => {
    try {
        const items = []; // Array to store items to update
        document.querySelectorAll('.quantity-input').forEach(input => {
            const quantity = input.value; // Get quantity
            const itemId = input.getAttribute('data-id'); // Get item ID
            if (itemId && quantity > 0) {
                items.push({ id: itemId, quantity: parseInt(quantity, 10) });
            }
        });

        if (items.length === 0) {
            alert('No items to update.');
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token

        const response = await fetch(`/api/orders/update/${order_id}`, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Include CSRF token
            },
            body: JSON.stringify({ items }) // Send items array as JSON
        });

        if (!response.ok) {
            throw new Error('Failed to update order. Please try again.');
        }

        const responseData = await response.json();
        alert(responseData.message || 'Order updated successfully.');
    } catch (error) {
        console.error('Error updating order:', error);
        alert('Failed to update the order. Please try again later.');
    }
});




</script>
<script src="https://cdn.tailwindcss.com"></script>
</body>
</html>