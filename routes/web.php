<?php

declare(strict_types=1);

use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ideas');

Route::get('/ideas', [IdeaController::class, 'index'])->name('idea.index')->middleware('auth');
Route::get('/ideas/{idea}', [IdeaController::class, 'show'])->name('idea.show')->middleware('auth');
Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy'])->name('idea.destroy')->middleware('auth');
Route::post('/ideas', [IdeaController::class, 'store'])->name('idea.store')->middleware('auth');

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register')->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('guest');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');
