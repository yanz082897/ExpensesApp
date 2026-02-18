<?php

use App\Livewire\Categories;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    Route::get('categories', Categories::class)
        ->name('categories');

});

require __DIR__.'/settings.php';
