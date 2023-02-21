<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'admin'],function ($router)
{
	Route::post('/auth/login',[AdminController::class,'login']);	
	Route::post('/registration',[AdminController::class,'register']);
});

Route::group(['middleware' => ['jwt.role:admin','jwt.auth'], 'prefix' => 'admin'], function ($router) 
{
	Route::post('/logout',[AdminController::class,'logout']);
	Route::get('/customer/list', [AdminController::class,'userProfile']);
	Route::get('/customer/view/{id}', [AdminController::class,'viewProfile']);
});

Route::group(['prefix' => 'customer'],function ($router)
{
	Route::post('/auth/login',[CustomerController::class,'login']);	
	Route::post('/registration',[CustomerController::class,'register']);
});

Route::group(['middleware' => ['jwt.role:customer','jwt.auth'], 'prefix' => 'customer'], function ($router) 
{
	Route::post('/logout',[CustomerController::class,'logout']);
});
