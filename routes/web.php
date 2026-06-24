<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Admin\ModerationController;

Route::get('/', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::get('/trending', [PostController::class, 'trendingPosts']);
Route::get('/toggle/{id}/{flag}', [PostController::class, 'toggleFlag']);

Route::prefix('admin')->group(function () {
    Route::get('/moderation', [ModerationController::class, 'index'])->name('admin.moderation');
 
    Route::delete('/post/{id}', [ModerationController::class, 'destroy'])->name('admin.post.delete');
    Route::delete('/flag/{id}', [ModerationController::class, 'resolve'])->name('admin.flag.resolve');
    
    Route::get('/trashed', [PostController::class, 'trashed'])->name('admin.posts.trashed');
    Route::get('/trash/{id}', [PostController::class, 'trash'])->name('admin.posts.trash');
    Route::get('/restore/{id}', [PostController::class, 'restore'])->name('admin.posts.restore');
    Route::get('/force-delete/{id}', [PostController::class, 'forceDelete'])->name('admin.posts.forceDelete');
    
    Route::post('/bulk-flags', [PostController::class, 'bulkToggleFlags'])->name('admin.flags.bulk');
    Route::get('/export', [PostController::class, 'export'])->name('admin.flags.export');
    Route::get('/statistics', [PostController::class, 'statistics'])->name('admin.flags.statistics');
});