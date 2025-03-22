<x-app-layout>

<x-slot name="header">
    <div class="bg-gradient-to-r from-blue-300 to-pink-300 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 justify-between p-4 rounded-lg shadow-md w-full mx-0">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Epic Toy Store') }}
        </h2>
        @if (session('token'))
            <script>
                localStorage.setItem('token', '{{ session('token') }}');
            </script>
        @endif

        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 w-full bg-gradient-to-r from-blue-300 to-pink-300">
            <!-- Gender Filter -->
            <div class="flex items-center space-x-2 w-full md:w-1/3">
                <i class="fas fa-venus-mars text-gray-700"></i>
                <select id="gender_filter" name="gender_filter" class="border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Genders</option>
                    <option value="male">
                        <i class="fas fa-mars text-blue-900"></i> Male
                    </option>
                    <option value="female">
                        <i class="fas fa-venus text-pink-900"></i> Female
                    </option>
                </select>
            </div>

            <!-- Age Filter -->
            <div class="flex items-center space-x-2 w-full md:w-1/3">
                <i class="fas fa-birthday-cake text-gray-700"></i>
                <select id="age_filter" name="age_filter" class="border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Ages</option>
                    <option value="0-3">0-3</option>
                    <option value="3-6">3-6</option>
                    <option value="6-9">6-9</option>
                    <option value="9-12">9-12</option>
                    <option value="13-17">13-17</option>
                    <option value="18+">18+</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div class="flex items-center space-x-2 w-full md:w-1/3">
                <i class="fas fa-list text-gray-700"></i>
                <select id="category_filter" name="category_filter" class="border-gray-300 rounded-md shadow-sm p-2 w-full focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Categories</option>
                    <!-- Categories will be populated by JavaScript -->
                </select>
            </div>

            @if (Auth::check() && Auth::user()->role === 'Manager')
                <a href="/additem" class="bg-indigo-500 text-white px-3 py-2 rounded-md shadow-sm hover:bg-indigo-600 flex items-center transition-colors">
                    <i class="fas fa-plus"></i>
                </a>
            @endif
        </div>
    </div>
</x-slot>

<!-- Toast Notification -->
<div id="toast-notification" class="fixed right-4 top-20 bg-white rounded-lg shadow-lg max-w-sm w-full transform translate-x-full transition-transform duration-300 z-50 overflow-hidden">
    <div class="p-4 flex items-start">
        <div class="flex-shrink-0 bg-green-100 rounded-full p-2">
            <i class="fas fa-check text-green-500"></i>
        </div>
        <div class="ml-3 w-0 flex-1">
            <p class="font-medium text-gray-900" id="toast-title"></p>
            <p class="mt-1 text-sm text-gray-500" id="toast-message"></p>
            <div class="mt-2 flex space-x-3">
                <button id="view-cart-btn" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    View Cart
                </button>
                <button id="dismiss-toast-btn" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Dismiss
                </button>
            </div>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
            <button id="close-toast-btn" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <span class="sr-only">Close</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- Progress bar -->
    <div id="toast-progress" class="h-1 bg-green-500 w-full" style="transition: width 3s linear;"></div>
</div>

<div class="container mx-auto px-4">
    <!-- Search Bar -->
    <form id="searchForm" class="mb-4 w-full md:w-1/3 ml-auto">
        <div class="flex items-center space-x-2">
            <input 
                type="text" 
                id="searchInput" 
                name="search" 
                placeholder="Search items by name" 
                class="border border-gray-300 rounded-lg p-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <button 
                type="submit" 
                class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition duration-200"
            >
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <!-- Responsive Grid -->
    <div id="items-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Items will be populated by JavaScript -->
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center gap-4" id="pagination-container">
        <!-- Pagination buttons will be inserted here -->
    </div>
</div>

<script>
    let currentPage = 1; // Track the current page
    let toastTimeout; // For tracking the toast timeout

    // Function to show toast notification
    function showToast(title, message, duration = 3000) {
        const toast = document.getElementById('toast-notification');
        const toastTitle = document.getElementById('toast-title');
        const toastMessage = document.getElementById('toast-message');
        const toastProgress = document.getElementById('toast-progress');
        
        // Set content
        toastTitle.textContent = title;
        toastMessage.textContent = message;
        
        // Show toast
        toast.classList.remove('translate-x-full');
        toast.classList.add('translate-x-0');
        
        // Reset and start progress bar
        toastProgress.style.width = '100%';
        setTimeout(() => {
            toastProgress.style.width = '0%';
        }, 50);
        
        // Hide toast after duration
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            hideToast();
        }, duration);
    }
    
    // Function to hide toast notification
    function hideToast() {
        const toast = document.getElementById('toast-notification');
        toast.classList.remove('translate-x-0');
        toast.classList.add('translate-x-full');
    }

    async function fetchItems(page = 1) {
        const query = document.getElementById('searchInput').value;
        const age = document.getElementById('age_filter').value;
        const gender = document.getElementById('gender_filter').value;
        const category = document.getElementById('category_filter').value;

        try {
            const response = await fetch(`/api/dashboard?page=${page}&search=${query}&age=${age}&gender=${gender}&category=${category}`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'), // Include token for authenticated requests
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            const itemsContainer = document.getElementById('items-container');
            const paginationContainer = document.getElementById('pagination-container');

            // Clear existing items and pagination
            itemsContainer.innerHTML = '';
            paginationContainer.innerHTML = '';

           // Populate items
           data.data.forEach(item => {
                const itemCard = `
                    <div class="bg-white shadow hover:shadow-md transition-shadow duration-300 rounded-lg overflow-hidden">
                        <div class="p-4">
                            <h5 class="font-semibold text-lg">${item.name}</h5>
                            <p class="text-gray-600 mt-2 text-sm">${item.description}</p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="font-bold text-blue-600">$${item.price}</span>
                                <span class="text-gray-500 text-sm">${item.quantity} left</span>
                            </div>
                            ${item.quantity == 0 ? '<p class="text-red-500 font-semibold text-sm mt-1">Out of stock</p>' : ''}
                            
                            <div class="mt-3 flex justify-between items-center">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="quantity_${item.id}" min="1" max="${item.quantity}" value="1" class="border rounded px-2 py-1 w-12 text-sm">
                                    <button onclick="addToCart(${item.id}, '${item.name}', ${item.price}, ${item.quantity})" class="bg-blue-500 text-white px-2 py-1 rounded text-sm hover:bg-blue-600 transition-colors">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                                
                                @if (Auth::check() && Auth::user()->role === 'Manager')
                                <div class="flex items-center space-x-1">
                                    <a href="/items/${item.id}/edit" class="bg-blue-500 text-white p-1 rounded hover:bg-blue-600 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form class="delete-form inline" data-id="${item.id}">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="bg-red-500 text-white p-1 rounded hover:bg-red-600 transition-colors">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                `;
                itemsContainer.innerHTML += itemCard;
            });

            // Pagination buttons
            const createPaginationButton = (text, url, page) => {
                const button = document.createElement('button');
                button.innerHTML = text.includes('Previous') ? '<i class="fas fa-chevron-left"></i>' : '<i class="fas fa-chevron-right"></i>';
                button.className = 'bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 transition-colors';
                button.disabled = !url;
                if (!url) {
                    button.className += ' opacity-50 cursor-not-allowed';
                }
                button.onclick = () => fetchItems(page);
                return button;
            };

            // Add page indicator
            const pageIndicator = document.createElement('span');
            pageIndicator.textContent = `Page ${data.current_page} of ${data.last_page}`;
            pageIndicator.className = 'text-gray-700 px-3 py-2';

            // Add "Previous" button
            const prevButton = createPaginationButton('Previous', data.prev_page_url, data.current_page - 1);

            // Add "Next" button
            const nextButton = createPaginationButton('Next', data.next_page_url, data.current_page + 1);

            paginationContainer.appendChild(prevButton);
            paginationContainer.appendChild(pageIndicator);
            paginationContainer.appendChild(nextButton);

            // Add event listeners to delete forms
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const itemId = form.getAttribute('data-id');
                    const token = form.querySelector('input[name="_token"]').value;

                    try {
                        const response = await fetch(`/api/items/delete/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Authorization': 'Bearer ' + localStorage.getItem('token')
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        // Redirect to the dashboard page after successful deletion
                        window.location.href = '/dashboard';
                    } catch (error) {
                        console.error('Error deleting item:', error);
                    }
                });
            });

        } catch (error) {
            console.error('Error fetching items:', error);
        }
    }

    function addToCart(id, name, price, maxQuantity) {
        let quantityInput = document.getElementById(`quantity_${id}`);
        let quantity = parseInt(quantityInput.value);

        if (quantity < 1 || quantity > maxQuantity) {
            showToast('Error', 'Invalid quantity selected!', 3000);
            return;
        }

        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        let existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            let newQuantity = existingItem.quantity + quantity;
            if (newQuantity > maxQuantity) {
                newQuantity = maxQuantity;
                showToast('Item Added to Cart', `Added ${name} to cart (max quantity reached)`, 3000);
            } else {
                showToast('Item Added to Cart', `Added ${quantity} ${name} to cart (total: ${newQuantity})`, 3000);
            }
            existingItem.quantity = newQuantity;
        } else {
            cart.push({ id, name, price, quantity });
            showToast('Item Added to Cart', `Added ${quantity} ${name} to cart`, 3000);
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Update cart button if it exists
        updateCartCount();
    }

    function updateCartCount() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        
        // If you have a cart count element, update it here
        // For example: document.getElementById('cart-count').textContent = totalItems;
    }

    function loadCart() {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        console.log("Cart:", cart);
        updateCartCount();
    }

    document.addEventListener("DOMContentLoaded", () => {
        loadCart();
        
        // Set up toast event listeners
        document.getElementById('close-toast-btn').addEventListener('click', hideToast);
        document.getElementById('dismiss-toast-btn').addEventListener('click', hideToast);
        document.getElementById('view-cart-btn').addEventListener('click', () => {
            // Redirect to cart page or open cart modal
            // For now, just hide the toast
            
           window.location.href = '/cart';
           hideToast();
        });
    });

    async function fetchCategories() {
        try {
            const response = await fetch('/api/categories', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'), // Include token for authenticated requests
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            const categoryFilter = document.getElementById('category_filter');

            // Check if data is an array or an object with a data property
            const categories = Array.isArray(data) ? data : data.data;

            // Populate category filter
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categoryFilter.appendChild(option);
            });

        } catch (error) {
            console.error('Error fetching categories:', error);
        }
    }

    // Load items and categories when the page loads
    document.addEventListener('DOMContentLoaded', () => {
        fetchItems(currentPage);
        fetchCategories();
    });

    // Handle search form submission
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault();
        fetchItems(1);
    });

    // Handle filter changes
    document.getElementById('age_filter').addEventListener('change', () => fetchItems(1));
    document.getElementById('gender_filter').addEventListener('change', () => fetchItems(1));
    document.getElementById('category_filter').addEventListener('change', () => fetchItems(1));
</script>

<script src="https://cdn.tailwindcss.com"></script>
</x-app-layout>