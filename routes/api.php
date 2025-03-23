<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
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


    Route::post('/signup', [UserController::class, 'signup']);
    Route::post('/login', [UserController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::get('/profile', [UserController::class, 'profile']);
        Route::post('/logout', [UserController::class, 'logout']);
        Route::post('/update', [UserController::class, 'update']);

});

    /*التصنيفات*/
Route::get('/gat_all_categories', [CategoryController::class, 'index']);
Route::middleware('auth:api')->group(function () {
    Route::post('/create_categories', [CategoryController::class, 'create']);
    Route::post('/update_categories/{category_id}', [CategoryController::class, 'update']);
    Route::delete('/delete_categories/{category_id}', [CategoryController::class, 'destroy']);
});

Route::get('/get_articles_By_Category/{category_id}', [ArticleController::class, 'articlesByCategory']);
Route::get('/get_articles/{article_id}', [ArticleController::class, 'show']);
Route::post('/search', [ArticleController::class, 'searchByWebsiteName']);
Route::middleware('auth:api')->group(function () {
    Route::post('/create_article', [ArticleController::class, 'create']);
    Route::post('/update_article/{article_id}', [ArticleController::class, 'update']);
    Route::delete('/delete_article/{article_id}', [ArticleController::class, 'destroy']);
    Route::delete('/delete_image/{image_id}', [ArticleController::class, 'destroyImage']);
});
