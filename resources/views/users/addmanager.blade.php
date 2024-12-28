<x-add 
    title="Add New Manager" 
    back-url="/users" 
    form-action="/api/users/createmanager" 
    form-id="managerForm"
    :fields="[ 
        ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'id' => 'name'], 
        ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'id' => 'email'], 
        ['label' => 'Password', 'name' => 'password', 'type' => 'password', 'id' => 'password'], 
        ['label' => 'Confirm Password', 'name' => 'password_confirmation', 'type' => 'password', 'id' => 'password_confirmation'], 
        ['label' => 'Role', 'name' => 'role', 'type' => 'text', 'id' => 'role', 'value' => 'Manager'], 
    ]"
/>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
document.querySelector('#managerForm').addEventListener('submit', async (event) => {
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

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const contentType = response.headers.get('content-type');
        let result;
        if (contentType && contentType.indexOf('application/json') !== -1) {
            result = await response.json();
        } else {
            result = await response.text();
        }
        console.log('Form submission successful:', result);
        // Redirect to /users on success
        window.location.href = '/users';
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
        alert("There was a problem with the fetch operation");
    }
});
</script>