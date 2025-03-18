<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Supplier</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-400 to-pink-400 dark:bg-gray-900">
    <div class="container mx-auto max-w-xs sm:max-w-md md:max-w-lg lg:max-w-xl shadow-md dark:shadow-white py-4 px-6 sm:px-10 bg-white bg-gradient-to-r from-blue-600 to-pink-600 border-emerald-500 rounded-md">
        <a href="/suppliers" class="px-4 py-2 bg-gradient-to-r from-blue-400 to-pink-400 rounded-md text-white text-sm sm:text-lg shadow-md">Go Back</a>

        <div class="my-3">
            <h1 class="text-center text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Edit Supplier</h1>
            <form id="editSupplierForm" action="/api/suppliers/edit/{{ $supplier->id }}" method="POST">
                @csrf
                @method('PUT')
                <div id="error-container"></div>

                <div class="my-2">
                    <label for="name" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Supplier Name</label>
                    <input type="text" name="name" id="name" value="{{ $supplier->name }}" class="input-field">
                </div>

                <div class="my-2">
                    <label for="phone" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ $supplier->phone }}" class="input-field">
                </div>

                <div class="my-2">
                    <label for="email" class="text-sm sm:text-md font-bold text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" id="email" value="{{ $supplier->email }}" class="input-field">
                </div>

                <button type="submit" id="submitBtn" class="submit-button">Update</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('#editSupplierForm');
            const submitButton = document.querySelector('#submitBtn');
            const inputs = form.querySelectorAll('input');

            function checkInputs() {
                let allFilled = Array.from(inputs).every(input => input.value.trim() !== '');
                submitButton.disabled = !allFilled;
                submitButton.classList.toggle('opacity-50', !allFilled);
                submitButton.classList.toggle('cursor-not-allowed', !allFilled);
            }

            inputs.forEach(input => input.addEventListener('input', checkInputs));
            checkInputs();

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                try {
                    const response = await fetch(form.action, {
                        method: 'PUT', // Use PUT for updating
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify(data),
                    });

                    const result = await response.json();

                    if (!response.ok || (result.success !== undefined && !result.success)) {
                        displayErrors(result.errors || {'error': [result.message]});
                        return;
                    }

                    console.log('Update successful:', result);
                    window.location.href = '/suppliers';

                } catch (error) {
                    console.error('Error:', error);
                    alert("There was a problem with the fetch operation");
                }
            });

            function displayErrors(errors) {
                const errorContainer = document.querySelector('#error-container');
                errorContainer.innerHTML = '';

                for (const field in errors) {
                    errors[field].forEach(error => {
                        const errorMessage = document.createElement('div');
                        errorMessage.classList.add('text-red-500', 'text-sm');
                        errorMessage.textContent = `${field}: ${error}`;
                        errorContainer.appendChild(errorMessage);
                    });
                }
            }
        });
    </script>

    <style>
        .input-field {
            display: block;
            width: 100%;
            border: 1px solid #34D399;
            outline: none;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            background-color: white;
            color: #1F2937;
        }

        .submit-button {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            background: linear-gradient(to right, #3B82F6, #EC4899);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .submit-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</body>
</html>
