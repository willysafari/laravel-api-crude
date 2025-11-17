<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\LikeController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Middleware\roleMiddleware;
use App\Models\BlogPost;
use App\Http\Controllers\CommentController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/students', App\Http\Controllers\StudentController::class);


Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::apiResource('/categories', BlogCategoryController::class)->middleware(['role:admin']);
    Route::apiResource('/posts', BlogPostController::class)->middleware(['role:admin,author']);
    Route::post('/blog-image-post/{posts}', [BlogPostController::class, 'blogImagePost'])->name('blog-image-post')->middleware(['role:admin,author']);
    Route::post('/like/react', [LikeController::class, 'react'])->name('react');
    Route::apiResource('/comments', CommentController::class)->middleware(['role:admin,author,reader']);
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index')->middleware(['role:admin']);
    // appending comment route
    Route::post('comments/pending/{id}', [CommentController::class, 'pending'])->name('comments.pending')->middleware(['role:admin']);
    //apiresource of comment
});

Route::get('/posts',[BlogPostController::class,'index'])->name('posts.index');
Route::get('/categories',[BlogCategoryController::class,'index'])->name('categories.index');
Route::get('/post/reactions/{post}', [LikeController::class, 'Reactions'])->name('post.reactions');
Route::get('/blog/{id}',[BlogPostController::class, 'show']);



