<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ItemSupplierController;
use App\Http\Controllers\CartController;

use App\Http\Middleware\CheckManagerRole;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

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

Route::middleware(['Manager'])->group(function () {

    Route::get('/addmanager', function () {
        return view('users.addmanager');
    })->name('addmanager');

    Route::get('/addsupply', function () {
        return view('items.additem');
    })->name('addsupply');




    Route::get('/itemsupplier', function () {
        return view('items.items');
    })->name('itemsupplier');
});

//Cart Routes
Route::post('cart/add/{itemId}', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('cart/remove/{itemId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('cart', [CartController::class, 'viewCart'])->name('cart.view');


Route::get('/dashboard', function() {
    return view('dashboard'); // The Blade view that contains the API fetch logic
});
Route::middleware(['Manager'])->group(function () {
    Route::get('/users', function() {
        return view('users.users'); // The Blade view that contains the API fetch logic
    });
});

/*

Route::get('/editsupply', function () {
    return view('items.editsupply');
})->name('items.editsupply');


Route::get('/users', [UserController::class, 'index'])->name('users.index');
//The ->name('users.index') method is used to assign a name to a route, which makes it easier to refer to that route elsewhere in your application.
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');


Route::get('/additem', function () {
    return view('items.additem');
})->name('items.additem');

Route::get('/items', [ItemSupplierController::class, 'index'])->name('items.index');//hode itemsupplier msh items 
Route::delete('/itemsupply/{id}', [ItemSupplierController::class, 'destroy'])->name('itemsupply.destroy');
Route::get('/itemsupply/{id}/edit', [ItemSupplierController::class, 'edit'])->name('itemsupply.edit');
Route::put('/itemsupply/{id}', [ItemSupplierController::class, 'update'])->name('itemsupply.update');




Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');*/




