<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-400 to-pink-400 dark:bg-gray-900">

    <div class="min-h-screen flex items-center justify-center">
        <div class="container mx-auto max-w-xs sm:max-w-md md:max-w-lg lg:max-w-xl shadow-md dark:shadow-white py-4 px-6 sm:px-10 bg-white bg-gradient-to-r from-blue-600 to-pink-600 border-emerald-500 rounded-md">
            
            <!-- Back Button -->
            <a href="/categories" class="px-4 py-2 bg-gradient-to-r from-blue-400 to-pink-400 rounded-md text-white text-sm sm:text-lg shadow-md">Go Back</a>

            <div class="my-3">
                <h1 class="text-center text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Add Category</h1>

                <!-- Error Messages Container -->
                <div id="error-container" class="text-red-500 font-bold mt-2"></div>

                <!-- Category Form -->
                <form id="categoryForm" action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="my-2">
                        <label for="name" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900" 
                        >
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="px-4 py-1 bg-gradient-to-r from-blue-400 to-pink-400 rounded-md text-white text-sm sm:text-lg shadow-md">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('#categoryForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json',
                    },
                    body: formData, // Do NOT set 'Content-Type' header, fetch handles it
                });

                const contentType = response.headers.get('content-type');
                let result;
                if (response.ok) {
                    if (contentType && contentType.includes('application/json')) {
                        result = await response.json();
                    } else {
                        result = await response.text();
                    }
                    console.log('Form submission successful:', result);
                    window.location.href = '/categories';
                } else if (response.status === 422) {
                    result = await response.json();
                    console.log('Validation errors:', result.errors);
                    displayErrors(result.errors);
                } else {
                    result = await response.text();
                    alert("An error occurred: " + result);
                }
            } catch (error) {
                console.error('Fetch error:', error);
                alert("There was a problem with the fetch operation");
            }
        });

        function displayErrors(errors) {
            const errorContainer = document.querySelector('#error-container');
            errorContainer.innerHTML = '';

            let errorMessages = '';
            for (const field in errors) {
                errors[field].forEach(error => {
                    errorMessages += `<div>${error}</div>`;
                });
            }

            errorContainer.innerHTML = errorMessages;
        }
    </script>

</body>
</html>
