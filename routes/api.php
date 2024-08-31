<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventJoinController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\EventStageController;
use App\Http\Controllers\User\PaymentController as PaymenCon;
use App\Http\Controllers\Api\EventBookController;
use App\Http\Controllers\Admin\AdminWithdrawRequestController;

// use App\Http\Controllers\Api\RolesController;
// use App\Http\Controllers\Api\PermissionController;

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
Route::any('cashfree-callback', [PaymenCon::class, 'cashfree_recharge_callback']);
Route::any('upigateway-callback', [PaymenCon::class, 'upigateway_recharge_callback']);
Route::any('mpay-callback', [PaymenCon::class, 'Mpay_recharge_callback']);
Route::any('recharge-payment-status', [PaymenCon::class, 'recharge_status']);
Route::any('upitel-recharge-payment-status', [PaymenCon::class, 'upitel_recharge_status']);
Route::post('login', [AuthController::class, 'login']);
Route::post('creator-login', [AuthController::class, 'creatorLogin']);
Route::post('social-login', [AuthController::class, 'socialLogin']);
Route::post('register', [AuthController::class, 'register']);
Route::get('get-all-creators', [UserController::class, 'list']);
Route::get('get-creator-details', [UserController::class, 'details']);
Route::get('get-all-events', [EventController::class, 'list']);
Route::get('get-active-events', [EventController::class, 'activeEvents']);
Route::get('get-event-details', [EventController::class, 'details']);
Route::get('banners', [BannerController::class, 'list']);
Route::get('search-home', [BannerController::class, 'search']);

Route::get('payment-processing', [PaymentController::class, 'paymentProcessing']);
Route::post('cashfree-gateway-success', [PaymentController::class, 'successCashfree']);
Route::get('paykun-gateway-success', [PaymentController::class, 'successPaykun']);
Route::post('inapp-gateway-success', [PaymentController::class, 'inAppSuccess']);
Route::get('paykun-gateway-fail', [PaymentController::class, 'failPaykun']);

Route::get('send-firebase-message', [EventController::class, 'sendFcmMessage']);
Route::any('/withdrawal-status-check', [AdminWithdrawRequestController::class, 'acceptRequestStatusWithdrawal']);
Route::group(['middleware' => 'auth:api'], function () {

    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    Route::get('get-user-profile', [UserController::class, 'profile']);
    Route::get('wallet-transactions', [UserController::class, 'transactions']);
    Route::post('update-user-profile', [UserController::class, 'updateProfile']);
    Route::post('update-user-fcm-token', [UserController::class, 'updateFCMToken']);
    Route::post('update-profile-pic', [UserController::class, 'updateProfilePicture']);
    Route::post('event-create', [EventController::class, 'create']);
    Route::post('update-event-status', [EventController::class, 'updateStatus']);
    Route::post('join-event', [EventJoinController::class, 'create']);
    Route::post('book-event', [EventJoinController::class, 'book']);

    Route::get('creator-upcoming-events', [EventController::class, 'creatorEvents']);
    Route::get('user-upcoming-virtual-bookings', [EventController::class, 'userVirtualBookings']);
    Route::get('user-upcoming-stage-bookings', [EventController::class, 'userStageBookings']);

    Route::get('get-creator-active-event', [EventController::class, 'creatorActiveEvent']);
    Route::get('cashfree-gateway', [PaymentController::class, 'create']);
    Route::post('create-order', [PaymentController::class, 'createOrder']);
    Route::get('get-active-gateways', [PaymentController::class, 'gateways']);

    Route::get('get-stage-categories', [EventStageController::class, 'categories']);
    Route::post('book-stage-event', [EventBookController::class, 'create']);
    Route::get('get-booking-details', [EventBookController::class, 'details']);


    // //only those have manage_user permission will get access
    // Route::group(['middleware' => 'can:manage_user'], function(){
    // 	Route::get('/users', [UserController::class,'list']);
    // 	Route::post('/user/create', [UserController::class,'store']);
    // 	Route::get('/user/{id}', [UserController::class,'profile']);
    // 	Route::get('/user/delete/{id}', [UserController::class,'delete']);
    // 	Route::post('/user/change-role/{id}', [UserController::class,'changeRole']);
    // });
});
