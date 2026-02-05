<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryBoyController;
use App\Http\Controllers\Admin\DeliveryBoyPerformanceController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SettlementController;
use App\Http\Controllers\Admin\UpiController;
use App\Http\Controllers\Mobile\MobileAuthController;
use App\Http\Controllers\Mobile\MobileDashboardController;
use App\Http\Controllers\Mobile\MobileDeliveryActionController;
use App\Http\Controllers\Mobile\MobileDeliveryController;
use App\Http\Controllers\Mobile\MobileNotificationController;
use App\Http\Controllers\Mobile\MobilePaymentController;
use App\Http\Controllers\Mobile\MobileProfileController;
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

        Route::get('/delivery-boys-performance',
            [DeliveryBoyPerformanceController::class, 'index'])
            ->name('delivery_boys.performance');

        Route::get(
            '/delivery-boys-performance/{user}',
            [DeliveryBoyPerformanceController::class, 'show']
        )->name('delivery_boys.performance.show');

        Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries.index');

        Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show'])->name('deliveries.show');
        Route::post('/deliveries/{delivery}', [DeliveryController::class, 'approve'])->name('deliveries.approve');

        Route::get('/settlements',
            [SettlementController::class, 'index']
        )->name('settlements.index');

        Route::get('/settlements/{settlement}',
            [SettlementController::class, 'show']
        )->name('settlements.show');

        Route::post('/settlements/{settlement}/approve',
            [SettlementController::class, 'approve']
        )->name('settlements.approve');

        Route::post('/settlements/{settlement}/reject',
            [SettlementController::class, 'reject']
        )->name('settlements.reject');

        Route::post('/notifications/{id}/read',
            [NotificationController::class, 'markRead']
        )->name('notifications.read');

        Route::post('/notifications/readAll',
            [NotificationController::class, 'markAllRead']
        )->name('notifications.readall');

        Route::get('/profile',
            [\App\Http\Controllers\Admin\ProfileController::class, 'edit']
        )->name('profile.edit');

        Route::post('/profile',
            [\App\Http\Controllers\Admin\ProfileController::class, 'update']
        )->name('profile.update');

        Route::post('/profile/password',
            [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword']
        )->name('profile.password');

        Route::get('/customers', [CustomerController::class, 'index'])
            ->name('customers');

        Route::get('/customers/{customer}', [CustomerController::class, 'show'])
            ->name('customers.show');

        Route::post('/admin/customers/geocode',
            [CustomerController::class, 'geocode']);

        Route::post('/admin/customers/reverse-geocode',
            [CustomerController::class, 'reverseGeocode']);

        Route::get('/admin/upi', [UpiController::class, 'get'])
            ->name('upi');

        Route::post('/admin/upi', [UpiController::class, 'update'])
            ->name('upi-update');
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
Route::prefix('mobile')->name('mobile.')->group(function () {

    // Guest (not logged in)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [MobileAuthController::class, 'showLogin'])
            ->name('login');

        Route::post('/login', [MobileAuthController::class, 'login'])
            ->name('login.submit');
    });
});
Route::middleware(['auth', 'role:delivery_boy'])
    ->prefix('mobile')
    ->name('mobile.')
    ->group(function () {
        Route::post('/logout', [MobileAuthController::class, 'logout'])
            ->name('logout');
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
        Route::get('/profile',
            [MobileProfileController::class, 'edit']
        )->name('profile');

        Route::post('/profile/username',
            [MobileProfileController::class, 'updateUsername']
        )->name('profile.username');

        Route::post('/profile/password',
            [MobileProfileController::class, 'updatePassword']
        )->name('profile.password');

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

        Route::get('/notifications',
            [MobileNotificationController::class, 'index']
        )->name('notifications');

        Route::post('/notifications/{id}/read',
            [MobileNotificationController::class, 'markRead']
        )->name('notifications.read');

        Route::post('/notifications/read-all',
            [MobileNotificationController::class, 'markAllRead']
        )->name('notifications.readAll');
    });
require __DIR__.'/auth.php';

//Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
