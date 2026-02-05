<?php

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\AdminDeliveryController;
use App\Http\Controllers\Api\AdminNotificationController;
use App\Http\Controllers\Api\AdminSettlementController;
use App\Http\Controllers\Api\CustomerSyncController;
use App\Http\Controllers\Api\DeliveryBoyController;
use App\Http\Controllers\Api\DeliveryBoyPerformanceController;
use App\Http\Controllers\Api\DeliveryUploadController;
use App\Http\Controllers\Api\GeocodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');
/*
|--------------------------------------------------------------------------
| ADMIN AUTH (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| ADMIN AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum','role:admin'])
    ->prefix('admin')
    ->group(function () {

        /*
        |--------------------------------------------------
        | AUTH / SESSION
        |--------------------------------------------------
        */
        Route::get('/me', [AdminAuthController::class, 'me']);
        Route::post('/logout', [AdminAuthController::class, 'logout']);

        /*
        |--------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------
        */
        Route::get('/dashboard',
            [AdminDashboardController::class, 'index']
        );

        Route::get('/dashboard/delivery-boy-performance',
            [DeliveryBoyPerformanceController::class, 'index']
        );

        /*
        |--------------------------------------------------
        | CUSTOMER SYNC (LOCAL → ONLINE)
        |--------------------------------------------------
        */
        Route::post('/customers/sync',
            [CustomerSyncController::class, 'sync']
        );

        Route::post('/customers/sync/map-location',
            [CustomerSyncController::class, 'syncMapLocation']
        );

        /*
        |--------------------------------------------------
        | DELIVERIES (UPLOAD + VIEW)
        |--------------------------------------------------
        */
        Route::post('/deliveries/upload',
            [DeliveryUploadController::class, 'upload']
        );

        Route::get('/deliveries',
            [AdminDeliveryController::class, 'index']
        );

        Route::get('/deliveries/{id}', [AdminDeliveryController::class, 'get']);
        /*
        |--------------------------------------------------
        | DELIVERY REQUEST APPROVALS
        |--------------------------------------------------
        */
        Route::post('/deliveries/{delivery}/approve',
            [AdminDeliveryController::class, 'approveRequest']
        );

        Route::post('/deliveries/{delivery}/reject',
            [AdminDeliveryController::class, 'rejectRequest']
        );

        /*
        |--------------------------------------------------
        | SETTLEMENTS
        |--------------------------------------------------
        */
        Route::get('/settlements',
            [AdminSettlementController::class, 'index']
        );

        Route::get('/settlements/{id}', [AdminSettlementController::class, 'get']);

        Route::post('/settlements/{settlement}/approve',
            [AdminSettlementController::class, 'approve']
        );

        Route::post('/settlements/{settlement}/reject',
            [AdminSettlementController::class, 'reject']
        );

        /*
        |--------------------------------------------------
        | DELIVERY BOY MANAGEMENT
        |--------------------------------------------------
        */
        Route::post('/delivery-boys/{user}/activate',
            [DeliveryBoyController::class, 'activate']
        );

        Route::post('/delivery-boys/{user}/deactivate',
            [DeliveryBoyController::class, 'deactivate']
        );

        Route::get('/delivery-boys', [DeliveryBoyController::class, 'index']);

        /*
         |----------------------------------------------------
         | ADMIN NOTIFICATION
         |----------------------------------------------------
         */
        Route::get('/notifications',
            [AdminNotificationController::class, 'index']
        );

        Route::post('/notifications/{id}/read',
            [AdminNotificationController::class, 'markAsRead']
        );

        Route::post('/notifications/read-all',
            [AdminNotificationController::class, 'readAll']
        );

        Route::post('/geocode/test',
            [GeocodeController::class, 'geocode']);
    });

/*
|--------------------------------------------------------------------------
| DELIVERY BOY API (MOBILE APP)
|--------------------------------------------------------------------------
*/
//Route::middleware(['auth:sanctum','role:delivery_boy'])
//    ->prefix('mobile')
//    ->group(function () {
//
//        /*
//        |--------------------------------------------------
//        | AUTH / PROFILE
//        |--------------------------------------------------
//        */
//        Route::get('/me', [\App\Http\Controllers\Api\Mobile\ProfileController::class, 'me']);
//        Route::post('/profile/username', [\App\Http\Controllers\Api\Mobile\ProfileController::class, 'updateUsername']);
//        Route::post('/profile/password', [\App\Http\Controllers\Api\Mobile\ProfileController::class, 'updatePassword']);
//
//        /*
//        |--------------------------------------------------
//        | DASHBOARD
//        |--------------------------------------------------
//        */
//        Route::get('/dashboard',
//            [\App\Http\Controllers\Api\Mobile\DashboardController::class, 'index']
//        );
//
//        /*
//        |--------------------------------------------------
//        | DELIVERIES
//        |--------------------------------------------------
//        */
//        Route::get('/deliveries',
//            [\App\Http\Controllers\Api\Mobile\DeliveryController::class, 'index']
//        );
//
//        Route::get('/deliveries/{delivery}',
//            [\App\Http\Controllers\Api\Mobile\DeliveryController::class, 'show']
//        );
//
//        /*
//        |--------------------------------------------------
//        | DELIVERY ACTIONS
//        |--------------------------------------------------
//        */
//        Route::post('/deliveries/{delivery}/confirm-prepaid',
//            [\App\Http\Controllers\Api\Mobile\DeliveryActionController::class, 'confirmPrepaid']
//        );
//
//        Route::post('/deliveries/{delivery}/collect-cod',
//            [\App\Http\Controllers\Api\Mobile\DeliveryActionController::class, 'collectCod']
//        );
//
//        Route::post('/deliveries/{delivery}/request-cancel',
//            [\App\Http\Controllers\Api\Mobile\DeliveryActionController::class, 'requestCancel']
//        );
//
//        Route::post('/deliveries/{delivery}/request-reschedule',
//            [\App\Http\Controllers\Api\Mobile\DeliveryActionController::class, 'requestReschedule']
//        );
//
//        /*
//        |--------------------------------------------------
//        | SCAN INVOICE
//        |--------------------------------------------------
//        */
//        Route::post('/scan',
//            [\App\Http\Controllers\Api\Mobile\ScanController::class, 'handle']
//        );
//
//        /*
//        |--------------------------------------------------
//        | SETTLEMENTS
//        |--------------------------------------------------
//        */
//        Route::get('/settlement',
//            [\App\Http\Controllers\Api\Mobile\SettlementController::class, 'index']
//        );
//
//        Route::post('/settlement',
//            [\App\Http\Controllers\Api\Mobile\SettlementController::class, 'store']
//        );
//
//        /*
//        |--------------------------------------------------
//        | NOTIFICATIONS
//        |--------------------------------------------------
//        */
//        Route::get('/notifications',
//            [\App\Http\Controllers\Api\Mobile\NotificationController::class, 'index']
//        );
//
//        Route::post('/notifications/{id}/read',
//            [\App\Http\Controllers\Api\Mobile\NotificationController::class, 'markAsRead']
//        );
//
//    });
