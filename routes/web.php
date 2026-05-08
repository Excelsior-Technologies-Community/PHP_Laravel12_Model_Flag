<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/trending', [PostController::class, 'trendingPosts']);
Route::get('/toggle/{id}/{flag}', [PostController::class, 'toggleFlag']);