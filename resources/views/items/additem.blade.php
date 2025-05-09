<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add New Supply</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-400 to-pink-400 dark:bg-gray-900">
    <div class="container mx-auto max-w-xs sm:max-w-md md:max-w-lg lg:max-w-xl shadow-md dark:shadow-white py-4 px-6 sm:px-10 bg-white bg-gradient-to-r from-blue-600 to-pink-600 border-emerald-500 rounded-md">
        <a href="/itemsupplier" class="px-4 py-2 bg-gradient-to-r from-blue-400 to-pink-400 rounded-md text-white text-sm sm:text-lg shadow-md">Go Back</a>
        
        <div class="my-3">
            <h1 class="text-center text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Add New Supply</h1>
            <form id="itemsupplierForm" action="/api/itemsupplier/addsupply" method="POST">
                @csrf
                <div id="error-container"></div>
                
                <div class="my-2">
                    <label for="itemname" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Item Name</label>
                    <input type="text" name="itemname" id="itemname" class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900">
                </div>
                <div class="my-2">
                    <label for="suppliername" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Supplier Name</label>
                    <input type="text" name="suppliername" id="suppliername" class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900">
                </div>
                <div class="my-2">
                    <label for="buyprice" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Buy Price</label>
                    <input type="number" name="buyprice" id="buyprice" step="0.01" min="0" oninput="validity.valid||(value='');" class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900">
                </div>
                <div class="my-2">
                    <label for="quantity" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Quantity</label>
                    <input type="number" name="quantity" id="quantity" min="0" oninput="validity.valid||(value='');" class="block w-full border border-emerald-500 outline-emerald-800 px-2 py-2 text-sm sm:text-md rounded-md my-2 bg-white text-gray-900 dark:text-gray-900">
                </div>
                <button type="submit" class="px-4 py-1 bg-gradient-to-r from-blue-400 to-pink-400 rounded-md text-white text-sm sm:text-lg shadow-md opacity-50 cursor-not-allowed" disabled>Save</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('#itemsupplierForm');
            const submitButton = form.querySelector('button[type="submit"]');
            const inputs = form.querySelectorAll('input');

            function checkInputs() {
                let allFilled = true;
                inputs.forEach(input => {
                    if (input.value.trim() === '') {
                        allFilled = false;
                    }
                });
                submitButton.disabled = !allFilled;
                submitButton.classList.toggle('opacity-50', !allFilled);
                submitButton.classList.toggle('cursor-not-allowed', !allFilled);
            }

            inputs.forEach(input => {
                input.addEventListener('input', checkInputs);
            });

            checkInputs();

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(form);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Authorization': 'Bearer ' + localStorage.getItem('token'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(data),
                    });

                    const contentType = response.headers.get('content-type');
                    let result;
                    if (response.ok) {
                        result = contentType && contentType.includes('application/json') ? await response.json() : await response.text();
                        console.log('Form submission successful:', result);
                        window.location.href = '/itemsupplier';
                    } else {
                        result = contentType && contentType.includes('application/json') ? await response.json() : await response.text();
                        console.log('Form submission failed:', result.errors);
                        displayErrors(result.errors);
                    }
                } catch (error) {
                    console.error('There was a problem with the fetch operation:', error);
                    alert("There was a problem with the fetch operation");
                }
            });

            function displayErrors(errors) {
                const errorContainer = document.querySelector('#error-container');
                errorContainer.innerHTML = '';

                for (const field in errors) {
                    errors[field].forEach(error => {
                        const errorMessage = document.createElement('div');
                        errorMessage.classList.add('text-red-500');
                        errorMessage.textContent = `${field}: ${error}`;
                        errorContainer.appendChild(errorMessage);
                    });
                }
            }
        });
    </script>
</body>
</html>
