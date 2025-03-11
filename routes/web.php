<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ItemSupplierController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ItemOrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\CheckManagerRole;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ForgetPasswordController;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgetPassword;
use App\Http\Controllers\ForgetPassController;
use App\Http\Controllers\PasswordResetLinkController;


Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'update'])->name('contact.update');
Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/
//Route::get('/dashboard', [ItemController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


//mn hon ana

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::middleware('auth-sanctum')->group(function () {
    Route::get('/myorders', function () {
        return view('orders.myorders');
    })->name('myorders');

    Route::get('/myorder/details/{order_id}', function ($order_id) {
        return view('orders.orderdetails', ['order_id' => $order_id]);
    });
});




Route::middleware(['Manager'])->group(function () {


    Route::middleware('auth:sanctum')->get('/stats', [StatsController::class, 'index'])->name('stats');








    Route::middleware('auth:sanctum')->get('/addmanager', function () {
        return view('users.addmanager');
    })->name('addmanager');

    Route::get('/additem', function () {
        return view('items.addnewitem');
    })->name('additem');
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');

    Route::get('/addsupply', function () {
        return view('items.additem');
    })->name('addsupply');








    Route::get('/usersorders', function () {
        return view('orders.usersorders');
    })->name('usersorders');

    Route::get('/userorder/details/{order_id}', function ($order_id) {
        return view('orders.usersordersdetails', ['order_id' => $order_id]);
    });





    Route::middleware('auth:sanctum')->get('/addsupplier', function () {
        return view('suppliers.addsupplier');
    })->name('addsupplier');

    Route::middleware('auth:sanctum')->get('/suppliers/{id}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');

    Route::middleware('auth:sanctum')->get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::get('/addcategories', function () {
        return view('categories.addcategories');
    })->name('addcategories');

    Route::middleware('auth:sanctum')->get('/suppliers', function () {
        return view('suppliers.suppliers');
    })->name('suppliers');





    Route::middleware('auth:sanctum')->get('/itemsupplier', function () {
        return view('items.items');
    })->name('itemsupplier');
    Route::middleware('auth:sanctum')->get('/itemsupplier/{id}/edit', [ItemSupplierController::class, 'edit']);
});

//Cart Routes

Route::middleware('auth:sanctum')->get('cart', [CartController::class, 'viewCart'])->name('cart.view');


Route::middleware('auth:sanctum')->get('/dashboard', function() {
    return view('dashboard'); // The Blade view that contains the API fetch logic
})->name('dashboard');
Route::middleware(['Manager'])->group(function () {
    Route::middleware('auth:sanctum')->get('/users', function() {
        return view('users.users'); // The Blade view that contains the API fetch logic
    })->name('users');
});

Route::middleware(['Manager'])->group(function () {
    Route::middleware('auth:sanctum')->get('/categories', function() {
        return view('categories.categories'); // The Blade view that contains the API fetch logic
    })->name('categories');







    Route::middleware('auth:sanctum')->get('/messages', function() {
        return view('messages.messages'); // The Blade view that contains the API fetch logic
    })->name('messages');
});



Route::get('/forgetpassword', function(){
    try {
        \Illuminate\Support\Facades\Mail::to('chamounjoseph78@gmail.com')->send(new App\Mail\ForgetPassword());
        return 'Email was sent';
    } catch (\Exception $e) {
        return 'Failed to send email: ' . $e->getMessage();
    }
});




Route::post('/send-forget-password-email', [ForgetPassController::class, 'sendForgetPasswordEmail'])->name('send.forget.password.email');

Route::get('/reset-password/{token}', [ForgetPassController::class, 'showResetForm'])->name('password.reset');
Route::put('/reset-password', [ForgetPassController::class, 'resetPassword'])->name('password.update2');



