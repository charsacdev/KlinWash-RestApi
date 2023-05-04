<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuth;
use App\Http\Controllers\UserProfile;
use App\Http\Controllers\UserHomeView;
use App\Http\Controllers\UserOrders;

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

//----------USER AUTHENTCATION AND MANAGMENT-------------------//
Route::post('/register',[UserAuth::class,'register']);
Route::post('/login', [UserAuth ::class,'login']);
Route::post('/forgotpassword', [UserAuth::class,'ForgotPassword']);
Route::post('/newpassword', [UserAuth::class,'Newpassword']);




Route::group(['middleware' => ['auth:sanctum']], function() {

    #basic information for user
    Route::get('userprofileInfo',[UserProfile::class,'BasicProfileInfo']);
    Route::post('userprofileUpdate',[UserProfile::class,'UpdateBasicInfo']);
    Route::post('userpassword',[UserProfile::class,'UpdatePasswordinfo']);
    Route::post('profilephoto',[UserProfile::class,'ProfilePhoto']);

    #add management
    Route::post('add_address',[UserProfile::class,'AddAddress']);
    Route::delete('delete_address/{id}',[UserProfile::class,'DeleteAddress']);

    #getting all services
    Route::get('all_services',[UserHomeView::class,'get_services']);
    Route::get('get_all_category/{id}',[UserHomeView::class,'get_services_categories']);

    #adding to cart
    Route::get('add_to_cart/{id}',[UserOrders::class,'AddToCart']);

    
});

