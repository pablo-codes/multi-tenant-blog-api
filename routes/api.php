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
    Route::apiResource("blogs", BlogController::class);
    Route::apiResource("post", PostController::class,);
    Route::post('/comment/{id}', [CommentController::class, 'index']); //Comment on a post
    Route::post('/like/{id}', [LikeController::class, 'index']); // Likes a post
});
