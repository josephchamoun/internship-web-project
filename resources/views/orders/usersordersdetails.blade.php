<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Details</title>
</head>
<body class="bg-gradient-to-r from-blue-50 to-indigo-50 min-h-screen font-sans">

<div class="container mx-auto px-4 py-8 max-w-6xl">
    <!-- Header with Back Button -->
    <div class="mb-8 flex items-center justify-between">
        <a href="/usersorders" class="group flex items-center gap-2 bg-white/80 text-blue-600 px-4 py-2.5 rounded-lg shadow-sm hover:bg-white hover:shadow transition-all duration-200">
            <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">Back to Orders</span>
        </a>
        
        <div class="text-sm text-gray-500">
            Last updated: <span id="current-date" class="font-medium text-indigo-600"></span>
        </div>
    </div>

    <!-- Order Status Timeline -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold text-gray-700">Order Status</h3>
            <div id="order-status-label"></div>
        </div>
        
        <div class="relative">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            
            <div class="relative flex justify-between">
                <div class="flex flex-col items-center">
                    <div class="bg-indigo-500 rounded-full h-6 w-6 flex items-center justify-center ring-4 ring-white">
                        <svg class="h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="mt-2 text-xs text-gray-500">Ordered</span>
                </div>
                
                <div class="flex flex-col items-center">
                    <div id="processing-icon" class="bg-gray-200 rounded-full h-6 w-6 flex items-center justify-center ring-4 ring-white">
                        <svg class="h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="mt-2 text-xs text-gray-500">Processing</span>
                </div>
                
                <div class="flex flex-col items-center">
                    <div id="shipped-icon" class="bg-gray-200 rounded-full h-6 w-6 flex items-center justify-center ring-4 ring-white">
                        <svg class="h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="mt-2 text-xs text-gray-500">Shipped</span>
                </div>
                

            </div>
        </div>
    </div>

    <!-- Order Info Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="md:col-span-2 bg-white shadow-md rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Order Information</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-600">Order Number:</span>
                    <span id="order-number" class="font-semibold text-indigo-700">N/A</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-600">Customer:</span>
                    <span id="customer-name" class="font-semibold text-indigo-700">N/A</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-600">Order Date:</span>
                    <span id="order-date" class="font-semibold text-indigo-700">N/A</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-600">Total Items:</span>
                    <span id="item-count" class="font-semibold text-indigo-700">0</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-600">Total Price:</span>
                    <span class="font-semibold text-lg text-indigo-700">$<span id="total-price">0.00</span></span>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Actions</h2>
            </div>
            <div class="p-6 space-y-4">
                <div id="btns" class="space-y-3"></div>
                <div id="order-status"></div>
                
                
            </div>
        </div>
    </div>

    <!-- Items Section Header -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-800">Order Items</h3>
        <div class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-medium" id="items-count-badge">0 items</div>
    </div>

    <!-- Items Section -->
    <div id="items-container" class="space-y-4">
        <!-- Items will be populated by JavaScript -->
    </div>

    <!-- Shipping Method Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <div id="shipping-method" class="bg-white shadow-md rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white">Shipping Method</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                    <div class="mr-4 bg-blue-100 p-2 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-blue-800">Free Shipping</p>
                        <p class="text-sm text-blue-600">Shipping Cost: $0.00</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white shadow-md rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white">Payment Information</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-600">Subtotal:</span>
                    <span id="subtotal" class="font-semibold text-gray-800">$0.00</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-600">Shipping:</span>
                    <span class="font-semibold text-gray-800">$0.00</span>
                </div>
                <div class="flex justify-between items-center pb-3">
                    <span class="text-gray-800 font-medium">Total:</span>
                    <span class="font-bold text-lg text-indigo-700">$<span id="total-with-shipping">0.00</span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const params = new URLSearchParams(window.location.search);
const status = params.get('status');
const order_id = @json($order_id);
const shippingCost = 0;
let currentPage = 1;
let itemCount = 0;
let orderItems = [];

// Set current date
const currentDate = new Date();
document.getElementById('current-date').textContent = currentDate.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
});

// Update status timeline based on order status
function updateStatusTimeline(status) {
    if (status === "shipped") {
        // Update processing icon
        const processingIcon = document.getElementById('processing-icon');
        processingIcon.classList.remove('bg-gray-200');
        processingIcon.classList.add('bg-indigo-500');
        processingIcon.querySelector('svg').classList.remove('text-gray-400');
        processingIcon.querySelector('svg').classList.add('text-white');
        
        // Update shipped icon
        const shippedIcon = document.getElementById('shipped-icon');
        shippedIcon.classList.remove('bg-gray-200');
        shippedIcon.classList.add('bg-indigo-500');
        shippedIcon.querySelector('svg').classList.remove('text-gray-400');
        shippedIcon.querySelector('svg').classList.add('text-white');
    }
}

// Handle order status and buttons
if (status === "shipped") {
    const shippedLabel = `<span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">🚚 Shipped</span>`;
    document.getElementById('order-status').innerHTML = shippedLabel;
    document.getElementById('order-status-label').innerHTML = shippedLabel;
    document.getElementById('btns').style.display = "none";
    updateStatusTimeline("shipped");
} else {
    const processingLabel = `<span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">Processing</span>`;
    document.getElementById('order-status-label').innerHTML = processingLabel;
    
    const btns = `
        <button id="ship-order" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-lg transition-colors duration-200 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>Ship Order</span>
        </button>
        <button id="remove-order" class="w-full flex items-center justify-center gap-2 bg-white border border-red-500 text-red-500 hover:bg-red-50 px-4 py-3 rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            <span>Remove Order</span>
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
        orderItems = data.data;
        itemCount = orderItems.length;

        // Update order info
        document.getElementById('order-number').textContent = orderInfo.order_number || 'N/A';
        document.getElementById('customer-name').textContent = orderInfo.customer_name || 'N/A';
        
        // Set order date if available, otherwise use current date
        const orderDate = orderInfo.created_at ? new Date(orderInfo.created_at) : new Date();
        document.getElementById('order-date').textContent = orderDate.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
        
        // Calculate totals
        let subtotal = 0;
        orderItems.forEach(item => {
            subtotal += (item.quantity * item.item.price);
        });
        
        document.getElementById('total-price').textContent = (subtotal + shippingCost).toFixed(2);
        document.getElementById('total-with-shipping').textContent = (subtotal + shippingCost).toFixed(2);
        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        
        // Update item count
        document.getElementById('item-count').textContent = itemCount;
        document.getElementById('items-count-badge').textContent = `${itemCount} item${itemCount !== 1 ? 's' : ''}`;

        // Populate items
        itemsContainer.innerHTML = '';
        data.data.forEach((itemOrder, index) => {
            const isLastItem = index === data.data.length - 1;
            const borderClass = isLastItem ? '' : 'border-b border-gray-100 pb-4 mb-4';
            
            const itemCard = `
<div class="bg-white shadow-md rounded-xl overflow-hidden ${borderClass}">
    <div class="p-4 md:p-6">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="w-full md:w-24 h-24 bg-gray-50 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                <img src="${itemOrder.item.image_url || 'https://via.placeholder.com/150'}" 
                     alt="${itemOrder.item.name}" 
                     class="w-full h-full object-cover">
            </div>
            <div class="flex-1">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h5 class="text-lg font-bold text-gray-800 mb-1">${itemOrder.item.name}</h5>
                        <p class="text-sm text-gray-500 mb-2">SKU: ${itemOrder.item.id || 'N/A'}</p>
                    </div>
                    <div class="mt-2 md:mt-0 text-right">
                        <p class="text-indigo-700 text-lg font-bold">
                            $${(itemOrder.quantity * itemOrder.item.price).toFixed(2)}
                        </p>
                        <p class="text-sm text-gray-500">
                            ${itemOrder.quantity} × $${parseFloat(itemOrder.item.price).toFixed(2)}
                        </p>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <div class="bg-gray-100 px-3 py-1 rounded-full text-sm text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Qty: ${itemOrder.quantity}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>`;
            itemsContainer.innerHTML += itemCard;
        });

    } catch (error) {
        console.error('Error fetching items:', error);
        document.getElementById('items-container').innerHTML = `
            <div class="bg-red-50 text-red-800 rounded-lg p-4 mb-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Error loading order details. Please try again later.</span>
                </div>
            </div>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => fetchItems(currentPage));

// Add event listeners after DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const removeOrderBtn = document.getElementById('remove-order');
    const shipOrderBtn = document.getElementById('ship-order');
    
    if (removeOrderBtn) {
        removeOrderBtn.addEventListener('click', async () => {
            if (confirm('Are you sure you want to remove this order?')) {
                try {
                    const response = await fetch(`/api/orders/delete/${order_id}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token'),
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    // Show success message before redirect
                    const successToast = document.createElement('div');
                    successToast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg';
                    successToast.textContent = 'Order successfully removed';
                    document.body.appendChild(successToast);
                    
                    setTimeout(() => {
                        window.location.href = '/usersorders';
                    }, 1000);
                } catch (error) {
                    console.error('Error cancelling order:', error);
                    alert('Failed to cancel the order.');
                }
            }
        });
    }
    
    if (shipOrderBtn) {
        shipOrderBtn.addEventListener('click', async () => {
            try {
                // Disable button and show loading state
                shipOrderBtn.disabled = true;
                shipOrderBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Processing...</span>
                `;
                
                const response = await fetch(`/api/orders/userorder/update/${order_id}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');
                
                // Show success message before redirect
                const successToast = document.createElement('div');
                successToast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg';
                successToast.textContent = 'Order marked as shipped';
                document.body.appendChild(successToast);
                
                setTimeout(() => {
                    window.location.href = '/usersorders';
                }, 1000);
            } catch (error) {
                console.error('Error shipping order:', error);
                
                // Re-enable button if there's an error
                shipOrderBtn.disabled = false;
                shipOrderBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Ship Order</span>
                `;
                
                alert('Failed to ship the order.');
            }
        });
    }
});
</script>

<script src="https://cdn.tailwindcss.com"></script>
</body>
</html>