<x-app-layout>
<x-slot name="header">
    <div class="flex items-center space-x-2 justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users Orders') }}
        </h2>

    </div>
</x-slot>

<div class="container mx-auto px-4">
    <!-- Responsive Grid -->
    <div id="items-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-6">
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
        try {
            const response = await fetch(`/api/orders?page=${page}`, {
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
                data.data.forEach(order => {
                const itemCard = `
<div class="bg-white shadow rounded-lg p-4">

    <div class="mt-4">
        <h5 class="font-semibold text-lg">#${order.id}</h5>
        <h5 class="font-semibold text-lg">${order.user.name}</h5>
       
        <p class="text-gray-600 mt-2">${order.total_amount} $</p>
        <h5 class="font-semibold text-lg">${order.updated_at}</h5>
        
        <div class="mt-4 flex gap-2">
            <a href="/userorder/details/${order.id}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Details</a>
       
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

    // Load items when the page loads
    document.addEventListener('DOMContentLoaded', () => fetchItems(currentPage));
</script>

<script src="https://cdn.tailwindcss.com"></script>
</x-app-layout>