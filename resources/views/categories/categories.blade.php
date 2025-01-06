<x-app-layout>
<x-slot name="header">
    <div class="flex items-center space-x-2 justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
        <!-- Button to Add Manager -->
        @if (Auth::check() && Auth::user()->role === 'Manager')
        <x-add-button url="/addmanager">
            Add Category
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

        async function fetchCategories(page = 1) {
            try {
                const response = await fetch(`/api/categories?page=${page}`);
                const data = await response.json();

                console.log('API Response:', data); // Log the API response

                const itemsContainer = document.getElementById('items-container');
                const paginationContainer = document.getElementById('pagination-container');

                // Clear existing content
                itemsContainer.innerHTML = '';
                paginationContainer.innerHTML = '';



                





                // Populate category data
                data.data.forEach(category => {
                    const itemCard = `
                        <div class="bg-white shadow rounded-lg p-4">

                        <div class="mt-4">
                        <h5 class="font-semibold text-lg">${category.name}</h5>
                            
                            <div class="mt-4 flex gap-2">
                                
                            @if (Auth::check() && Auth::user()->role === 'Manager')
                                <a href="/categories/${category.id}/edit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</a>
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
                    button.onclick = () => fetchCategories(page);
                    return button;
                };

                // Add "Previous" button
                const prevButton = createPaginationButton('Previous', data.prev_page_url, data.current_page - 1);
                // Add "Next" button
                const nextButton = createPaginationButton('Next', data.next_page_url, data.current_page + 1);

                paginationContainer.appendChild(prevButton);
                paginationContainer.appendChild(nextButton);

            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        }

        // Load categories when the page loads
        document.addEventListener('DOMContentLoaded', () => fetchCategories(currentPage));
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
</x-app-layout>