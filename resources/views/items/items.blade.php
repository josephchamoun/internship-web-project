<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Supplies') }}
            </h2>
            <!-- Button to Add Manager -->
            @if (Auth::check() && Auth::user()->role === 'Manager')
            <x-add-button url="/addsupply">
                Add Supply
            </x-add-button>
            @endif
        </div>
    </x-slot>

    <div class="container mx-auto px-4">
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

        async function fetchItemsupplier(page = 1) {
            try {
                const response = await fetch(`/api/itemsupplier?page=${page}`, {
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
                const itemsContainer = document.getElementById('items-container');

                // Clear the existing items (if any)
                itemsContainer.innerHTML = '';

                // Populate the items
                data.data.forEach(item => { // `data.data` contains the paginated items
                    const itemCard = `
                        <div class="bg-white shadow rounded-lg p-4">

                            <div class="mt-4">
                                <h5 class="font-semibold text-lg">Item name: ${item.item.name}</h5>
                                <h5 class="font-semibold text-lg">Supplier name: ${item.supplier.name}</h5>
                                <p class="text-gray-600 mt-2">Quantity: ${item.quantity}</p>
                                <p class="text-gray-600 mt-2">Buy Price: ${item.buyprice} $</p>
                                <p class="text-gray-600 mt-2">Supplied At: ${item.created_at}</p>
  <div class="mt-4 flex gap-2">
            @if (Auth::check() && Auth::user()->role === 'Manager')
                <a href="/itemsupplier/${item.id}/edit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <form action="/itemsupplier/${item.id}" method="POST" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                    @csrf
                    @method('DELETE')
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

                // Handle pagination (Next and Previous buttons)
                const paginationContainer = document.getElementById('pagination-container');
                paginationContainer.innerHTML = '';

                if (data.links) {
                    const prevLink = data.links.find(link => link.label === 'Previous');
                    const nextLink = data.links.find(link => link.label === 'Next');

                    if (prevLink && prevLink.url) {
                        const prevPage = new URL(prevLink.url).searchParams.get('page');
                        const prevButton = document.createElement('button');
                        prevButton.textContent = 'Previous';
                        prevButton.className = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600';
                        prevButton.onclick = () => fetchItemsupplier(prevPage);
                        paginationContainer.appendChild(prevButton);
                    }

                    if (nextLink && nextLink.url) {
                        const nextPage = new URL(nextLink.url).searchParams.get('page');
                        const nextButton = document.createElement('button');
                        nextButton.textContent = 'Next';
                        nextButton.className = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600';
                        nextButton.onclick = () => fetchItemsupplier(nextPage);
                        paginationContainer.appendChild(nextButton);
                    }
                }
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        }

        // Initial fetch
        fetchItemsupplier();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
</x-app-layout>