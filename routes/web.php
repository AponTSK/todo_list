<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\TodoController;

Route::name('todo.')->group(function ()
{
    Route::get('/index', [TodoController::class, 'index'])->name('index');
    Route::post('/todos', [TodoController::class, 'store'])->name('store');
    Route::post('/todos/{id}', [TodoController::class, 'update'])->name('update');
    Route::get('/todos/{id}', [TodoController::class, 'destroy'])->name('destroy');
});
