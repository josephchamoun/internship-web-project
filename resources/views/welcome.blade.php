<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Epic Toy Store</title>
    <style>
        :root {
            --pink: #FF69B4;
            --blue: #00BFFF;
            --dark-pink: #FF1493;
            --dark-blue: #009ACD;
            --bg-gradient: linear-gradient(135deg, #FFB6C1 0%, #87CEFA 100%);
        }
        
        body {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            margin: 0;
            background: var(--bg-gradient);
            min-height: 100vh;
        }

        /* Auth Links Styling */
        .auth-links {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .auth-links a {
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-weight: bold;
        }
        .login-btn {
            background: var(--pink);
            color: white;
            margin-right: 10px;
        }
        .register-btn {
            background: var(--blue);
            color: white;
        }
        .auth-links a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Main Content */
        .main-content {
            text-align: center;
            padding: 50px 20px;
        }

        /* Logo & Title */
        .logo-title {
            margin-bottom: 40px;
        }
        .logo {
            width: 150px;
            filter: drop-shadow(3px 3px 2px rgba(0,0,0,0.1));
        }
        .main-title {
            font-size: 3.5rem;
            background: linear-gradient(45deg, var(--pink), var(--blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 20px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        /* Content Sections */
        .content-box {
            background: rgba(255,255,255,0.9);
            border-radius: 20px;
            padding: 30px;
            margin: 30px auto;
            max-width: 800px;
            box-shadow: 0 10px 30px rgba(255,105,180,0.2);
            border: 2px solid var(--pink);
        }
        .content-text {
            color: #1E3A8A;
            font-size: 1.2rem;
            line-height: 1.8;
            margin: 0;
        }
        .decorative-line {
            height: 4px;
            background: linear-gradient(90deg, var(--pink), var(--blue));
            width: 200px;
            margin: 30px auto;
            border-radius: 2px;
        }

        /* Animated Elements */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .floating {
            animation: float 3s ease-in-out infinite;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Auth Links -->
    <div class="auth-links">
        <a href="{{ route('login') }}" class="login-btn">Login</a>
        <a href="{{ route('register') }}" class="register-btn">Register</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="logo-title">
            <div class="floating">
                <svg class="logo" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M50 0L100 50L50 100L0 50L50 0Z" fill="url(#pink-blue)"/>
                    <path d="M30 40L70 40L50 70L30 40Z" fill="white"/>
                    <defs>
                        <linearGradient id="pink-blue" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:var(--pink);stop-opacity:1"/>
                            <stop offset="100%" style="stop-color:var(--blue);stop-opacity:1"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <h1 class="main-title">Epic Toy Store</h1>
            <div class="decorative-line"></div>
        </div>

        <!-- Content Boxes -->
        <div class="content-box">
            <p class="content-text">
                🎉 Welcome to the most magical toy wonderland! 🌈 Discover toys that spark creativity, 
                encourage learning, and bring endless smiles to little faces. Our collection shines 
                with <span style="color: var(--pink)">pink</span> dreams and 
                <span style="color: var(--blue)">blue</span> adventures!
            </p>
        </div>

        <div class="content-box">
            <p class="content-text">
                🧸 Step into a world where every toy tells a story! From cuddly companions to 
                brain-teasing puzzles, we've got everything to make playtime unforgettable. 
                Watch as our <span style="color: var(--pink)">sparkling pink</span> joy meets 
                <span style="color: var(--blue)">ocean blue</span> creativity!
            </p>
        </div>

        <div class="content-box">
            <p class="content-text">
                ✨ Where imagination takes flight! 🚀 Our toys are carefully selected to blend 
                <span style="color: var(--pink)">rosy fun</span> with 
                <span style="color: var(--blue)">azure wonder</span>, creating the perfect mix 
                for magical childhood moments. Let the adventure begin!
            </p>
        </div>
    </div>
</body>
</html>