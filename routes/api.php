<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\CommentController;

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
//Comments
Route::controller(CommentController::class)->prefix('comment')->group(function(){
    Route::get('/', 'index')->name('comment.index');
    Route::post('/', 'store');
    Route::get('/edit/{comment}', 'edit');
    Route::post('/update/{comment}', 'update');
    Route::get('/delete/{comment}', 'delete');
    Route::get('/accept/{comment}', 'accept');
    Route::get('/reject/{comment}', 'reject');
});

//Article
Route::resource('/article', ArticleController::class)->middleware('auth:sanctum');
Route::get('/article/{article}', [ArticleController::class, 'show'])->name('article.show')->middleware('stat', 'auth:sanctum');

//Auth
Route::post('/auth/registr', [AuthController::class, 'registr']);
Route::get('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/authenticate', [AuthController::class, 'authenticate']);
Route::get('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
