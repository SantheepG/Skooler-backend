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
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\Controller;

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



Route::post('add', [AuthController::class, 'AddStudent']);
Route::post('/checkid', [AuthController::class, 'CheckId']);

//Event

Route::get('/events', [EventController::class, 'fetchEvents']);
Route::put('/event/update', [EventController::class, 'UpdateEvent']);

Route::post('/event/add', [EventController::class, 'store']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::put('/events/{id}/edit', [EventController::class, 'update']);
Route::delete('/events/{id}/delete', [EventController::class, 'deleteEvent']);




Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/search', [UserController::class, 'search']);
Route::post('/cart/add', [UserController::class, 'addToCart']);

//Admin 
Route::post('adminsignup', [AdminController::class, 'AdminSignup']);
Route::post('adminlogin', [AdminController::class, 'AdminLogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('admin', [AdminController::class, 'Admin']);
    Route::post('adminlogout', [AdminController::class, 'AdminLogout']);
});
Route::post('/complaint/lodge', [ComplaintController::class, 'lodgeComplaint']);
Route::get('/complaints', [ComplaintController::class, 'fetchComplaints']);
Route::put('/stock/{id}/{stock}', [AdminController::class, 'updateStock']);

Route::post('adminlogin', [AdminController::class, 'AdminLogin']);
Route::get('fetchadmins', [AdminController::class, 'fetchAdmins']);
Route::get('fetchusers', [AdminController::class, 'fetchUsers']);
Route::put('changeuserstatus', [AdminController::class, 'ChangeUserStatus']);
Route::put('changeadminstatus', [AdminController::class, 'ChangeAdminStatus']);
Route::delete('/admin/delete/{id}', [AdminController::class, 'DeleteAdmin']);
//User 
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::put('user/reset', [AuthController::class, 'resetPassword']);
    Route::post('logout', [AuthController::class, 'logout']);
});
Route::put('user/update/name', [UserController::class, 'updateName']);
Route::put('user/update/address', [UserController::class, 'updateAddress']);

Route::put('update', [UserController::class, 'updateProfile']);
Route::post('/user/orders', [OrderController::class, 'getOrders']);
Route::get('/cart/{id}', [UserController::class, 'fetchCart']);
Route::get('/cart/stockcheck/{id}/{qty}', [UserController::class, 'StockCheck']);
Route::delete('/cart/delete/{id}', [UserController::class, 'deleteCartItem']);
Route::put('/updatecart/{id}/{qty}/{price}', [UserController::class, 'updateCartItem']);
Route::get('/cart/fetchtotal/{id}', [UserController::class, 'fetchSubtotal']);
Route::get('/categories', [UserController::class, 'getCategories']);
Route::get('/categories/{id}', [UserController::class, 'getSubcategories']);
Route::get('/products', [ProductController::class, 'fetchProducts']);

//Product 
Route::post('/avgrating', [ProductController::class, 'getAvgRating']);
Route::get('/product/{id}', [ProductController::class, 'getProduct']);
Route::put('/product/update', [ProductController::class, 'UpdateProduct']);
Route::post('/addproduct', [ProductController::class, 'addProduct']);
Route::delete('/deleteproduct/{id}', [ProductController::class, 'deleteProduct']);
Route::get('/products/related/{id}', [ProductController::class, 'fetchRelatedProducts']);
Route::post('/product/rate', [UserController::class, 'RateProduct']);
Route::post('category/add', [ProductController::class, 'addCategory']);
Route::post('subcategory/add', [ProductController::class, 'addSubCategory']);
//Complaints

Route::get('/q', [QuoteController::class, 'index']);


Route::post('login/a', [Controller::class, 'adminlogin']);
Route::post('logout/a', [Controller::class, 'adminlogout']);


Route::post('/user/placeorder', [OrderController::class, 'PlaceOrder']);
Route::post('/user/orders', [OrderController::class, 'getOrders']);

Route::get('/admin/orders', [OrderController::class, 'fetchAllOrders']);
Route::put('admin/roles/update', [AdminController::class, 'updateRoles']);

Route::post('/user/book', [EventController::class, 'bookaTicket']);
Route::post('/user/bookings', [EventController::class, 'fetchBookings']);


Route::post('user/complaint/lodge', [ComplaintController::class, 'lodgeComplaint']);
Route::post('user/complaints', [ComplaintController::class, 'fetchUserComplaints']);
Route::get('user/complaint/contact/{id}', [ComplaintController::class, 'fetchUserContact']);
Route::get('user/reviews/{id}', [UserController::class, 'getReviews']);

Route::delete('user/review/delete/{id}', [UserController::class, 'deleteReview']);
//order
Route::put('admin/order/update', [OrderController::class, 'updateOrder']);

//complaint
Route::put('admin/complaint/update', [ComplaintController::class, 'changeComplaintStatus']);
