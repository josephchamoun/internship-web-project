<x-app-layout>

<x-slot name="header">
    <div class="bg-gradient-to-r from-blue-300 to-pink-300 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 justify-between bg-white p-4 rounded-lg shadow-md w-full mx-0">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>

        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 w-full bg-gradient-to-r from-blue-300 to-pink-300">
            <!-- Gender Filter -->
            <div class="flex items-center space-x-2 w-full md:w-1/3">
                <i class="fas fa-venus-mars text-gray-500"></i>
                <select id="gender_filter" name="gender_filter" class="border-gray-300 rounded-md shadow-sm p-2 md:p-4 w-full focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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
                <i class="fas fa-birthday-cake text-gray-500"></i>
                <select id="age_filter" name="age_filter" class="border-gray-300 rounded-md shadow-sm p-2 md:p-4 w-full focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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
                <i class="fas fa-list text-gray-500"></i>
                <select id="category_filter" name="category_filter" class="border-gray-300 rounded-md shadow-sm p-2 md:p-4 w-full focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Categories</option>
                    <!-- Categories will be populated by JavaScript -->
                </select>
            </div>

            @if (Auth::check() && Auth::user()->role === 'Manager')
                <a href="/additem" class="bg-indigo-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-600 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Add Item
                </a>
            @endif
        </div>
    </div>
</x-slot>

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
            class="bg-blue-500 text-white p-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200"
        >
            Search
        </button>
    </div>
</form>

    <!-- Responsive Grid -->
    <div id="items-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Items will be populated by JavaScript -->
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-between" id="pagination-container">
        <!-- Pagination buttons will be inserted here -->
    </div>
</div>

<script>
    let currentPage = 1; // Track the current page

    async function fetchItems(page = 1) {
        const query = document.getElementById('searchInput').value;
        const age = document.getElementById('age_filter').value;
        const gender = document.getElementById('gender_filter').value;
        const category = document.getElementById('category_filter').value;

        try {
            const response = await fetch(`/api/dashboard?page=${page}&search=${query}&age=${age}&gender=${gender}&category=${category}`, {
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

            // Clear existing items and pagination
            itemsContainer.innerHTML = '';
            paginationContainer.innerHTML = '';

           // Populate items
           data.data.forEach(item => {
                const itemCard = `
                    <div class="bg-white shadow rounded-lg p-4">
                                   <figure>
                                        <img src="${item.image_url ? '/storage/' + item.image_url : '/path/to/default-image.jpg'}" alt="${item.name}" class="w-full rounded-lg" />
                                    </figure>
                        <div class="mt-4">
                            <h5 class="font-semibold text-lg">${item.name}</h5>
                            <p class="text-gray-600 mt-2">${item.description}</p>
                            <p class="text-gray-600 mt-2">${item.price} $</p>
                            <p class="text-gray-600 mt-2">${item.quantity} left</p>
                             ${item.quantity == 0 ? '<p class="text-red-400 font-semibold">Out of stock</p>' : ''}
                            <div class="mt-4 flex flex-col gap-2">
                                <form action="/cart/add/${item.id}" method="POST">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                    <input type="number" name="quantity" min="1" max="${item.quantity}" value="1" class="border rounded px-2 py-1 w-16">
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add to cart</button>
                                </form>
                                
                                    @if (Auth::check() && Auth::user()->role === 'Manager')
                                        <a href="/items/${item.id}/edit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center w-max">
                                            <i class="fas fa-edit mr-2"></i> Edit
                                        </a>
                                          <form action="api/items/delete/${item.id}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 flex items-center w-max">
                                        <i class="fas fa-trash-alt mr-2"></i> Delete
                                    </button>
                                </form>
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
                button.textContent = text;
                button.className = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600';
                button.disabled = !url;
                button.onclick = () => fetchItems(page);
                return button;
            };

            // Add "Previous" button
            const prevButton = createPaginationButton('Previous', data.prev_page_url, data.current_page - 1);

            // Add "Next" button
            const nextButton = createPaginationButton('Next', data.next_page_url, data.current_page + 1);

            paginationContainer.appendChild(prevButton);
            paginationContainer.appendChild(nextButton);

        } catch (error) {
            console.error('Error fetching items:', error);
        }
    }

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