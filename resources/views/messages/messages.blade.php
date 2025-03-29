<x-app-layout>
<x-slot name="header">
    <div class="flex items-center space-x-2 justify-between">
        <h2 class="font-semibold text-xl md:text-2xl text-gray-800 leading-tight">
            {{ __('Messages') }}
        </h2>
    </div>
</x-slot>

<div class="container mx-auto px-4 py-6">
    <!-- Search Bar -->
    <form id="searchForm" class="mb-6 w-full md:w-1/2 lg:w-1/3 ml-auto">
        <div class="flex items-center space-x-2 shadow-sm">
            <div class="relative flex-grow">
                <input 
                    type="text" 
                    id="searchInput" 
                    name="search" 
                    placeholder="Search messages by subject" 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <button 
                type="submit" 
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200"
            >
                Search
            </button>
        </div>
    </form>

    <!-- Responsive Grid -->
    <div id="items-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        <!-- Items will be populated by JavaScript -->
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center space-x-4" id="pagination-container">
        <!-- Pagination buttons will be inserted here -->
    </div>
</div>

<script>
    let currentPage = 1; // Track the current page

    async function fetchItems(page = 1, query = '') {
        try {
            const response = await fetch(`/api/messages?page=${page}&search=${query}`, {
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
            data.data.forEach(message => {
                const itemCard = `
                    <div class="bg-white shadow-md rounded-lg p-5 transform hover:scale-105 transition duration-300 ease-in-out">
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a4 4 0 00-4-4H4a2 2 0 00-2 2v2a2 2 0 002 2h4a4 4 0 004-4zm0 0V6a4 4 0 114 4v-4m-8 5a4 4 0 004 4h4a2 2 0 002-2v-2a2 2 0 00-2-2h-4a4 4 0 01-4-4z"></path>
                                </svg>
                                <h5 class="font-semibold text-lg text-gray-800">${message.user.name}</h5>
                            </div>
                            <div class="border-t pt-3">
                                <p class="text-sm text-gray-600 mb-1"><strong>Subject:</strong> ${message.subject}</p>
                                <p class="text-sm text-gray-500">${message.message}</p>
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
                button.className = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition duration-200';
                button.disabled = !url;
                button.onclick = () => fetchItems(page, query);
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

    // Load items when the page loads
    document.addEventListener('DOMContentLoaded', () => fetchItems(currentPage));

    // Handle search form submission
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const query = document.getElementById('searchInput').value;
        fetchItems(1, query);
    });
</script>

<script src="https://cdn.tailwindcss.com"></script>
</x-app-layout>