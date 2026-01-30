<?php

use App\Http\Controllers\Admin\DeliveryBoyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/admin/dashboard', fn () => view('admin.dashboard'))
        ->name('admin.dashboard');

    Route::middleware('role:delivery_boy')->group(function () {
        Route::get('/delivery/dashboard', fn () => view('delivery.dashboard'))
            ->name('delivery.dashboard');
    });

    Route::post('/admin/delivery-boys/{user}/toggle-status',
        [DeliveryBoyController::class, 'toggleStatus']
    )->name('admin.delivery_boys.toggle');

    Route::get('/delivery-boys', [DeliveryBoyController::class, 'index'])
        ->name('admin.delivery_boys.index');

    Route::get('/delivery-boys/create', [DeliveryBoyController::class, 'create'])
        ->name('admin.delivery_boys.create');

    Route::post('/delivery-boys', [DeliveryBoyController::class, 'store'])
        ->name('admin.delivery_boys.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
