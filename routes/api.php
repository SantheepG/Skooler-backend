<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('signup', [AuthController::class, 'signup']);

Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::post('add', [AuthController::class, 'AddStudent']);
Route::put('update', [AuthController::class, 'updateProfile']);
Route::get('/events', [EventController::class, 'index']);
Route::post('/event', [EventController::class, 'store']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::put('/events/{id}/edit', [EventController::class, 'update']);
Route::delete('/events/{id}/delete', [EventController::class, 'destroy']);
Route::post('/orders', [OrderController::class, 'getOrders']);
Route::post('/checkid', [AuthController::class, 'CheckId']);

Route::get('/categories', [UserController::class, 'getCategories']);
Route::get('/categories/{id}', [UserController::class, 'getSubcategories']);
Route::get('/products', [UserController::class, 'getProducts']);
Route::post('/search', [UserController::class, 'search']);
Route::post('/cart/add', [UserController::class, 'addToCart']);

//Admin
Route::post('adminsignup', [AdminController::class, 'AdminSignup']);

Route::post('adminlogin', [AdminController::class, 'AdminLogin']);

Route::post('adminlogin', [AdminController::class, 'AdminLogin']);
Route::get('fetchadmins', [AdminController::class, 'fetchAdmins']);
Route::get('fetchusers', [AdminController::class, 'fetchUsers']);
Route::put('changeuserstatus', [AdminController::class, 'ChangeUserStatus']);
Route::put('changeadminstatus', [AdminController::class, 'ChangeAdminStatus']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('admin', [AdminController::class, 'Admin']);
    Route::post('adminlogout', [AdminController::class, 'AdminLogout']);
});
