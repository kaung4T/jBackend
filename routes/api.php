<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('create_token', [AuthController::class, 'create_token']);
    Route::post('logout_token', [AuthController::class, 'logout_token']);
    Route::post('refresh_token', [AuthController::class, 'refresh_token']);
    Route::post('me', 'AuthController@me');
});

Route::post('create_user', [AuthController::class, 'create_user'])->name('create_user');
Route::post('login_user', [AuthController::class, 'login_user'])->name('login_user');
Route::get('all_user', [AuthController::class, 'all_user'])->name('all_user');

Route::get('all_item', [ApiController::class, 'all_item'])->name('all_item')->middleware('jwt.verify');
Route::post('create_item', [ApiController::class, 'create_item'])->name('create_item')->middleware('jwt.verify');
Route::put('update_item/{id}', [ApiController::class, 'update_item'])->name('update_item')->middleware('jwt.verify');
Route::delete('delete_item/{id}', [ApiController::class, 'delete_item'])->name('delete_item')->middleware('jwt.verify');
