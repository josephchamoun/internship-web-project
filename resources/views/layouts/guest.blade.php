<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Styles -->
        <style>
            :root {
                --pink: #FF69B4;
                --blue: #00BFFF;
                --soft-pink: #fff0f6;
                --soft-blue: #f0faff;
            }
            
            .bg-auth {
                background: linear-gradient(135deg, var(--soft-pink) 0%, var(--soft-blue) 100%);
            }
            
            .auth-card {
                background: rgba(255,255,255,0.95);
                border-radius: 20px;
                border: 2px solid var(--pink);
                box-shadow: 0 10px 30px rgba(255,105,180,0.15);
            }
            
            .input-field {
                border: 2px solid #e2e8f0;
                border-radius: 12px;
                transition: all 0.3s ease;
            }
            
            .input-field:focus {
                border-color: var(--pink);
                box-shadow: 0 0 0 3px rgba(255,105,180,0.2);
            }
            
            .auth-btn {
                background: var(--pink);
                color: white;
                padding: 12px 30px;
                border-radius: 12px;
                transition: all 0.3s ease;
            }
            
            .auth-btn:hover {
                background: #ff1493;
                transform: translateY(-2px);
            }
            
            .forgot-link {
                color: var(--blue);
                transition: color 0.3s ease;
            }
            
            .forgot-link:hover {
                color: #009ACD;
            }
            
            .checkbox:checked {
                background-color: var(--pink);
                border-color: var(--pink);
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex items-center justify-center bg-auth">
            <div class="w-full max-w-md space-y-8 auth-card p-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>