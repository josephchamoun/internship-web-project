<x-app-layout>
<x-slot name="header">
    <div class="flex items-center space-x-2 justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
        <!-- Button to Add Manager -->
        @if (Auth::check() && Auth::user()->role === 'Manager')
        <x-add-button url="/addmanager">
            Add Manager
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

        async function fetchUsers(page = 1) {
            try {
                const response = await fetch(`/api/users?page=${page}`);
                const data = await response.json();

                const itemsContainer = document.getElementById('items-container');
                const paginationContainer = document.getElementById('pagination-container');

                // Clear existing content
                itemsContainer.innerHTML = '';
                paginationContainer.innerHTML = '';

                // Populate user data
                data.data.forEach(user => {
                    const itemCard = `
                        <div class="bg-white shadow rounded-lg p-4">
                            <div class="flex items-center">
                                <img src="${user.profile_photo}" alt="${user.name}" class="w-16 h-16 rounded-full mr-4">
                                <div>
                                    <h5 class="font-semibold text-lg">${user.name}</h5>
                                    <p class="text-gray-600">${user.email}</p>
                                    <p class="text-gray-600">Role: ${user.role}</p>
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
                    button.onclick = () => fetchUsers(page);
                    return button;
                };

                // Add "Previous" button
                const prevButton = createPaginationButton('Previous', data.prev_page_url, data.current_page - 1);
                // Add "Next" button
                const nextButton = createPaginationButton('Next', data.next_page_url, data.current_page + 1);

                paginationContainer.appendChild(prevButton);
                paginationContainer.appendChild(nextButton);

            } catch (error) {
                console.error('Error fetching users:', error);
            }
        }

        // Load users when the page loads
        document.addEventListener('DOMContentLoaded', () => fetchUsers(currentPage));
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
</x-app-layout>


