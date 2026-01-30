<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryBoyController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\SettlementController;
use App\Http\Controllers\Mobile\MobileDashboardController;
use App\Http\Controllers\Mobile\MobileDeliveryActionController;
use App\Http\Controllers\Mobile\MobileDeliveryController;
use App\Http\Controllers\Mobile\MobilePaymentController;
use App\Http\Controllers\Mobile\MobileScanController;
use App\Http\Controllers\Mobile\MobileSettlementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect('/login');
    }

    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('mobile.dashboard');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /* 📊 Dashboard */
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /* 👤 Delivery Boy Management */
        Route::get('/delivery-boys', [DeliveryBoyController::class, 'index'])
            ->name('delivery_boys.index');

        Route::get('/delivery-boys/create', [DeliveryBoyController::class, 'create'])
            ->name('delivery_boys.create');

        Route::post('/delivery-boys', [DeliveryBoyController::class, 'store'])
            ->name('delivery_boys.store');

        Route::post('/delivery-boys/{user}/toggle-status',
            [DeliveryBoyController::class, 'toggleStatus']
        )->name('delivery_boys.toggle');

        Route::get('/settlements',
            [SettlementController::class, 'index']
        )->name('settlements.index');

        Route::post('/settlements/{settlement}/approve',
            [SettlementController::class, 'approve']
        )->name('settlements.approve');

        Route::post('/settlements/{settlement}/reject',
            [SettlementController::class, 'reject']
        )->name('settlements.reject');
        /* 💳 Payments (View / Verify / Reject) */
//        Route::get('/payments', [PaymentController::class, 'index'])
//            ->name('payments.index');
//
//        Route::post('/payments/{payment}/verify',
//            [PaymentController::class, 'verify']
//        )->name('payments.verify');
//
//        Route::post('/payments/{payment}/reject',
//            [PaymentController::class, 'reject']
//        )->name('payments.reject');
    });
Route::middleware(['auth', 'role:delivery_boy'])
    ->prefix('mobile')
    ->name('mobile.')
    ->group(function () {

        /* 🏠 Dashboard */
        Route::get('/dashboard',
            [MobileDashboardController::class, 'index']
        )->name('dashboard');

        /* 📦 Deliveries */
        Route::get('/deliveries',
            [MobileDeliveryController::class, 'index']
        )->name('deliveries');

        Route::get('/deliveries/{delivery}',
            [MobileDeliveryController::class, 'show']
        )->name('delivery.show');

        /* ✅ Prepaid – Confirm Delivery */
        Route::post('/deliveries/{delivery}/confirm-prepaid',
            [MobileDeliveryActionController::class, 'confirmPrepaid']
        )->name('delivery.confirm.prepaid');

        /* 💰 COD – Cash / UPI */
        Route::post('/deliveries/{delivery}/collect-cod',
            [MobileDeliveryActionController::class, 'collectCod']
        )->name('delivery.collect.cod');

        /* 🔄 Reschedule / Cancel Request */
        Route::post('/deliveries/{delivery}/request',
            [MobileDeliveryActionController::class, 'requestChange']
        )->name('delivery.request');

        /* 💼 Settlement */
        Route::get('/settlement',
            [MobileSettlementController::class, 'index']
        )->name('settlement');

        Route::post('/settlement',
            [MobileSettlementController::class, 'store']
        )->name('settlement.store');

        /* 👤 Profile */
        Route::get('/profile', function () {
            return view('mobile.profile');
        })->name('profile');

        Route::get('/payments',
            [MobilePaymentController::class, 'index']
        )->name('payments.index');

        Route::post('/deliveries/{delivery}/payment',
            [MobilePaymentController::class, 'store']
        )->name('payments.store');

        Route::get('/payments/{payment}',
            [MobilePaymentController::class, 'show']
        )->name('payments.show');

        Route::get('/scan', [MobileScanController::class, 'index'])
            ->name('scan');

        Route::post('/scan', [MobileScanController::class, 'handle'])
            ->name('scan.handle');
    });
require __DIR__.'/auth.php';

//Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
