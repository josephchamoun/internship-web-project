<x-add 
    title="Add New Item" 
    back-url="/dashboard" 
    form-action="/api/items/addnewitem" 
    form-id="itemForm"
    :fields="[ 
        ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'id' => 'name'], 
        ['label' => 'Description', 'name' => 'description', 'type' => 'text', 'id' => 'description'], 
        ['label' => 'Price', 'name' => 'price', 'type' => 'text', 'id' => 'price'],
        ['label' => 'Category', 'name' => 'category', 'type' => 'select', 'id' => 'category_filter', 'options' => []], // Placeholder for categories
        ['label' => 'Gender', 'name' => 'gender', 'type' => 'select', 'id' => 'gender', 'options' => ['male' => 'Male', 'female' => 'Female', 'both' => 'Both']],
        ['label' => 'Age', 'name' => 'age', 'type' => 'select', 'id' => 'age', 'options' => ['0-3' => '0-3', '3-6' => '3-6', '6-9' => '6-9', '9-12' => '9-12','13-17' => '13-17', '18+' => '18+']],
    ]"
/>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetchCategories();
});

document.querySelector('#itemForm').addEventListener('submit', async (event) => {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    // Convert form data to JSON object
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
            },
            body: JSON.stringify(data),
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
            // Redirect to /dashboard on success
            window.location.href = '/dashboard';
        } else {
            if (contentType && contentType.indexOf('application/json') !== -1) {
                result = await response.json();
                console.log('Form submission failed:', result.errors);
                displayErrors(result.errors); // Show errors if any
            } else {
                result = await response.text();
                alert("An error occurred: " + result);
            }
        }
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
        alert("There was a problem with the fetch operation");
    }
});

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

async function fetchCategories() {
    try {
        const response = await fetch('/api/categories', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'), // Include token for authenticated requests
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