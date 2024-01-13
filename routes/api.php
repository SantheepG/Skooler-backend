<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ComplaintController;
use App\Models\Admin;
use App\Models\Product;

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
Route::get('/events', [EventController::class, 'fetchEvents']);
Route::put('/event/update', [EventController::class, 'UpdateEvent']);

Route::post('/event/add', [EventController::class, 'store']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::put('/events/{id}/edit', [EventController::class, 'update']);
Route::delete('/events/{id}/delete', [EventController::class, 'deleteEvent']);
Route::post('/orders', [OrderController::class, 'getOrders']);
Route::post('/checkid', [AuthController::class, 'CheckId']);

Route::get('/categories', [UserController::class, 'getCategories']);
Route::get('/categories/{id}', [UserController::class, 'getSubcategories']);
Route::get('/products', [UserController::class, 'getProducts']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/search', [UserController::class, 'search']);
Route::post('/cart/add', [UserController::class, 'addToCart']);

//Admin
Route::post('adminsignup', [AdminController::class, 'AdminSignup']);

Route::post('adminlogin', [AdminController::class, 'AdminLogin']);

Route::post('adminlogin', [AdminController::class, 'AdminLogin']);
Route::get('fetchadmins', [AdminController::class, 'fetchAdmins']);
Route::get('fetchusers', [AdminController::class, 'fetchUsers']);
//Route::get('fetchproducts', [AdminController::class, 'fetchProducts']);
Route::put('changeuserstatus', [AdminController::class, 'ChangeUserStatus']);
Route::put('changeadminstatus', [AdminController::class, 'ChangeAdminStatus']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('admin', [AdminController::class, 'Admin']);
    Route::post('adminlogout', [AdminController::class, 'AdminLogout']);
});

Route::post('/avgrating', [ProductController::class, 'getAvgRating']);
Route::get('/product/{id}', [ProductController::class, 'getProduct']);
Route::put('/product/update', [ProductController::class, 'UpdateProduct']);
Route::get('/cart/{id}', [UserController::class, 'fetchCart']);
Route::get('/cart/stockcheck/{id}/{qty}', [UserController::class, 'StockCheck']);
Route::delete('/cart/delete/{id}', [UserController::class, 'deleteCartItem']);
Route::put('/updatecart/{id}/{qty}/{price}', [UserController::class, 'updateCartItem']);
Route::get('/cart/fetchtotal/{id}', [UserController::class, 'fetchSubtotal']);

Route::post('/addproduct', [ProductController::class, 'addProduct']);
Route::delete('/deleteproduct/{id}', [ProductController::class, 'deleteProduct']);

Route::put('/stock/{id}/{stock}', [AdminController::class, 'updateStock']);

Route::get('/products/related/{id}', [UserController::class, 'fetchRelatedProducts']);
Route::post('/product/rate', [UserController::class, 'RateProduct']);

Route::post('/complaint/lodge', [ComplaintController::class, 'lodgeComplaint']);
Route::get('/complaints', [ComplaintController::class, 'fetchComplaints']);
