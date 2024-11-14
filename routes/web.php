<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\TodoController;

Route::get('/index', [TodoController::class, 'index'])->name('todo.index');
Route::post('/todos', [TodoController::class, 'store'])->name('todo.store');
Route::post('/todos/{id}', [TodoController::class, 'update'])->name('todo.update');
Route::get('/todos/{id}', [TodoController::class, 'destroy'])->name('todo.destroy');
