<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CompanyController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [RegisterController::class, 'store']);
Route::post('login', [LoginController::class, 'login']);
Route::get('all-events/{keyword?}', [EventController::class, 'all_event']);
Route::get('event-detail/{id}/{user_id?}', [EventController::class, 'event_detail']);
Route::get('company-detail/{id}', [CompanyController::class, 'company_detail']);

Route::middleware('auth:api')->group(function () {

    Route::get('user-info', [ProfileController::class, 'user_profile_info']);
    Route::get('user-notifications', [ProfileController::class, 'user_notifications']);

    Route::put('user-info-update', [ProfileController::class, 'user_profile_update']);
    Route::get('logout', [LoginController::class, 'logout']);


    Route::post('follow-company/{id}', [CompanyController::class, 'follow_company']);



    Route::resource('events', EventController::class);

});
