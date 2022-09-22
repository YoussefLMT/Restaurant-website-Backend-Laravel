<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::get('meals', [MealController::class, 'getMeals']);
Route::post('add-meal', [MealController::class, 'addMeal']);
Route::get('get-meal/{id}', [MealController::class, 'getMeal']);
Route::put('update-meal/{id}', [MealController::class, 'updateMeal']);
Route::delete('delete-meal/{id}', [MealController::class, 'deleteMeal']);
Route::get('specific-meals', [MealController::class, 'getSpecificMeals']);


Route::get('users', [UserController::class, 'getUsers']);
Route::post('add-user', [UserController::class, 'addUser']);
Route::get('get-user/{id}', [UserController::class, 'getUser']);
Route::put('update-user/{id}', [UserController::class, 'updateUser']);
Route::delete('delete-user/{id}', [UserController::class, 'deleteUser']);




Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('logout', [AuthController::class, 'logOut']);

    Route::post('add-to-cart/{product_id}', [CartController::class, 'addToCart']);
    Route::get('cart-count', [CartController::class, 'getCartCount']);

});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
