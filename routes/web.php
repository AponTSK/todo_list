<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function ()
{
    return view('welcome');
});

Route::get('/dashboard', [QuoteController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function ()
{
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/quotes', [QuoteController::class, 'index'])->name('quotes.index');
    Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
    Route::post('posts/like-dislike', [QuoteController::class, 'likeDislike'])->name('quotes.like.dislike');
});

// Route::get('/dashboard', function ()
// {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function ()
{
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

require __DIR__ . '/auth.php';
