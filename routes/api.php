<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemSupplierController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ItemOrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RegistrationloginController;
use App\Http\Middleware\CheckManagerRole;
use App\Http\Controllers\ContactController;
use App\Models\User;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\AuthController;
use Laravel\Socialite\Facades\Socialite;







Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});




Route::middleware('auth:sanctum')->get('/dashboard', [ItemController::class, 'index'])->name('dashboard');
Route::get('/dashboard2', [ItemController::class, 'index2']);//kermel api



Route::middleware('auth:sanctum')->group(function () {
    // Existing '/user' route for the authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();

        


        
    });

    // Your custom user routes
    Route::prefix('users')->group(function () {
        Route::middleware('auth:sanctum')->get('/', [UserController::class, 'index'])->name('users.index');
        //Route::post('/createmanager', [UserController::class, 'storeManager']); // Adjusted route path
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    });
    
});
Route::middleware(['Manager'])->group(function () {
    

    Route::middleware('auth:sanctum')->post('/users/createmanager', [UserController::class, 'createManager']);
});



Route::prefix('itemsupplier')->middleware('Manager')->group(function () {
    Route::middleware('auth:sanctum')->get('/', [ItemSupplierController::class, 'index']);
    Route::middleware('auth:sanctum')->post('/addsupply', [ItemSupplierController::class, 'store']);
    Route::middleware('auth:sanctum')->put('/edit/{id}', [ItemSupplierController::class, 'update']);
    Route::middleware('auth:sanctum')->delete('/delete/{id}', [ItemSupplierController::class, 'destroy']);
});


Route::prefix('suppliers')->middleware(['auth:sanctum', 'Manager'])->group(function () {
    Route::middleware('auth:sanctum')->get('/', [SupplierController::class, 'index']);
    Route::middleware('auth:sanctum')->post('/addsupplier', [SupplierController::class, 'store']);
    Route::middleware('auth:sanctum')->put('/edit/{id}', [SupplierController::class, 'update']);
    Route::middleware('auth:sanctum')->delete('/delete/{id}', [SupplierController::class, 'destroy']);
});

Route::prefix('items')->middleware('Manager')->group(function () {
    Route::middleware('auth:sanctum')->get('/', [ItemController::class, 'index']);
    Route::middleware('auth:sanctum')->post('/addnewitem', [ItemController::class, 'store']);
    Route::middleware('auth:sanctum')->put('/edit/{id}', [ItemController::class, 'update']);
    Route::middleware('auth:sanctum')->delete('/delete/{id}', [ItemController::class, 'destroy']);

});

Route::prefix('orders')->middleware('auth:sanctum')->group(function () { //my orders
    Route::middleware('auth:sanctum')->get('/myorders', [OrderController::class, 'MyOrdersindex']);
    Route::middleware('auth:sanctum')->get('/myorder/details/{orderId}', [ItemOrderController::class, 'MyOrderDetails']);
    Route::middleware('auth:sanctum')->post('/addorder', [OrderController::class, 'saveOrder']);
    Route::middleware('auth:sanctum')->put('/update/{id}', [ItemOrderController::class, 'update']);
    Route::middleware('auth:sanctum')->delete('/delete/{id}', [OrderController::class, 'destroy']);
});

Route::prefix('orders')->middleware('Manager')->group(function () { //users orders
    
    Route::middleware('auth:sanctum')->get('/', [OrderController::class, 'index']);
    Route::middleware('auth:sanctum')->get('/userorder/details/{orderId}', [ItemOrderController::class, 'OrderDetails']);
    Route::middleware('auth:sanctum')->put('/userorder/update/{id}', [OrderController::class, 'updatePending']);


});

Route::middleware('auth:sanctum')->get('/categories', [CategoryController::class, 'index']);
Route::prefix('categories')->middleware('Manager')->group(function () {
    
    Route::middleware('auth:sanctum')->post('/addcategory', [CategoryController::class, 'store'])->name('categories.store');
    Route::middleware('auth:sanctum')->put('/edit/{id}', [CategoryController::class, 'update']);
    Route::middleware('auth:sanctum')->delete('/delete/{id}', [CategoryController::class, 'destroy']);

});

Route::prefix('users')->middleware('Manager')->group(function () {
    Route::middleware('auth:sanctum')->delete('/{id}', [UserController::class, 'destroy']);



});

Route::prefix('messages')->middleware('auth:sanctum')->group(function () {
    Route::middleware('Manager')->get('/', [MessageController::class, 'index']);
    Route::post('/postmessage', [MessageController::class, 'store'])->name('messages.store');
});

Route::prefix('register')->group(function () {
   
    Route::post('/apiregister', [RegistrationloginController::class, 'register']);

});
Route::post('/login/apilogin', [RegistrationloginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/contact/info', [ContactController::class, 'index']);

Route::prefix('person')->middleware('auth:sanctum')->group(function () {
    Route::put('/update', [PersonController::class, 'updatePerson']);
    Route::put('/updatepassword', [PersonController::class, 'updateUserPassword']);
    Route::delete('/delete', [PersonController::class, 'deleteUser']);
});




