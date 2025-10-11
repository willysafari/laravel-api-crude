<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::get('/test', [App\Http\Controllers\TestApiController::class, 'index'])->name('test');