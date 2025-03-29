<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between bg-gradient-to-r from-blue-800 to-pink-800 px-6 py-4 rounded-lg shadow-md">
            <h2 class="font-bold text-2xl text-white leading-tight flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                {{ __('My Orders') }}
            </h2>
            <div class="flex space-x-2">
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1.5 rounded-full">Your Shopping History</span>
            </div>
        </div>
    </x-slot>

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Order Summary Stats -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">All Orders</p>
                        <p class="text-lg font-bold text-gray-800" id="total-orders">Loading...</p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Shipped Orders</p>
                        <p class="text-lg font-bold text-gray-800" id="shipped-orders">Loading...</p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Spent</p>
                        <p class="text-lg font-bold text-gray-800" id="total-spent">Loading...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Options -->
        <div class="bg-white rounded-xl shadow-md p-4 mb-8 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center">
                <span class="text-gray-700 font-medium mr-3">Filter:</span>
                <select id="status-filter" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    <option value="all">All Orders</option>
                    <option value="shipped">Shipped</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <!--
            <div class="flex items-center">
                <span class="text-gray-700 font-medium mr-3">Sort by:</span>
                <select id="sort-orders" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="highest">Highest Amount</option>
                    <option value="lowest">Lowest Amount</option>
                </select>
            </div>
            
            <div class="flex items-center">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="search" id="search-orders" class="block w-full p-2.5 pl-10 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search orders...">
                </div>
            </div>
-->
        </div>

        <!-- Orders Grid with Animation -->
        <div id="items-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Items will be populated by JavaScript -->
        </div>

        <!-- Enhanced Pagination -->
        <div class="mt-8 flex justify-center" id="pagination-container">
            <!-- Pagination buttons will be inserted here -->
        </div>
    </div>

    <script>
        let currentPage = 1; // Track the current page
        let allOrders = []; // Store all orders data
        let filteredOrders = []; // Store filtered orders

        async function fetchItems(page = 1) {
            try {
                const response = await fetch(`/api/orders/myorders?page=${page}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token') // Include token for authenticated requests
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                allOrders = data.data; // Store all orders
                filteredOrders = [...allOrders]; // Initialize filtered orders
                
                updateOrderStats(allOrders);
                renderOrders(data);

            } catch (error) {
                console.error('Error fetching items:', error);
                document.getElementById('items-container').innerHTML = `
                    <div class="col-span-full p-8 bg-red-50 rounded-xl text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-red-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="text-lg font-bold text-red-800">Unable to load orders</h3>
                        <p class="text-red-600 mt-2">Please check your connection and try again</p>
                        <button onclick="fetchItems(1)" class="mt-4 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Retry
                        </button>
                    </div>
                `;
            }
        }

        function renderOrders(data) {
            const itemsContainer = document.getElementById('items-container');
            const paginationContainer = document.getElementById('pagination-container');

            // Clear existing items and pagination
            itemsContainer.innerHTML = '';
            paginationContainer.innerHTML = '';

            // Show message if no orders
            if (data.data.length === 0) {
                itemsContainer.innerHTML = `
                    <div class="col-span-full p-8 bg-gray-50 rounded-xl text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="text-lg font-bold text-gray-800">No orders found</h3>
                        <p class="text-gray-600 mt-2">You haven't placed any orders yet</p>
                        <a href="/dashboard" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Start Shopping
                        </a>
                    </div>
                `;
                return;
            }

            // Populate items with animation
            data.data.forEach((order, index) => {
                const orderDate = new Date(order.updated_at);
                const formattedDate = orderDate.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
                
                const statusClass = order.status === "shipped" 
                    ? "bg-green-100 text-green-800" 
                    : "bg-yellow-100 text-yellow-800";
                
                const statusLabel = order.status === "shipped" 
                    ? `<span class="${statusClass} text-xs font-semibold px-2.5 py-0.5 rounded-full">Shipped</span>` 
                    : `<span class="${statusClass} text-xs font-semibold px-2.5 py-0.5 rounded-full">Pending</span>`;

                // Format currency
                const formatter = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                });
                
                const formattedAmount = formatter.format(order.total_amount);

                const itemCard = `
                    <div class="bg-white shadow-md hover:shadow-lg transition-all duration-300 rounded-xl overflow-hidden animate-fadeIn" style="animation-delay: ${index * 100}ms">
                        <div class="p-5 border-b">
                            <div class="flex justify-between items-center">
                                <h5 class="font-bold text-gray-800 text-lg">Order #${order.id}</h5>
                                ${statusLabel}
                            </div>
                        </div>
                        
                        <div class="p-5">
                            <div class="flex items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <p class="text-gray-700">${order.user.name}</p>
                            </div>
                            
                            <div class="flex items-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-blue-600 font-semibold">${formattedAmount}</p>
                            </div>
                            
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-600 text-sm">${formattedDate}</p>
                            </div>
                            
                            <a href="/myorder/details/${order.id}?status=${order.status}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg text-center transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                `;
                itemsContainer.innerHTML += itemCard;
            });

            // Create enhanced pagination
            const paginationWrapper = document.createElement('div');
            paginationWrapper.className = 'inline-flex items-center space-x-2 bg-white rounded-lg shadow-md p-1';
            
            // First page button
            const firstPageBtn = document.createElement('button');
            firstPageBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            `;
            firstPageBtn.className = 'p-2 hover:bg-blue-50 rounded text-gray-500 hover:text-blue-600 transition-colors';
            firstPageBtn.disabled = data.current_page === 1;
            firstPageBtn.onclick = () => fetchItems(1);
            
            // Previous button
            const prevButton = document.createElement('button');
            prevButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            `;
            prevButton.className = 'p-2 hover:bg-blue-50 rounded text-gray-500 hover:text-blue-600 transition-colors';
            prevButton.disabled = !data.prev_page_url;
            prevButton.onclick = () => fetchItems(data.current_page - 1);
            
            // Current page indicator
            const pageIndicator = document.createElement('span');
            pageIndicator.textContent = `Page ${data.current_page} of ${data.last_page}`;
            pageIndicator.className = 'px-4 py-2 text-sm font-medium text-gray-700';
            
            // Next button
            const nextButton = document.createElement('button');
            nextButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            `;
            nextButton.className = 'p-2 hover:bg-blue-50 rounded text-gray-500 hover:text-blue-600 transition-colors';
            nextButton.disabled = !data.next_page_url;
            nextButton.onclick = () => fetchItems(data.current_page + 1);
            
            // Last page button
            const lastPageBtn = document.createElement('button');
            lastPageBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0zm6 0a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            `;
            lastPageBtn.className = 'p-2 hover:bg-blue-50 rounded text-gray-500 hover:text-blue-600 transition-colors';
            lastPageBtn.disabled = data.current_page === data.last_page;
            lastPageBtn.onclick = () => fetchItems(data.last_page);

            // Add buttons to pagination wrapper
            paginationWrapper.appendChild(firstPageBtn);
            paginationWrapper.appendChild(prevButton);
            paginationWrapper.appendChild(pageIndicator);
            paginationWrapper.appendChild(nextButton);
            paginationWrapper.appendChild(lastPageBtn);
            
            // Add pagination wrapper to container
            paginationContainer.appendChild(paginationWrapper);
        }
        
        function updateOrderStats(orders) {
            // Count shipped orders
            const shippedCount = orders.filter(order => order.status === "shipped").length;
            
            // Calculate total spent
            const totalSpent = orders.reduce((sum, order) => sum + parseFloat(order.total_amount), 0);
            
            // Update stats
            document.getElementById('total-orders').textContent = orders.length;
            document.getElementById('shipped-orders').textContent = shippedCount;
            document.getElementById('total-spent').textContent = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
            }).format(totalSpent);
        }

        // Filter functionality
        document.getElementById('status-filter')?.addEventListener('change', function() {
            const filterValue = this.value;
            
            if (filterValue === 'all') {
                filteredOrders = [...allOrders];
            } else {
                filteredOrders = allOrders.filter(order => order.status === filterValue);
            }
            
            // Create a mock data object for rendering
            const mockData = {
                data: filteredOrders,
                current_page: 1,
                last_page: 1,
                prev_page_url: null,
                next_page_url: null
            };
            
            renderOrders(mockData);
            updateOrderStats(filteredOrders);
        });

        // Add animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fadeIn {
                animation: fadeIn 0.3s ease-out forwards;
                opacity: 0;
            }
        `;
        document.head.appendChild(style);

        // Load items when the page loads
        document.addEventListener('DOMContentLoaded', () => fetchItems(currentPage));
    </script>
</x-app-layout>