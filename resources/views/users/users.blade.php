<!-- filepath: /C:/laragon/www/internship-web-project/resources/views/users/users.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                {{ __('Users') }} 
                <span class="ml-2 bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full" id="user-count">Loading...</span>
            </h2>

            @if (Auth::check() && Auth::user()->role === 'Manager')
            <x-add-button url="/addmanager" class="transition-all duration-300 transform hover:scale-105">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add New Manager
                </span>
            </x-add-button>
            @endif
        </div>
    </x-slot>

    <!-- Search bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input id="search-input" type="text" placeholder="Search users..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200">
                </div>
                <button id="search-button" class="ml-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors duration-200 flex items-center">
                    <span>Search</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Users grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="items-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Items will be populated by JavaScript -->
        </div>

        <!-- Pagination controls -->
        <div class="mt-8 mb-6" id="pagination-container">
            <!-- Pagination buttons will be inserted here -->
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        
        // Add search functionality
        searchButton.addEventListener('click', () => {
            fetchItems(1, searchInput.value);
        });
        
        // Allow search on Enter key
        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') {
                fetchItems(1, searchInput.value);
            }
        });
        
        async function fetchItems(page = 1, query = '') {
            try {
                // Show loading state
                document.getElementById('items-container').innerHTML = `
                    <div class="col-span-full flex justify-center items-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                    </div>
                `;
                
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

                // Handle empty results
                if (data.users.data.length === 0) {
                    itemsContainer.innerHTML = `
                        <div class="col-span-full bg-gray-50 rounded-lg p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
                        </div>
                    `;
                    return;
                }

                // Populate items
                data.users.data.forEach(user => {
                    // Create role badge with color based on role
                    const roleBadgeColor = user.role === 'Manager' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800';
                    
                    const itemCard = `
                        <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h5 class="font-bold text-xl text-gray-900">${user.name}</h5>
                                    <span class="${roleBadgeColor} text-xs font-bold px-3 py-1 rounded-full">${user.role}</span>
                                </div>
                                <div class="space-y-2 mb-4">
                                    <p class="text-gray-600 flex items-center">
                                        <svg class="h-5 w-5 mr-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                        ${user.email}
                                    </p>
                                </div>
                                @if (Auth::check() && Auth::user()->role === 'Manager')
                                    <form class="delete-form mt-4" data-id="${user.id}">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="w-full bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-colors duration-200 flex items-center justify-center group">
                                            <svg class="h-5 w-5 mr-2 group-hover:animate-pulse" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            Delete User
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    `;
                    itemsContainer.innerHTML += itemCard;
                });

                // Create pagination UI
                const paginationEl = document.createElement('div');
                paginationEl.className = 'flex justify-center space-x-2';
                
                // Page info
                const pageInfo = document.createElement('div');
                pageInfo.className = 'text-sm text-gray-700 py-2 px-4';
                pageInfo.textContent = `Page ${data.users.current_page} of ${data.users.last_page}`;
                
                // Previous button
                const prevButton = document.createElement('button');
                prevButton.className = data.users.prev_page_url 
                    ? 'bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50 transition-colors duration-200 flex items-center'
                    : 'bg-gray-100 text-gray-400 px-4 py-2 rounded-md cursor-not-allowed flex items-center';
                prevButton.disabled = !data.users.prev_page_url;
                prevButton.innerHTML = `
                    <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Previous
                `;
                prevButton.onclick = () => fetchItems(data.users.current_page - 1, query);
                
                // Next button
                const nextButton = document.createElement('button');
                nextButton.className = data.users.next_page_url 
                    ? 'bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50 transition-colors duration-200 flex items-center'
                    : 'bg-gray-100 text-gray-400 px-4 py-2 rounded-md cursor-not-allowed flex items-center';
                nextButton.disabled = !data.users.next_page_url;
                nextButton.innerHTML = `
                    Next
                    <svg class="h-5 w-5 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                `;
                nextButton.onclick = () => fetchItems(data.users.current_page + 1, query);

                paginationEl.appendChild(prevButton);
                paginationEl.appendChild(pageInfo);
                paginationEl.appendChild(nextButton);
                paginationContainer.appendChild(paginationEl);

                // Add event listeners to delete forms
                document.querySelectorAll('.delete-form').forEach(form => {
                    form.addEventListener('submit', async (event) => {
                        event.preventDefault();
                        
                        if (!confirm('Are you sure you want to delete this user?')) {
                            return;
                        }
                        
                        const userId = form.getAttribute('data-id');
                        const token = form.querySelector('input[name="_token"]').value;
                        
                        // Show loading state on button
                        const button = form.querySelector('button');
                        const originalContent = button.innerHTML;
                        button.innerHTML = `
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Deleting...
                        `;
                        
                        try {
                            const response = await fetch(`/api/users/${userId}`, {
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

                            // Refresh the current page instead of redirecting
                            fetchItems(data.users.current_page, query);
                        } catch (error) {
                            console.error('Error deleting user:', error);
                            // Restore button state
                            button.innerHTML = originalContent;
                            alert('Failed to delete user. Please try again.');
                        }
                    });
                });

            } catch (error) {
                console.error('Error fetching items:', error);
                document.getElementById('items-container').innerHTML = `
                    <div class="col-span-full bg-red-50 border border-red-200 rounded-lg p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-red-800">Error loading users</h3>
                        <p class="mt-1 text-sm text-red-600">Please try refreshing the page.</p>
                    </div>
                `;
            }
        }

        // Initial fetch
        fetchItems();
    </script>
</x-app-layout>