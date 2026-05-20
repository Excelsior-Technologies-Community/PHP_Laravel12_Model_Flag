<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/trending', [PostController::class, 'trendingPosts']);
Route::get('/toggle/{id}/{flag}', [PostController::class, 'toggleFlag']);

// NEW ROUTES
Route::post('/bulk-flags', [PostController::class, 'bulkToggleFlags']);
Route::get('/export', [PostController::class, 'export']);
Route::get('/trash/{id}', [PostController::class, 'trash']);
Route::get('/restore/{id}', [PostController::class, 'restore']);
Route::get('/force-delete/{id}', [PostController::class, 'forceDelete']);
Route::get('/trashed', [PostController::class, 'trashed']);
Route::get('/statistics', [PostController::class, 'statistics']);