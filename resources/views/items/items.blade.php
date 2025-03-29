<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Supplies') }}
            </h2>
            @if (Auth::check() && Auth::user()->role === 'Manager')
            <x-add-button url="/addsupply">
                Add Supply
            </x-add-button>
            @endif
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
                    placeholder="Search supplies by item name" 
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
    let currentPage = 1;
    let currentQuery = '';

    async function fetchItemsupplier(page = 1, query = '') {
        try {
            const response = await fetch(`/api/itemsupplier?page=${page}&search=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'

                    
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            renderItems(data);
            renderPagination(data);
            setupDeleteListeners();

        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }
    }

    function renderItems(data) {
        const itemsContainer = document.getElementById('items-container');
        itemsContainer.innerHTML = '';

        data.data.forEach(item => {
            const itemCard = `
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="mt-4">
                        <h5 class="font-semibold text-lg">Item: ${item.item.name}</h5>
                        <h5 class="font-semibold text-lg">Supplier: ${item.supplier.name}</h5>
                        <p class="text-gray-600 mt-2">Quantity: ${item.quantity}</p>
                        <p class="text-gray-600 mt-2">Price: $${item.buyprice}</p>
                        <p class="text-gray-600 mt-2">Supplied: ${item.created_at}</p>
                        <div class="mt-4 flex gap-2">
                            @if (Auth::check() && Auth::user()->role === 'Manager')
                                <a href="/itemsupplier/${item.id}/edit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
                                    <i class="fas fa-edit mr-2"></i> Edit
                                </a>
                                <form class="delete-form" data-id="${item.id}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 flex items-center">
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
    }

    function renderPagination(data) {
        const paginationContainer = document.getElementById('pagination-container');
        paginationContainer.innerHTML = '';

        const createPaginationButton = (text, url, page) => {
            const button = document.createElement('button');
            button.textContent = text;
            button.className = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600';
            button.disabled = !url;
            button.onclick = () => fetchItemsupplier(page, currentQuery);
            return button;
        };

        // Previous Button
        const prevButton = createPaginationButton(
            'Previous',
            data.prev_page_url,
            data.current_page - 1
        );

        // Next Button
        const nextButton = createPaginationButton(
            'Next',
            data.next_page_url,
            data.current_page + 1
        );

        paginationContainer.appendChild(prevButton);
        paginationContainer.appendChild(nextButton);
    }

    function setupDeleteListeners() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const itemId = form.getAttribute('data-id');
                
                try {
                    const response = await fetch(`/api/itemsupplier/delete/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        }
                    });

                    if (response.ok) {
                        fetchItemsupplier(currentPage, currentQuery);
                    }
                } catch (error) {
                    console.error('Error deleting item:', error);
                }
            });
        });
    }

    // Initial load
    fetchItemsupplier();

    // Search handler
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault();
        currentQuery = document.getElementById('searchInput').value;
        fetchItemsupplier(1, currentQuery);
    });
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</x-app-layout>