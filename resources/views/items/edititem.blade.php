<!-- filepath: /resources/views/items/edititem.blade.php -->
<div id="error-container" class="mt-4"></div>
<x-edit 
    title="Edit Item" 
    back-url="/dashboard" 
    form-action="/api/items/edit/{{ $item->id }}"
    form-id="editItemForm"
    :fields="[ 
        ['label' => 'Item Name', 'name' => 'name', 'type' => 'text', 'id' => 'name', 'value' => $item->name], 
        ['label' => 'Description', 'name' => 'description', 'type' => 'text', 'id' => 'description', 'value' => $item->description], 
        ['label' => 'Price', 'name' => 'price', 'type' => 'text', 'id' => 'price', 'value' => $item->price], 
        ['label' => 'Quantity', 'name' => 'quantity', 'type' => 'text', 'id' => 'quantity', 'value' => $item->quantity], 
    ]"
/>
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Error container to display validation errors -->


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
    errorWrapper.classList.add('bg-red-50', 'border', 'border-red-400', 'text-red-700', 'p-4', 'rounded', 'mb-4');
    
    // Add a title
    const errorTitle = document.createElement('h3');
    errorTitle.classList.add('font-bold', 'mb-2');
    errorTitle.textContent = 'Please fix the following errors:';
    errorWrapper.appendChild(errorTitle);
    
    // Create a list for the errors
    const errorList = document.createElement('ul');
    errorList.classList.add('list-disc', 'pl-4');
    
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
</script>

<style>
    #error-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 1rem;
    }
</style>