<!-- filepath: /resources/views/items/edititem.blade.php -->
<!-- Error container moved to top for better visibility -->
<div id="error-container" class="mt-4 mb-6 mx-auto max-w-2xl"></div>

<x-edit 
    title="Edit Category" 
    back-url="/categories" 
    form-action="/api/categories/edit/{{ $category->id }}"
    form-id="editCategoryForm"
    :fields="[ 
        ['label' => 'Category Name', 'name' => 'name', 'type' => 'text', 'id' => 'name', 'value' => $category->name], 
    ]"
/>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.querySelector('#editCategoryForm').addEventListener('submit', async (event) => {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    // Clear any previous errors
    const errorContainer = document.querySelector('#error-container');
    errorContainer.innerHTML = '';

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

        // Log the response for debugging
        console.log('Response status:', response.status);
        
        let result;
        try {
            // Try to parse as JSON regardless of content type
            result = await response.json();
            console.log('Response body:', result);
        } catch (e) {
            // If not JSON, get as text
            result = await response.text();
            console.log('Response text:', result);
        }

        if (response.ok) {
            // Success - redirect to categories page
            window.location.href = '/categories';
        } else if (response.status === 422) {
            // Validation errors
            displayErrors(result.errors);
        } else {
            // Other errors
            alert("An error occurred: " + (result.message || result));
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
    errorWrapper.classList.add('bg-red-50', 'border-l-4', 'border-red-500', 'text-red-700', 'p-4', 'rounded-lg', 'shadow-md', 'mb-4');
    
    // Add a title with an icon
    const errorHeader = document.createElement('div');
    errorHeader.classList.add('flex', 'items-center', 'mb-2');
    
    const errorIcon = document.createElement('div');
    errorIcon.innerHTML = `<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
    </svg>`;
    
    const errorTitle = document.createElement('h3');
    errorTitle.classList.add('font-bold');
    errorTitle.textContent = 'Please fix the following errors:';
    
    errorHeader.appendChild(errorIcon);
    errorHeader.appendChild(errorTitle);
    errorWrapper.appendChild(errorHeader);
    
    // Create a list for the errors
    const errorList = document.createElement('ul');
    errorList.classList.add('list-disc', 'pl-5', 'space-y-1', 'mt-2');
    
    // Add each error as a list item
    for (const field in errors) {
        const errorMessages = errors[field];
        errorMessages.forEach(message => {
            const errorItem = document.createElement('li');
            errorItem.classList.add('text-red-600');
            
            // Capitalize the first letter of the field name
            const fieldName = field.charAt(0).toUpperCase() + field.slice(1);
            
            // Create a span for the field name with bold styling
            const fieldSpan = document.createElement('span');
            fieldSpan.classList.add('font-semibold');
            fieldSpan.textContent = fieldName + ': ';
            
            errorItem.appendChild(fieldSpan);
            errorItem.appendChild(document.createTextNode(message));
            errorList.appendChild(errorItem);
            
            // Highlight the corresponding field
            const inputField = document.querySelector(`#${field}`);
            if (inputField) {
                inputField.classList.add('border-red-500', 'bg-red-50');
                inputField.addEventListener('input', function() {
                    this.classList.remove('border-red-500', 'bg-red-50');
                }, { once: true });
            }
        });
    }
    
    errorWrapper.appendChild(errorList);
    errorContainer.appendChild(errorWrapper);
}
</script>

<style>
    #error-container {
        max-width: 600px;
        margin: 0 auto;
    }
    
    /* Improve form field styling */
    input[type="text"] {
        transition: border-color 0.2s ease, background-color 0.2s ease;
    }
    
    /* Field highlight animation */
    .border-red-500 {
        box-shadow: 0 0 0 1px rgba(239, 68, 68, 0.5);
    }
</style>