<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;

// Rotas de autores
Route::apiResource('authors', AuthorController::class);

// Rotas de livros
Route::apiResource('books', BookController::class);

// Rota adicional: livros de um autor específico
Route::get('authors/{author}/books', [AuthorController::class, 'books']);