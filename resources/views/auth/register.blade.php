<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - Epic Toy Store</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        :root {
            --pink: #FF69B4;
            --blue: #00BFFF;
            --soft-pink: #fff0f6;
            --soft-blue: #f0faff;
        }

        body {
            background: linear-gradient(135deg, var(--soft-pink) 0%, var(--soft-blue) 100%);
            min-height: 100vh;
            font-family: 'Figtree', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            border: 2px solid var(--pink);
            box-shadow: 0 10px 30px rgba(255,105,180,0.15);
            width: 100%;
            max-width: 500px;
            padding: 2.5rem;
        }

        .register-title {
            font-size: 2rem;
            font-weight: bold;
            background: linear-gradient(45deg, var(--pink), var(--blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 2rem;
            text-align: center;
        }

        .register-input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .register-input:focus {
            border-color: var(--pink);
            box-shadow: 0 0 0 3px rgba(255,105,180,0.2);
        }

        .register-btn {
            background: var(--pink);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .register-btn:hover {
            background: #ff1493;
            transform: translateY(-2px);
        }

        .login-link {
            color: var(--blue);
            transition: color 0.3s ease;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .login-link:hover {
            color: #009ACD;
        }

        .input-error {
            color: var(--pink);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <!-- Title -->
        <h1 class="register-title">
            Create Your Account
        </h1>

        <!-- Replace the form tag in your HTML with this -->
<form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-6">
    @csrf

    <!-- Name -->
    <div>
        <label for="name" class="text-gray-700 font-medium">Name</label>
        <input 
            id="name" 
            class="register-input mt-1" 
            type="text" 
            name="name" 
            value="{{ old('name') }}" 
            required 
            autofocus 
            autocomplete="name" 
        />
        @if ($errors->has('name'))
            <div class="input-error">{{ $errors->first('name') }}</div>
        @endif
    </div>

    <!-- Email Address -->
    <div>
        <label for="email" class="text-gray-700 font-medium">Email</label>
        <input 
            id="email" 
            class="register-input mt-1" 
            type="email" 
            name="email" 
            value="{{ old('email') }}" 
            required 
            autocomplete="username" 
        />
        @if ($errors->has('email'))
            <div class="input-error">{{ $errors->first('email') }}</div>
        @endif
    </div>

    <div>
        <label for="address" class="text-gray-700 font-medium">Address</label>
        <input 
            id="address" 
            class="register-input mt-1" 
            type="address" 
            name="address" 
            value="{{ old('address') }}" 
            required 
             
        />

    </div>

    <!-- Password -->
    <div>
        <label for="password" class="text-gray-700 font-medium">Password</label>
        <input 
            id="password" 
            class="register-input mt-1" 
            type="password" 
            name="password" 
            required 
            autocomplete="new-password" 
        />
        @if ($errors->has('password'))
            <div class="input-error">{{ $errors->first('password') }}</div>
        @endif
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="password_confirmation" class="text-gray-700 font-medium">Confirm Password</label>
        <input 
            id="password_confirmation" 
            class="register-input mt-1" 
            type="password" 
            name="password_confirmation" 
            required 
            autocomplete="new-password" 
        />
        @if ($errors->has('password_confirmation'))
            <div class="input-error">{{ $errors->first('password_confirmation') }}</div>
        @endif
    </div>

    <div class="flex items-center justify-between mt-6">
        <a class="login-link" href="{{ route('login') }}">
            Already registered?
        </a>

        <button type="submit" class="register-btn" id="registerButton">
            Register
        </button>
    </div>
</form>

<!-- No JavaScript for form submission -->
    <script>
    


    document.addEventListener('DOMContentLoaded', function() {
        const registerForm = document.getElementById('registerForm');
        
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const registerButton = document.getElementById('registerButton');
            
            // Disable button and change text
            registerButton.disabled = true;
            registerButton.innerText = 'Registering...';
            
            // Clear previous errors
            document.querySelectorAll('.input-error').forEach(el => el.remove());
            
            fetch(registerForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                redirect: 'follow'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.redirect_url) {
                    // Manually redirect to the dashboard
                    window.location.href = data.redirect_url;
                } else if (data.errors) {
                    // Display validation errors
                    for (const field in data.errors) {
                        const inputField = document.querySelector(`[name="${field}"]`);
                        if (inputField) {
                            const errorElement = document.createElement('div');
                            errorElement.classList.add('input-error');
                            errorElement.textContent = data.errors[field][0];
                            inputField.parentNode.appendChild(errorElement);
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Registration failed:', error);
            })
            .finally(() => {
                // Re-enable button
                registerButton.disabled = false;
                registerButton.innerText = 'Register';
            });
        });
    });


function displayErrors(errors) {
    for (const field in errors) {
        let inputField = document.querySelector(`[name="${field}"]`);
        if (inputField) {
            let errorElement = document.createElement('div');
            errorElement.classList.add('input-error');
            errorElement.textContent = errors[field][0];
            inputField.parentNode.appendChild(errorElement);
        }
    }
}
</script>


</body>
</html>