<x-app-layout>
<x-slot name="header">
    <div class="flex items-center space-x-2 justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>

        @if (Auth::check() && Auth::user()->role === 'Manager')
        <x-add-button url="/addmanager">
            Add New Manager
        </x-add-button>
        @endif
    </div>
</x-slot>

<div class="container mx-auto px-4">
    <!-- Pass authentication data to JavaScript -->
    <script>
        const isAuthenticated = @json(Auth::check());
        const userRole = @json(Auth::check() ? Auth::user()->role : null);
    </script>

    <!-- Search Bar -->
    <form id="searchForm" class="mb-4 w-full md:w-1/3 ml-auto">
        <div class="flex items-center space-x-2">
            <input 
                type="text" 
                id="searchInput" 
                name="search" 
                placeholder="Search users by name" 
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
    <div id="items-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        <!-- Items will be populated by JavaScript -->
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-between" id="pagination-container">
        <!-- Pagination buttons will be inserted here -->
    </div>
</div>

<script>
    let currentPage = 1; // Track the current page

async function fetchItems(page = 1, query = '') {
    try {
        const response = await fetch(`/api/users?page=${page}&search=${query}`, {
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
        data.data.forEach(user => {
            const itemCard = `
               <div class="bg-white shadow rounded-lg p-6 mb-4">
            <div class="flex items-center">
                <img src="${user.profile_photo}" alt="${user.name}" class="w-16 h-16 rounded-full mr-4">
                <div class="flex-1">
                    <h5 class="font-semibold text-lg">${user.name}</h5>
                    <p class="text-gray-600">Email: ${user.email}</p>
                    <p class="text-gray-600">Role: ${user.role}</p>
                </div>
                <div class="ml-auto mt-4 flex gap-2">
                    @if (Auth::check() && Auth::user()->role === 'Manager')
                        <form action="api/users/${user.id}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
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

        // Pagination buttons
        const createPaginationButton = (text, url, page) => {
            const button = document.createElement('button');
            button.textContent = text;
            button.className = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600';
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

document.addEventListener('submit', async function (event) {
    if (event.target.matches('form[action^="/api/users/"]')) {
        event.preventDefault();

        const form = event.target;
        const userId = form.action.split('/').pop();

        try {
            const response = await fetch(`/api/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to delete user');
            }

            const result = await response.json();
            
            // Log the result to ensure it's correct
            console.log(result);

            if (result.success) {
                alert('User deleted successfully!');
                window.location.href = result.redirect_url; // Redirect to the users page
            } else {
                throw new Error(result.error || 'An error occurred');
            }
        } catch (error) {
            console.error('Error deleting user:', error);
            alert('There was a problem deleting the user');
        }
    }
});

document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const query = document.getElementById('searchInput').value;
    fetchItems(1, query);
});
</script>

<script src="https://cdn.tailwindcss.com"></script>
</x-app-layout>