<?php

use App\Http\Controllers\BlogPostController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Middleware\roleMiddleware;



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
});

Route::get('/posts',[BlogPostController::class,'index'])->name('posts.index');
Route::get('/categories',[BlogCategoryController::class,'index'])->name('categories.index');