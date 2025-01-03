<x-add 
    title="Add New Supply" 
    back-url="/itemsupplier" 
    form-action="/api/itemsupplier/addsupply" 
    form-id="itemsupplierForm"
    :fields="[ 
        ['label' => 'Item Name', 'name' => 'itemname', 'type' => 'text', 'id' => 'itemname'], 
        ['label' => 'Supplier Name', 'name' => 'suppliername', 'type' => 'text', 'id' => 'suppliername'], 
        ['label' => 'Buy Price', 'name' => 'buyprice', 'type' => 'text', 'id' => 'buyprice'], 
        ['label' => 'Quantity', 'name' => 'quantity', 'type' => 'text', 'id' => 'quantity'], 
    ]"
/>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.querySelector('#itemsupplierForm').addEventListener('submit', async (event) => {
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
            // Redirect to /itemsupplier on success
            window.location.href = '/itemsupplier';
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
</script>