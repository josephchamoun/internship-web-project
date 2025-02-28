<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add New Item</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-400 to-pink-400 dark:bg-gray-900">
        <div class="container mx-auto max-w-xs sm:max-w-md md:max-w-lg lg:max-w-xl shadow-md dark:shadow-white py-4 px-6 sm:px-10 bg-white bg-gradient-to-r from-blue-600 to-pink-600 border-emerald-500 rounded-md">
            <!-- Go Back Button -->
            <a href="/dashboard" class="px-4 py-2 bg-gradient-to-r from-blue-400 to-pink-400 rounded-md text-white text-sm sm:text-lg shadow-md">Go Back</a>
            
            <!-- Form Title -->
            <div class="my-3">
                <h1 class="text-center text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Add New Item</h1>
                
                <!-- Form -->
                <form id="itemForm" action="/api/items/addnewitem" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Name Field -->
                    <div class="my-2">
                        <label for="name" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900" 
                            required
                        >
                    </div>

                    <!-- Description Field -->
                    <div class="my-2">
                        <label for="description" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Description</label>
                        <input 
                            type="text" 
                            name="description" 
                            id="description" 
                            class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900" 
                            required
                        >
                    </div>

                    <!-- Price Field -->
                    <div class="my-2">
                        <label for="price" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Price</label>
                        <input 
                            type="text" 
                            name="price" 
                            id="price" 
                            class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900" 
                            required
                            min="0"
                            oninput="validity.valid||(value='');"
                        >
                    </div>

                    <!-- Category Field -->
                    <div class="my-2">
                        <label for="category_filter" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Category</label>
                        <select 
                            name="category" 
                            id="category_filter" 
                            class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900"
                            required
                        >
                            <!-- Options will be populated dynamically by JavaScript -->
                        </select>
                    </div>

                    <!-- Gender Field -->
                    <div class="my-2">
                        <label for="gender" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Gender</label>
                        <select 
                            name="gender" 
                            id="gender" 
                            class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900"
                            required
                        >
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="both">Both</option>
                        </select>
                    </div>

                    <!-- Age Field -->
                    <div class="my-2">
                        <label for="age" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Age</label>
                        <select 
                            name="age" 
                            id="age" 
                            class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900"
                            required
                        >
                            <option value="0-3">0-3</option>
                            <option value="3-6">3-6</option>
                            <option value="6-9">6-9</option>
                            <option value="9-12">9-12</option>
                            <option value="13-17">13-17</option>
                            <option value="18+">18+</option>
                        </select>
                    </div>

                    <!-- Image Field -->
                    <div class="my-2">
                        <label for="image" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Image</label>
                        <input 
                            type="file" 
                            name="image" 
                            id="image" 
                            class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900"
                        >
                    </div>

                    <!-- Error Container -->
                    <div id="error-container" class="my-2"></div>

                    <!-- Submit Button -->
                    <button type="submit" class="px-4 py-1 bg-gradient-to-r from-blue-400 to-pink-400 rounded-md text-white text-sm sm:text-lg shadow-md">Save</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchCategories();
        });

        // Form Submission Handler
        document.querySelector('#itemForm').addEventListener('submit', async (event) => {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    // Validate price (if applicable)
    const price = parseFloat(document.querySelector('#price').value);
    if (price < 0 || isNaN(price)) {
        alert('Price must be a positive value.');
        return;
    }

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData,
        });

        const contentType = response.headers.get('content-type');
        let result;
        if (response.ok) {
            if (contentType && contentType.indexOf('application/json') !== -1) {
                result = await response.json();
            } else {
                result = await response.text();
            }
            console.log('Form submission successful:', result);
            window.location.href = '/dashboard';
        } else if (response.status === 422) {  // Validation error handling
            result = await response.json();
            console.log('Form submission failed:', result.errors);
            displayErrors(result.errors);
        } else {
            result = await response.text();
            alert("An error occurred: " + result);
        }
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
        alert("There was a problem with the fetch operation");
    }
});

// Display Validation Errors
function displayErrors(errors) {
    const errorContainer = document.querySelector('#error-container');
    errorContainer.innerHTML = ''; // Clear any existing errors

    for (const field in errors) {
        const errorList = errors[field];
        errorList.forEach(error => {
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('text-red-500');
            errorMessage.textContent = `${field}: ${error}`;
            errorContainer.appendChild(errorMessage);
        });
    }
}


        // Fetch Categories from API
        async function fetchCategories() {
            try {
                const response = await fetch('/api/categories', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                const categoryFilter = document.getElementById('category_filter');

                // Check if data is an array or an object with a data property
                const categories = Array.isArray(data) ? data : data.data;

                // Populate category filter
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    categoryFilter.appendChild(option);
                });

            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        }
    </script>
</body>
</html>