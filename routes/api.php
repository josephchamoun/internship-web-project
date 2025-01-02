<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemSupplierController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Middleware\CheckManagerRole;

Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});




Route::middleware('auth:sanctum')->get('/dashboard', [ItemController::class, 'index'])->name('dashboard');


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
    Route::post('/users/createmanager', [UserController::class, 'createManager']);
});


Route::prefix('itemsupplier')->middleware('Manager')->group(function () {
    Route::get('/', [ItemSupplierController::class, 'index']);
    Route::post('/addsupply', [ItemSupplierController::class, 'store']);
    Route::delete('/{id}', [ItemSupplierController::class, 'destroy']);
    Route::get('/{id}/edit', [ItemSupplierController::class, 'edit']);
    Route::put('/{id}', [ItemSupplierController::class, 'update']);
});


Route::prefix('suppliers')->middleware('Manager')->group(function () {
    Route::get('/', [SupplierController::class, 'index']);
    Route::post('/addsupplier', [SupplierController::class, 'store']);
});

Route::prefix('items')->middleware('Manager')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::post('/addnewitem', [ItemController::class, 'store']);
    Route::put('/edit/{id}', [ItemController::class, 'update']);

});








