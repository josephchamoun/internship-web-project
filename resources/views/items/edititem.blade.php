<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-blue': '#4B89DC',
                        'custom-blue-light': '#6FA1E9',
                        'custom-blue-dark': '#3A6FC9',
                        'custom-pink': '#E84F8A',
                        'custom-pink-light': '#F06EA0',
                        'custom-pink-dark': '#D6306F'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #f0f7ff 0%, #fff0f5 100%);
        }
        .form-container {
            background: white;
            box-shadow: 0 0 20px rgba(75, 137, 220, 0.1);
            border-radius: 12px;
            border-top: 5px solid #4B89DC;
            border-bottom: 5px solid #E84F8A;
        }
        .input-focus:focus {
            border-color: #4B89DC;
            box-shadow: 0 0 0 3px rgba(75, 137, 220, 0.3);
        }
        .select-focus:focus {
            border-color: #E84F8A;
            box-shadow: 0 0 0 3px rgba(232, 79, 138, 0.3);
        }
        .button-gradient {
            background: linear-gradient(to right, #4B89DC, #E84F8A);
            transition: all 0.3s ease;
        }
        .button-gradient:hover {
            background: linear-gradient(to right, #3A6FC9, #D6306F);
            transform: translateY(-2px);
        }
        .category-item:nth-child(odd) {
            background-color: #f0f7ff;
        }
        .category-item:nth-child(even) {
            background-color: #fff0f5;
        }
    </style>
</head>
<body class="min-h-screen py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto form-container p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-custom-blue to-custom-pink">
                    Edit Item
                </h1>
                <a href="/dashboard" class="px-5 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back</span>
                </a>
            </div>
            
            <!-- Error container to display validation errors -->
            <div id="error-container" class="mb-6"></div>
            
            <form id="editItemForm" action="/api/items/edit/{{ $item->id }}" class="space-y-6">
                <!-- Item Name -->
                <div>
                    <label for="name" class="block text-custom-blue font-medium mb-2">Item Name</label>
                    <input type="text" id="name" name="name" value="{{ $item->name }}" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg input-focus focus:outline-none transition">
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-custom-pink font-medium mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" 
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg input-focus focus:outline-none transition">{{ $item->description }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-custom-blue font-medium mb-2">Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500">$</span>
                            <input type="number" id="price" name="price" value="{{ $item->price }}" step="0.01" min="0" 
                                class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-lg input-focus focus:outline-none transition">
                        </div>
                    </div>
                    
                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-custom-pink font-medium mb-2">Quantity</label>
                        <input type="number" id="quantity" name="quantity" value="{{ $item->quantity }}" min="0" 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg input-focus focus:outline-none transition">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Gender Combobox -->
                    <div>
                        <label for="gender" class="block text-custom-blue font-medium mb-2">Gender</label>
                        <select id="gender" name="gender" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg select-focus focus:outline-none transition appearance-none bg-white">
                            <option value="both" {{ $item->gender == 'both' ? 'selected' : '' }}>Both</option>
                            <option value="male" {{ $item->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ $item->gender == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    
                    <!-- Age Combobox -->
                    <div>
                        <label for="age" class="block text-custom-pink font-medium mb-2">Age Range</label>
                        <select id="age" name="age" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg select-focus focus:outline-none transition appearance-none bg-white">
                            <option value="0-3" {{ $item->age == '0-3' ? 'selected' : '' }}>0-3</option>
                            <option value="3-6" {{ $item->age == '3-6' ? 'selected' : '' }}>3-6</option>
                            <option value="6-9" {{ $item->age == '6-9' ? 'selected' : '' }}>6-9</option>
                            <option value="9-12" {{ $item->age == '9-12' ? 'selected' : '' }}>9-12</option>
                            <option value="13-17" {{ $item->age == '13-17' ? 'selected' : '' }}>13-17</option>
                            <option value="18+" {{ $item->age == '18+' ? 'selected' : '' }}>18+</option>
                        </select>
                    </div>
                </div>
                
                <!-- Category Combobox -->
                <div>
                    <label for="category_id" class="block text-gray-700 font-medium mb-2">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-custom-blue to-custom-pink font-semibold">Category</span>
                    </label>
                    <div class="flex space-x-2">
                        <select id="category_id" name="category_id" 
                                class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-lg select-focus focus:outline-none transition appearance-none bg-white">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" id="editCategoryBtn" 
                                class="px-4 py-3 bg-gradient-to-r from-custom-blue-light to-custom-pink-light text-white rounded-lg hover:from-custom-blue hover:to-custom-pink transition shadow-md">
                            Edit Categories
                        </button>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-8 py-3 button-gradient text-white rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom-blue">
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Category Edit Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-8 max-w-md w-full shadow-2xl">
            <h2 class="text-2xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-custom-blue to-custom-pink">Edit Categories</h2>
            <div id="categoriesList" class="mb-6 max-h-60 overflow-y-auto rounded-lg border border-gray-200">
                <!-- Categories will be loaded here -->
            </div>
            <div class="mb-6">
                <label for="newCategory" class="block text-gray-700 font-medium mb-2">Add New Category</label>
                <div class="flex space-x-2">
                    <input type="text" id="newCategory" class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-lg input-focus focus:outline-none transition">
                    <button type="button" id="addCategoryBtn" class="px-4 py-3 bg-gradient-to-r from-custom-blue to-custom-pink text-white rounded-lg hover:from-custom-blue-dark hover:to-custom-pink-dark transition shadow-md">
                        Add
                    </button>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" id="closeCategoryModal" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('#editItemForm').addEventListener('submit', async (event) => {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(form.action, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data),
                });

                const result = await response.json();
                
                if (response.ok) {
                    // Success - redirect to dashboard
                    window.location.href = '/dashboard';
                } else if (response.status === 422) {
                    // Validation errors - display them
                    console.log('Validation errors:', result.errors);
                    displayErrors(result.errors);
                } else {
                    // Other error
                    alert("An error occurred: " + (result.message || "Unknown error"));
                }
            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
                alert("There was a problem with the fetch operation");
            }
        });

        function displayErrors(errors) {
            const errorContainer = document.querySelector('#error-container');
            if (!errorContainer) return;

            // Clear any existing errors
            errorContainer.innerHTML = '';
            
            // Add a wrapper for better styling
            const errorWrapper = document.createElement('div');
            errorWrapper.classList.add('bg-gradient-to-r', 'from-red-50', 'to-pink-50', 'border', 'border-red-300', 'text-red-700', 'p-4', 'rounded-lg', 'shadow-sm');
            
            // Add a title
            const errorTitle = document.createElement('h3');
            errorTitle.classList.add('font-bold', 'mb-2', 'flex', 'items-center', 'text-custom-pink-dark');
            
            // Add error icon
            errorTitle.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Please fix the following errors:
            `;
            errorWrapper.appendChild(errorTitle);
            
            // Create a list for the errors
            const errorList = document.createElement('ul');
            errorList.classList.add('list-disc', 'pl-10', 'mt-2', 'space-y-1');
            
            // Add each error as a list item
            for (const field in errors) {
                const errorMessages = errors[field];
                errorMessages.forEach(message => {
                    const errorItem = document.createElement('li');
                    errorItem.classList.add('text-red-600');
                    // Capitalize the first letter of the field name
                    const fieldName = field.charAt(0).toUpperCase() + field.slice(1);
                    errorItem.textContent = `${fieldName}: ${message}`;
                    errorList.appendChild(errorItem);
                });
            }
            
            errorWrapper.appendChild(errorList);
            errorContainer.appendChild(errorWrapper);
            
            // Scroll to the error container
            errorContainer.scrollIntoView({ behavior: 'smooth' });
        }

        // Category Modal Controls
        const editCategoryBtn = document.getElementById('editCategoryBtn');
        const categoryModal = document.getElementById('categoryModal');
        const closeCategoryModal = document.getElementById('closeCategoryModal');
        const categoriesList = document.getElementById('categoriesList');
        const newCategoryInput = document.getElementById('newCategory');
        const addCategoryBtn = document.getElementById('addCategoryBtn');

        // Open modal and load categories
        editCategoryBtn.addEventListener('click', async () => {
            await loadCategories();
            categoryModal.classList.remove('hidden');
            // Add animation classes
            setTimeout(() => {
                document.querySelector('#categoryModal > div').classList.add('animate-fade-in');
            }, 10);
        });

        // Close modal
        closeCategoryModal.addEventListener('click', () => {
            categoryModal.classList.add('hidden');
        });

        // Close modal when clicking outside
        categoryModal.addEventListener('click', (event) => {
            if (event.target === categoryModal) {
                categoryModal.classList.add('hidden');
            }
        });

        // Load categories from API
        async function loadCategories() {
            try {
                const response = await fetch('/api/categories', {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json',
                    }
                });
                
                if (response.ok) {
                    const categories = await response.json();
                    renderCategories(categories);
                } else {
                    alert('Failed to load categories');
                }
            } catch (error) {
                console.error('Error loading categories:', error);
                alert('Error loading categories');
            }
        }

        // Render categories list
        function renderCategories(categories) {
            categoriesList.innerHTML = '';
            
            categories.forEach((category, index) => {
                const categoryItem = document.createElement('div');
                categoryItem.classList.add('category-item', 'flex', 'justify-between', 'items-center', 'py-3', 'px-4', 'border-b', 'border-gray-200');
                
                const categoryName = document.createElement('span');
                categoryName.textContent = category.name;
                categoryName.classList.add('font-medium');
                
                const actionsDiv = document.createElement('div');
                actionsDiv.classList.add('flex', 'space-x-2');
                
                const editBtn = document.createElement('button');
                editBtn.classList.add('px-3', 'py-1', 'bg-custom-blue-light', 'text-white', 'rounded-full', 'text-sm', 'hover:bg-custom-blue', 'transition');
                editBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                `;
                editBtn.addEventListener('click', () => editCategory(category));
                
                const deleteBtn = document.createElement('button');
                deleteBtn.classList.add('px-3', 'py-1', 'bg-custom-pink-light', 'text-white', 'rounded-full', 'text-sm', 'hover:bg-custom-pink', 'transition');
                deleteBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                `;
                deleteBtn.addEventListener('click', () => deleteCategory(category.id));
                
                actionsDiv.appendChild(editBtn);
                actionsDiv.appendChild(deleteBtn);
                
                categoryItem.appendChild(categoryName);
                categoryItem.appendChild(actionsDiv);
                
                categoriesList.appendChild(categoryItem);
            });
            
            // If empty, show a message
            if (categories.length === 0) {
                const emptyMessage = document.createElement('div');
                emptyMessage.classList.add('py-4', 'px-4', 'text-center', 'text-gray-500', 'italic');
                emptyMessage.textContent = 'No categories found. Add one below.';
                categoriesList.appendChild(emptyMessage);
            }
        }

        // Edit category function
        function editCategory(category) {
            const newName = prompt('Enter new name for category:', category.name);
            if (newName && newName !== category.name) {
                updateCategory(category.id, newName);
            }
        }

        // Update category via API
        async function updateCategory(id, newName) {
            try {
                const response = await fetch(`/api/categories/edit/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ name: newName }),
                });
                
                if (response.ok) {
                    await loadCategories();
                    // Also update the dropdown
                    await updateCategoryDropdown();
                } else {
                    const result = await response.json();
                    alert('Failed to update category: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error updating category:', error);
                alert('Error updating category');
            }
        }

        // Delete category via API
        async function deleteCategory(id) {
            if (!confirm('Are you sure you want to delete this category? This may affect items using this category.')) {
                return;
            }
            
            try {
                const response = await fetch(`/api/categories/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json',
                    },
                });
                
                if (response.ok) {
                    await loadCategories();
                    // Also update the dropdown
                    await updateCategoryDropdown();
                } else {
                    const result = await response.json();
                    alert('Failed to delete category: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error deleting category:', error);
                alert('Error deleting category');
            }
        }

        // Add new category
        addCategoryBtn.addEventListener('click', async () => {
            const categoryName = newCategoryInput.value.trim();
            if (!categoryName) {
                alert('Please enter a category name');
                return;
            }
            
            try {
                const response = await fetch('/api/categories/addcategory', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ name: categoryName }),
                });
                
                if (response.ok) {
                    newCategoryInput.value = '';
                    await loadCategories();
                    // Also update the dropdown
                    await updateCategoryDropdown();
                } else {
                    const result = await response.json();
                    alert('Failed to add category: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error adding category:', error);
                alert('Error adding category');
            }
        });

        // Update category dropdown
        async function updateCategoryDropdown() {
            try {
                const response = await fetch('/api/categories', {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Accept': 'application/json',
                    }
                });
                
                if (response.ok) {
                    const categories = await response.json();
                    const categoryDropdown = document.getElementById('category_id');
                    const selectedValue = categoryDropdown.value;
                    
                    // Clear current options
                    categoryDropdown.innerHTML = '';
                    
                    // Add new options
                    categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        if (category.id.toString() === selectedValue) {
                            option.selected = true;
                        }
                        categoryDropdown.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error updating category dropdown:', error);
            }
        }

        // Add some animation classes
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('form').classList.add('animate-fade-in');
        });
    </script>
</body>
</html>