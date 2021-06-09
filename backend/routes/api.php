<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/name', [AuthController::class, 'getName']);
Route::group(['middeleware' => ['auth:sanctum']], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->post('/edit/profile', [\App\Http\Controllers\AuthController::class, 'editProfile']);
Route::get('/profileData/{id}', [AuthController::class, 'getProfileData']);

Route::get('/category', [\App\Http\Controllers\APIController::class, 'showCategories']);
Route::get('/foodDetails/{id}', [\App\Http\Controllers\APIController::class, 'getDetails']);
Route::get('/meals/{category}', [\App\Http\Controllers\APIController::class, 'showMeals']);
Route::get('/search/{word}', [\App\Http\Controllers\APIController::class, 'search']);

Route::middleware('auth:sanctum')->post('/favorite', [\App\Http\Controllers\FoodController::class, 'addFav']);
Route::middleware('auth:sanctum')->get('/favorite/list', [\App\Http\Controllers\FoodController::class, 'showFav']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
