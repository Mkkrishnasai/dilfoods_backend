<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class,'register']);

Route::post('login', [\App\Http\Controllers\Auth\LoginController::class,'login']);

Route::group(['middleware' => 'auth:api'],function (){
    Route::get('items',[\App\Http\Controllers\ItemsController::class,'getItems']);

    Route::get('categories',[\App\Http\Controllers\CategoryController::class,'getCategories']);

    Route::post('order',[\App\Http\Controllers\OrderController::class,'makeOrder']);
});
