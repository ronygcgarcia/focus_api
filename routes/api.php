<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GenreController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{book}', [BookController::class, 'show']);
    Route::post('/books/checkout', [CheckoutController::class, 'store']);

    Route::group(['middleware' => ['role:librarian']], function () {
        Route::post('/books', [BookController::class, 'store']);
        
        Route::get('/checkouts/{checkout}', [CheckoutController::class, 'show']);
        Route::put('/checkouts/{checkout}', [CheckoutController::class, 'update']);
    });

    Route::get('/checkouts', [CheckoutController::class, 'index']);
    Route::get('/genre', [GenreController::class, 'index']);
});