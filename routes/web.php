<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
  return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

Route::middleware(['auth'])->group(function () {
  Route::redirect('settings', 'settings/profile');

  Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
  Volt::route('settings/password', 'settings.password')->name('settings.password');

  Route::get('categories', [CategoryController::class, 'index'])->name('categories');
  Route::get('products', [ProductController::class, 'index'])->name('products');
  Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases');
  Route::get('sales', [SaleController::class, 'index'])->name('sales');
});

require __DIR__ . '/auth.php';
