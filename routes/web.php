<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

// --- LEITURA (Páginas Públicas) ---
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/post/{id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/user/{id}/posts', [PostController::class, 'postsByUser'])->name('users.posts');
Route::get('/user/{id}', [UserController::class, 'show'])->name('users.show');

// --- INTERAÇÕES NO POST ---
Route::post('/post/{id}/like', [PostController::class, 'like'])->name('posts.like');
Route::post('/post/{id}/dislike', [PostController::class, 'dislike'])->name('posts.dislike');

// --- INTERAÇÕES NOS COMENTÁRIOS (O erro estava aqui!) ---
Route::post('/comment/{id}/like', [PostController::class, 'likeComment'])->name('comments.like');
Route::post('/comment/{id}/dislike', [PostController::class, 'dislikeComment'])->name('comments.dislike');

// --- CRUD DE COMENTÁRIOS ---
Route::post('/post/{id}/comment', [PostController::class, 'storeComment'])->name('comments.store');
Route::get('/comment/{id}/edit', [PostController::class, 'editComment'])->name('comments.edit');
Route::put('/comment/{id}', [PostController::class, 'updateComment'])->name('comments.update');
Route::delete('/comment/{id}', [PostController::class, 'deleteComment'])->name('comments.destroy');