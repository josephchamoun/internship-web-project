<!-- filepath: /C:/laragon/www/intershipwebproject/resources/views/categories/categories.blade.php -->
<x-app-layout>
<x-slot name="header">
    <div class="flex items-center space-x-2 justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>

        @if (Auth::check() && Auth::user()->role === 'Manager')
        <x-add-button url="/addcategories">
            Add New Category
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
                placeholder="Search category by name" 
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
</div>

<script>
    async function fetchItems(query = '') {
        try {
            const response = await fetch(`/api/categories?search=${query}`, {
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
            itemsContainer.innerHTML = '';

            // Populate items
            data.forEach(item => {
                const itemCard = document.createElement('div');
                itemCard.className = 'bg-white shadow rounded-lg p-4';

                itemCard.innerHTML = `
                    <h5 class="font-semibold text-lg">${item.name}</h5>
                    <div class="mt-4 flex gap-2">
                        @if (Auth::check() && Auth::user()->role === 'Manager')
                            <a href="/categories/${item.id}/edit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 flex items-center delete-button" data-id="${item.id}">
                                <i class="fas fa-trash-alt mr-2"></i> Delete
                            </button>
                        @endif
                    </div>
                `;
                itemsContainer.appendChild(itemCard);
            });

            // Attach event listeners to delete buttons
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', async (event) => {
                    const categoryId = event.target.getAttribute('data-id');
                    await deleteCategory(categoryId);
                });
            });

        } catch (error) {
            console.error('Error fetching items:', error);
        }
    }

    async function deleteCategory(categoryId) {
        if (!confirm('Are you sure you want to delete this category?')) return;

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch(`/api/categories/delete/${categoryId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to delete category.');
            }

            // Reload the categories list after deletion
            fetchItems();
        } catch (error) {
            console.error('Error deleting category:', error);
        }
    }

    // Load items when the page loads
    document.addEventListener('DOMContentLoaded', () => fetchItems());

    // Handle search form submission
    document.getElementById('searchForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const query = document.getElementById('searchInput').value;
        fetchItems(query);
    });
</script>

<script src="https://cdn.tailwindcss.com"></script>
</x-app-layout>
