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



Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::put('user/reset', [AuthController::class, 'resetPassword']);
    Route::get('user/cart', [UserController::class, 'fetchCart']);
    Route::get('/user/notifications/fetch', [UserController::class, 'fetchNotifications']);
    Route::get('/user/notifications/change', [UserController::class, 'updateAlertStatus']);
    Route::post('user/logout', [AuthController::class, 'logout']);
});


Route::post('user/login', [AuthController::class, 'login']);

Route::post('user/signup/validate', [AuthController::class, 'ValidationCheck']);
Route::post('user/signup', [AuthController::class, 'signup']);

Route::delete('user/delete/{id}', [AuthController::class, 'deleteUser']);

Route::post('user/avatar/update', [UserController::class, 'updateProfilePic']);
Route::get('user/avatar/get/{id}', [UserController::class, 'getAvatar']);
Route::delete('user/delete/{id}', [AuthController::class, 'deleteUser']);
Route::post('/cart/add', [UserController::class, 'addToCart']);
Route::put('/updatecart/{id}/{qty}/{price}', [UserController::class, 'updateCartItem']);
Route::delete('/cart/delete/{id}', [UserController::class, 'deleteCartItem']);

Route::put('user/update/name', [UserController::class, 'updateName']);
Route::put('user/update/address', [UserController::class, 'updateAddress']);
Route::put('update', [UserController::class, 'updateProfile']);
Route::get('/user/orders/{id}', [OrderController::class, 'getUserOrders']);
Route::get('user/complaints/{id}', [ComplaintController::class, 'fetchUserComplaints']);
Route::post('user/complaint/lodge', [ComplaintController::class, 'lodgeComplaint']);


Route::post('/user/placeorder/slip/upload', [OrderController::class, 'uploadBankSlip']);
Route::post('/user/placeorder', [OrderController::class, 'PlaceOrder']);
Route::post('/user/orders', [OrderController::class, 'getOrders']);
Route::get('user/reviews/{id}', [UserController::class, 'getReviews']);
Route::delete('user/review/delete/{id}', [UserController::class, 'deleteReview']);
Route::post('/product/rate', [UserController::class, 'rateProduct']);

Route::post('/user/card/add', [UserController::class, 'addCard']);
Route::get('user/card/{id}', [UserController::class, 'getCards']);

Route::get('fetchusers', [UserController::class, 'fetchUsers']);
Route::put('/user/updatestatus', [UserController::class, 'changeUserStatus']);

Route::get('user/complaint/contact/{id}', [UserController::class, 'fetchUserContact']);

Route::get('/cart/stockcheck/{id}/{qty}', [UserController::class, 'StockCheck']);
Route::get('/cart/fetchtotal/{id}', [UserController::class, 'fetchSubtotal']);


//Student
Route::post('add', [AuthController::class, 'AddStudent']);
Route::post('/checkid', [AuthController::class, 'CheckId']);

//Admin
Route::post('login/admin', [AdminController::class, 'adminlogin']);
Route::post('adminsignup', [AdminController::class, 'adminSignup']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('admin', [AdminController::class, 'admin']);
    Route::put('admin/reset/pwd', [AdminController::class, 'resetPassword']);
    Route::post('logout/admin', [AdminController::class, 'adminlogout']);
});

Route::put('/admin/updatestatus', [AdminController::class, 'changeAdminStatus']);
Route::delete('/admin/delete/{id}', [AdminController::class, 'deleteAdmin']);

Route::get('/school/fetch/stats', [AdminController::class, 'fetchStats']);

Route::get('fetchadmins', [AdminController::class, 'fetchAdmins']);
Route::put('admin/roles/update', [AdminController::class, 'updateRoles']);
Route::put('admin/update/name', [AdminController::class, 'updateDetails']);

//Product 
Route::post('/avgrating', [ProductController::class, 'getAvgRating']);
Route::get('/product/{id}', [ProductController::class, 'getProduct']);
Route::put('/product/update', [ProductController::class, 'updateProduct']);
Route::post('/addproduct', [ProductController::class, 'addProduct']);
Route::delete('/deleteproduct/{id}', [ProductController::class, 'deleteProduct']);
Route::get('/products/related/{id}', [ProductController::class, 'fetchRelatedProducts']);
Route::get('/categories', [ProductController::class, 'getCategories']);
Route::get('/categories/{id}', [ProductController::class, 'getSubcategories']);
Route::get('/products', [ProductController::class, 'fetchProducts']);
Route::post('category/add', [ProductController::class, 'addCategory']);
Route::post('subcategory/add', [ProductController::class, 'addSubCategory']);
Route::put('/stock/{id}/{stock}', [ProductController::class, 'updateStock']);
Route::get('/products/featured', [ProductController::class, 'fetchFeturedProducts']);

Route::post('product/imgs/add', [ProductController::class, 'addProductImgs']);
Route::post('product/imgs/update', [ProductController::class, 'updateProductImgs']);
Route::put('/product/img/delete', [ProductController::class, 'deleteProductImg']);

Route::delete('/img/delete', [ProductController::class, 'deleteImg']);


//Search results
Route::post('/search', [ProductController::class, 'search']);

//Event
Route::get('/events/fetch', [EventController::class, 'fetchEvents']);
Route::get('/upcoming/events/fetch', [EventController::class, 'fetchUpcomingEvents']);
Route::get('/event/get/{id}', [EventController::class, 'show']);
Route::put('/event/update', [EventController::class, 'UpdateEvent']);
Route::post('/event/add', [EventController::class, 'store']);
Route::put('/events/{id}/edit', [EventController::class, 'update']);
Route::delete('/events/{id}/delete', [EventController::class, 'deleteEvent']);

Route::post('/user/event/booking/uploadslip', [EventController::class, 'uploadBookingBankSlip']);
Route::post('/user/book', [EventController::class, 'bookaTicket']);
Route::put('/user/booking/statusupdate', [EventController::class, 'updateBookingStatus']);

Route::post('/user/bookings', [EventController::class, 'fetchUserBookings']);
Route::get('fetch/bookings', [EventController::class, 'fetchAllBookings']);
Route::delete('booking/delete/{id}', [EventController::class, 'deleteBooking']);

//Complaints
Route::get('/complaints', [ComplaintController::class, 'fetchComplaints']);
Route::post('user/complaint/lodge', [ComplaintController::class, 'lodgeComplaint']);
Route::put('admin/complaint/update', [ComplaintController::class, 'changeComplaintStatus']);
Route::delete('complaint/delete/{id}', [ComplaintController::class, 'deleteComplaint']);
//User 


//order
Route::put('admin/order/update', [OrderController::class, 'updateOrder']);
Route::get('/admin/orders', [OrderController::class, 'fetchAllOrders']);
Route::delete('/admin/order/delete/{id}', [OrderController::class, 'deleteOrder']);
