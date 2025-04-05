Epic Toy Store - Laravel Backend
Epic Toy Store is a full-featured e-commerce backend built with Laravel. It powers the Epic Toy Store mobile application developed using Flutter. This backend handles product management, user authentication, order processing, and API endpoints used by the Flutter frontend.

🚀 Features
RESTful API for product listing, cart, orders, etc.

User registration, login, and authentication (Laravel Sanctum/JWT/etc.)

Admin panel for managing products and orders (optional)

Laravel + MySQL + API-first architecture

Connected to a Flutter frontend app

🔗 Flutter Integration
This Laravel backend is fully integrated with a Flutter app (mobile frontend). The Flutter app consumes the API endpoints provided by this Laravel project.

Make sure to run the backend server before using the mobile app.

⚙️ Installation
Prerequisites
PHP >= 8.1

Composer

MySQL or compatible database

Laravel 10+

[Optional] Postman for testing API

Steps
bash
Copy
Edit
git clone https://github.com/your-username/epic-toy-store-backend.git
cd epic-toy-store-backend
cp .env.example .env
composer install
php artisan key:generate
Set your database credentials in .env

Run migrations:

bash
Copy
Edit
php artisan migrate
(Optional) Seed the database:

bash
Copy
Edit
php artisan db:seed
Start the server:

bash
Copy
Edit
php artisan serve
📡 API Endpoints
All API routes are located in routes/api.php.

Examples:

GET /api/products

POST /api/login

POST /api/orders

Use tools like Postman or directly integrate with the Flutter app.

📱 Flutter Frontend
The Flutter project can be found here. Make sure the Laravel server is running and accessible from the mobile app (especially if testing on an emulator or device).

🛠 Tech Stack
Laravel

MySQL

Flutter (frontend)

Laravel Sanctum / Passport / JWT (choose one, depending on your implementation)

✨ Credits
Developed by Joseph Chamoun & Rodrique El Khoury.
Feel free to contribute or fork the project!









