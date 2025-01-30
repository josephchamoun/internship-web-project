<!-- filepath: /C:/laragon/www/intershipwebproject/resources/views/items/items.blade.php -->
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
                                    <form class="delete-form" data-id="${item.id}">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                        <input type="hidden" name="_method" value="DELETE">
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
                data.links.forEach(link => {
                    if (link.url) {
                        const page = new URL(link.url).searchParams.get('page');
                        const button = document.createElement('button');
                        button.textContent = link.label;
                        button.className = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600';
                        button.onclick = () => fetchItemsupplier(page);
                        paginationContainer.appendChild(button);
                    }
                });
            }

            // Add event listeners to delete forms
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const itemId = form.getAttribute('data-id');
                    const token = form.querySelector('input[name="_token"]').value;

                    try {
                        const response = await fetch(`/api/itemsupplier/delete/${itemId}`, {
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

                        // Redirect to the itemsupplier page after successful deletion
                        window.location.href = '/itemsupplier';
                    } catch (error) {
                        console.error('Error deleting item:', error);
                    }
                });
            });

        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }
    }

    // Initial fetch
    fetchItemsupplier();
</script>

    <script src="https://cdn.tailwindcss.com"></script>
</x-app-layout>