<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

// Rota da Página Inicial
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/post/{id}', [PostController::class, 'show'])->name('posts.show');
// Rota para posts de um usuário
Route::get('/user/{id}/posts', [PostController::class, 'postsByUser'])->name('users.posts');
Route::get('/user/{id}', [UserController::class, 'show'])->name('users.show');