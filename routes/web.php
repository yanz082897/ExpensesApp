<?php

use App\Livewire\BudgetForm;
use App\Livewire\BudgetList;
use App\Livewire\Categories;
use App\Livewire\ExpenseForm;
use App\Livewire\ExpenseList;
use App\Livewire\RecurringExpense;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    Route::get('categories', Categories::class)
        ->name('categories.index');

    Route::get('budgets', BudgetList::class)
        ->name('budgets.index');

    Route::get('budgets/create', BudgetForm::class)
        ->name('budgets.create');

    Route::get('budgets/{budgetId}/edit', BudgetForm::class)
        ->name('budgets.edit');

    Route::get('expenses', ExpenseList::class)
        ->name('expenses.index');

    Route::get('expenses/create', ExpenseForm::class)
        ->name('expenses.create');

    Route::get('recurring-expenses', RecurringExpense::class)
        ->name('recurring-expenses.index');

});

require __DIR__.'/settings.php';
