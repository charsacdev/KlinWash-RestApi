<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsercreateAccount;
use App\Http\Controllers\UserLogin;
use App\Http\Controllers\UserProfile;
use App\Http\Controllers\BuisnessProfile;

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
Route::post('/register',[UsercreateAccount::class,'register']);
Route::post('/login', [UserLogin ::class,'login']);
Route::post('/forgotpassword', [UserLogin ::class,'ForgotPassword']);
Route::post('/newpassword', [UserLogin ::class,'Newpassword']);




Route::group(['middleware' => ['auth:sanctum']], function() {

    //basic information for user
    Route::get('userprofileInfo',[UserProfile::class,'BasicProfileInfo']);
    Route::post('userprofileUpdate',[UserProfile::class,'UpdateBasicInfo']);
    Route::post('userpassword',[UserProfile::class,'UpdatePasswordinfo']);
    Route::post('getlocation',[UserProfile::class,'Getlocation']);
    Route::post('profilephoto',[UserProfile::class,'ProfilePhoto']);

    //business information for user
    Route::get('businessInfo',[BuisnessProfile::class,'BusinessProfileInfo']);
    Route::post('businessInfoUpdate',[BuisnessProfile::class,'UpdateBasicInfo']);
    Route::post('businessOwner',[BuisnessProfile::class,'BusinessOwners']);
    Route::post('aboutus',[BuisnessProfile::class,'AboutBusiness']);

    //bank information
    Route::get('allbanks',[BuisnessProfile::class,'BanksInfo']);
    Route::post('UpdateBankInformation',[BuisnessProfile::class,'BankAccountInformation']);
    


    
});

