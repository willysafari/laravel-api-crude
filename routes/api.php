<?php

use App\Http\Controllers\BlogPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/students', App\Http\Controllers\StudentController::class);


Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');
    Route::apiResource('/categories', App\Http\Controllers\BlogCategoryController::class);
    Route::apiResource('/posts', BlogPostController::class);
    Route::post('/blog-image-post/{posts}', [BlogPostController::class, 'blogImagePost'])->name('blog-image-post');
});