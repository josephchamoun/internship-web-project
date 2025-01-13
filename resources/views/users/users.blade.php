<!-- filepath: /C:/laragon/www/internship-web-project/resources/views/users/users.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users') }} (<span id="user-count">Loading...</span>)
            </h2>

            @if (Auth::check() && Auth::user()->role === 'Manager')
            <x-add-button url="/addmanager">
                Add New Manager
            </x-add-button>
            @endif
        </div>
    </x-slot>

    <!-- Rest of your view content -->

    <div id="items-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Items will be populated by JavaScript -->
    </div>

    <div class="mt-6 flex justify-between" id="pagination-container">
        <!-- Pagination buttons will be inserted here -->
    </div>

    <script>
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
                const userCountElement = document.getElementById('user-count');

                // Update user count
                userCountElement.textContent = data.userCount;

                // Clear existing items and pagination
                itemsContainer.innerHTML = '';
                paginationContainer.innerHTML = '';

                // Populate items
                data.users.data.forEach(user => {
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
                const prevButton = createPaginationButton('Previous', data.users.prev_page_url, data.users.current_page - 1);

                // Add "Next" button
                const nextButton = createPaginationButton('Next', data.users.next_page_url, data.users.current_page + 1);

                paginationContainer.appendChild(prevButton);
                paginationContainer.appendChild(nextButton);

            } catch (error) {
                console.error('Error fetching items:', error);
            }
        }

        // Initial fetch
        fetchItems();
    </script>
</x-app-layout>