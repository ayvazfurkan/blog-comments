<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
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

Route::get('/posts/{post}', [PostController::class, 'show'])
    ->name('show_post');
Route::get('/posts/{post}/comments', [CommentController::class, 'postComments'])
    ->name('show_post_comments');

Route::post('/posts/{post}/comment', [CommentController::class, 'commentToPost'])
    ->name('save_post_comment');
Route::post('/comments/{comment}/comment', [CommentController::class, 'commentToComment'])
    ->name('save_comment_comment');
