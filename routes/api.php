<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
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





Route::middleware(['token'])->get('/test', function (Request $request) {

    $id = $request->input('user_id');
    return response()->json(['User Id' => $id, 'message' => 'Api connected succesfully'], 200);
});

Route::middleware('token')->group(function () {
    Route::get('/blogs', [BlogController::class, 'index']);        // Fetches all blogs with posts 
    Route::post('/blogs', [BlogController::class, 'create']);      // Create a new blog
    Route::get('/blogs/{id}', [BlogController::class, 'show']);    // Show a specific blog
    Route::put('/blogs/{id}', [BlogController::class, 'update']);  // Update a blog
    Route::delete('/blogs/{id}', [BlogController::class, 'delete']);  // Delete a blog
});

Route::middleware('token')->group(function () {
    Route::get('/posts/{id}', [PostController::class, 'index']); // Fetches all posts with comments and likes 
    Route::post('/post/{id}', [PostController::class, 'create']);      // Create a new post
    Route::get('/post/{id}', [PostController::class, 'show']);    // Show a specific post
    Route::put('/post/{id}', [PostController::class, 'update']);  // Update a post
    Route::delete('/post/{id}', [PostController::class, 'delete']);  // Delete a post

    Route::post('/comment/{id}', [CommentController::class, 'index']); //Comment on a post
    Route::post('/like/{id}', [LikeController::class, 'index']); // Likes a post
});
