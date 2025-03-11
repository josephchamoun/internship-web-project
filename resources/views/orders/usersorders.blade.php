<x-app-layout>
<x-slot name="header">
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Users Orders') }}
        </h2>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-white">Quick status filter:</span>
            <button onclick="fetchItems(1, 'all')" class="px-3 py-1 text-sm rounded-full bg-gray-200 hover:bg-gray-300 transition">All</button>
            <button onclick="fetchItems(1, 'pending')" class="px-3 py-1 text-sm rounded-full bg-gray-200 hover:bg-gray-300 transition">Pending</button>
            <button onclick="fetchItems(1, 'shipped')" class="px-3 py-1 text-sm rounded-full bg-green-100 hover:bg-green-200 transition">Shipped</button>
        </div>
    </div>
</x-slot>

<div class="container mx-auto px-4 py-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Total Orders</p>
                    <h3 id="total-orders" class="text-2xl font-bold text-gray-800">--</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Pending Orders</p>
                    <h3 id="pending-orders" class="text-2xl font-bold text-gray-800">--</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Shipped Orders</p>
                    <h3 id="shipped-orders" class="text-2xl font-bold text-gray-800">--</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Grid -->
    <div id="items-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
        <!-- Items will be populated by JavaScript -->
    </div>

    <!-- Empty State (will be hidden when there are items) -->
    <div id="empty-state" class="hidden flex flex-col items-center justify-center py-12 text-center bg-white rounded-lg shadow mt-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900">No orders found</h3>
        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
    </div>

    <!-- Pagination with page numbers -->
    <div class="mt-8 flex justify-between items-center" id="pagination-container">
        <!-- Pagination will be populated by JavaScript -->
    </div>
</div>

<script>
    let currentPage = 1; // Track the current page
    let currentStatus = 'all'; // Track the current status filter

    async function fetchItems(page = 1, status = 'all') {
        currentPage = page;
        currentStatus = status;

        try {
            const response = await fetch(`/api/orders?page=${page}&status=${status}`, {
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
            const paginationContainer = document.getElementById('pagination-container');
            const emptyState = document.getElementById('empty-state');

            // Update stats overview
            document.getElementById('total-orders').textContent = data.totalOrders;
            document.getElementById('pending-orders').textContent = data.pendingOrders;
            document.getElementById('shipped-orders').textContent = data.shippedOrders;

            // Clear existing items and pagination
            itemsContainer.innerHTML = '';
            paginationContainer.innerHTML = '';

            // Show/hide empty state
            if (data.orders.data.length === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }

            // Populate items
            data.orders.data.forEach(order => {
                // Format date to be more readable
                const orderDate = new Date(order.created_at);
                const formattedDate = orderDate.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });

                const statusClasses = {
                    'shipped': 'bg-green-100 text-green-800',
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'cancelled': 'bg-red-100 text-red-800',
                    'default': 'bg-gray-100 text-gray-800'
                };

                const statusClass = statusClasses[order.status] || statusClasses.default;
                const statusLabel = `<span class="${statusClass} text-xs font-semibold px-2.5 py-0.5 rounded capitalize">${order.status}</span>`;

                const itemCard = `
                <div class="bg-white shadow rounded-lg overflow-hidden transition-all hover:shadow-md">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-3">
                            <h5 class="font-bold text-lg text-gray-900">#${order.id}</h5>
                            ${statusLabel}
                        </div>
                        <div class="mb-3">
                            <div class="flex items-center gap-1 text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-medium">${order.user.name}</span>
                            </div>
                            <div class="flex items-center gap-1 text-gray-700 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm">${formattedDate}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-3">
                            <div class="bg-blue-50 px-3 py-1 rounded-full">
                                <span class="text-blue-800 font-semibold">$${parseFloat(order.total_amount).toFixed(2)}</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="/userorder/details/${order.id}?status=${order.status}" class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                `;
                
                itemsContainer.innerHTML += itemCard;
            });

            // Enhanced pagination
            const paginationWrapper = document.createElement('div');
            paginationWrapper.className = 'flex items-center space-x-2';
            
            // Previous button
            const prevButton = document.createElement('button');
            prevButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>';
            prevButton.className = data.orders.prev_page_url 
                ? 'flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50' 
                : 'flex items-center justify-center w-10 h-10 rounded-md border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed';
            prevButton.disabled = !data.orders.prev_page_url;
            prevButton.onclick = () => fetchItems(data.orders.current_page - 1, currentStatus);
            paginationWrapper.appendChild(prevButton);
            
            // Page numbers
            const totalPages = data.orders.last_page;
            let startPage = Math.max(1, data.orders.current_page - 2);
            let endPage = Math.min(totalPages, data.orders.current_page + 2);
            
            // Always show at least 5 pages if available
            if (endPage - startPage + 1 < 5 && totalPages > 4) {
                if (data.orders.current_page < 3) {
                    endPage = Math.min(5, totalPages);
                } else if (data.orders.current_page > totalPages - 2) {
                    startPage = Math.max(1, totalPages - 4);
                }
            }
            
            // First page
            if (startPage > 1) {
                const firstPageBtn = document.createElement('button');
                firstPageBtn.textContent = '1';
                firstPageBtn.className = 'flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50';
                firstPageBtn.onclick = () => fetchItems(1, currentStatus);
                paginationWrapper.appendChild(firstPageBtn);
                
                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'flex items-center justify-center w-10 h-10';
                    paginationWrapper.appendChild(ellipsis);
                }
            }
            
            // Page numbers
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = i.toString();
                pageBtn.className = i === data.orders.current_page
                    ? 'flex items-center justify-center w-10 h-10 rounded-md border border-blue-500 bg-blue-500 text-white'
                    : 'flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50';
                pageBtn.onclick = () => fetchItems(i, currentStatus);
                paginationWrapper.appendChild(pageBtn);
            }
            
            // Last page
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'flex items-center justify-center w-10 h-10';
                    paginationWrapper.appendChild(ellipsis);
                }
                
                const lastPageBtn = document.createElement('button');
                lastPageBtn.textContent = totalPages.toString();
                lastPageBtn.className = 'flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50';
                lastPageBtn.onclick = () => fetchItems(totalPages, currentStatus);
                paginationWrapper.appendChild(lastPageBtn);
            }
            
            // Next button
            const nextButton = document.createElement('button');
            nextButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';
            nextButton.className = data.orders.next_page_url 
                ? 'flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50' 
                : 'flex items-center justify-center w-10 h-10 rounded-md border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed';
            nextButton.disabled = !data.orders.next_page_url;
            nextButton.onclick = () => fetchItems(data.orders.current_page + 1, currentStatus);
            paginationWrapper.appendChild(nextButton);
            
            // Page info
            const pageInfo = document.createElement('div');
            pageInfo.className = 'text-sm text-gray-500';
            pageInfo.textContent = `Showing page ${data.orders.current_page} of ${totalPages}`;
            
            paginationContainer.appendChild(paginationWrapper);
            paginationContainer.appendChild(pageInfo);

        } catch (error) {
            console.error('Error fetching items:', error);
            document.getElementById('items-container').innerHTML = `
            <div class="col-span-full py-8 text-center">
                <p class="text-red-500">An error occurred while loading orders. Please try again.</p>
            </div>`;
        }
    }

    // Load items when the page loads
    document.addEventListener('DOMContentLoaded', () => fetchItems(currentPage, currentStatus));
</script>

<script src="https://cdn.tailwindcss.com"></script>
</x-app-layout>