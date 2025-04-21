<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// // uji route untuk controller /category
// Route::get('/category', [CategoryController::class, 'index']);
// Route::get('/book', [BookController::class, 'index']);


Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Hanya setelah login
Route::middleware('auth')->group(function () {
    
    Route::get('/', [WelcomeController::class, 'index']);

    // Route untuk role admin
    Route::middleware('role:admin')->group(function () {
        Route::prefix('book')->group(function () {
            Route::get('/', [BookController::class, 'index']);
            Route::post('/list', [BookController::class, 'list']);
            Route::get('/create_ajax', [BookController::class, 'create_ajax']);
            Route::post('/ajax', [BookController::class, 'store_ajax']);
            Route::get('/{id}/show', [BookController::class, 'show']);
            Route::get('/{id}/edit_ajax', [BookController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [BookController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [BookController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [BookController::class, 'delete_ajax']);
            Route::delete('/{id}', [BookController::class, 'destroy']);
        });

        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::post('/list', [CategoryController::class, 'list']);
            Route::get('/create_ajax', [CategoryController::class, 'create_ajax']);
            Route::post('/ajax', [CategoryController::class, 'store_ajax']);
            Route::get('/{id}/show', [CategoryController::class, 'show']);
            Route::get('/{id}/edit_ajax', [CategoryController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [CategoryController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [CategoryController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [CategoryController::class, 'delete_ajax']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/list', [UserController::class, 'list']);
            Route::get('/create_ajax', [UserController::class, 'create_ajax']);
            Route::post('/ajax', [UserController::class, 'store_ajax']);
            Route::get('/{id}/show', [UserController::class, 'show']);
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
        });

        Route::prefix('borrow')->group(function () {
            Route::get('/', [BorrowingController::class, 'index']);
            Route::post('/list', [BorrowingController::class, 'list']);

            // Route::get('/create_ajax', [UserController::class, 'create_ajax']);
            // Route::post('/ajax', [UserController::class, 'store_ajax']);
            Route::get('/{id}/show', [BorrowingController::class, 'show']);
            Route::get('/{id}/edit_ajax', [BorrowingController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [BorrowingController::class, 'update_ajax']);
            Route::get('/{id}/delete_ajax', [BorrowingController::class, 'confirm_ajax']);
            Route::delete('/{id}/delete_ajax', [BorrowingController::class, 'delete_ajax']);
            Route::delete('/{id}', [BorrowingController::class, 'destroy']);
        });
    });

    // Route untuk role mahasiswa
    Route::middleware('role:mahasiswa')->group(function () {
        Route::prefix('booking')->group(function () {
            Route::get('/', [BookingController::class, 'index']);
            Route::post('/list', [BookingController::class, 'list_booking']);
            Route::get('/{id}/show', [BookingController::class, 'show']);
            Route::get('/create_ajax', [BookingController::class, 'create_ajax']);
            Route::post('/ajax', [BookingController::class, 'store_ajax']);
        });
    });
    
});
