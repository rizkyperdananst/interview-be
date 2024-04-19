<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Home\PostController;
use App\Http\Controllers\API\Home\UserController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Home\LikesController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Home\CommentsController;
use App\Http\Controllers\API\Home\FollowersController;
use App\Http\Controllers\API\Home\FollowingController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::prefix('/home')->middleware('auth:api')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('followings', FollowingController::class);
    Route::apiResource('followers', FollowersController::class);
    Route::apiResource('likes', LikesController::class);
    Route::apiResource('comments', CommentsController::class);
});
