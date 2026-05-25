<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\VendorAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TenderController;
use App\Http\Controllers\Admin\BidMonitoringController;
use App\Http\Controllers\Admin\TenderAnnouncementController;
use App\Http\Controllers\Admin\TenderResultController as AdminTenderResultController;
use App\Http\Controllers\Admin\VendorManagementController;
use App\Http\Controllers\Admin\AdminInvoiceController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\PurchaseOrderController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Admin Authentication
Route::prefix('auth/admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('me', [AdminAuthController::class, 'me'])->middleware('auth:sanctum');
});

// Vendor Authentication
Route::prefix('auth/vendor')->group(function () {
    Route::post('register', [VendorAuthController::class, 'register']);
    Route::post('login', [VendorAuthController::class, 'login'])->middleware('throttle:5,1'); // Limit to 5 attempts per minute
    Route::post('logout', [VendorAuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('me', [VendorAuthController::class, 'me'])->middleware('auth:sanctum');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', \App\Http\Middleware\EnsureUserIsAdmin::class])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);

    // Tenders Management
    Route::apiResource('tenders', TenderController::class);

    // Bid Monitoring
    Route::get('bids', [BidMonitoringController::class, 'index']);
    Route::get('tenders/{tenderId}/bids', [BidMonitoringController::class, 'tenderBids']);

    // Tender Announcements
    Route::post('tenders/{tenderId}/announcements', [TenderAnnouncementController::class, 'store']);
    Route::get('tenders/{tenderId}/announcements', [TenderAnnouncementController::class, 'index']);

    // Tender Results
    Route::post('tenders/{tenderId}/results/select-winner', [AdminTenderResultController::class, 'selectWinner']);
    Route::get('tenders/{tenderId}/results', [AdminTenderResultController::class, 'show']);

    // Vendor Management
    Route::get('vendors', [VendorManagementController::class, 'index']);
    Route::get('vendors/{id}', [VendorManagementController::class, 'show']);
    Route::post('vendors/{id}/approve', [VendorManagementController::class, 'approve']);
    Route::post('vendors/{id}/reject', [VendorManagementController::class, 'reject']);

    Route::prefix('notifications')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index']);
        Route::put('{id}/read', [AdminNotificationController::class, 'markAsRead']);
        Route::put('read-all', [AdminNotificationController::class, 'markAllAsRead']);
    });

    Route::prefix('invoices')->group(function () {
        Route::get('/', [AdminInvoiceController::class, 'index']);
        Route::get('{id}', [AdminInvoiceController::class, 'show']);
        Route::put('{id}/status', [AdminInvoiceController::class, 'updateStatus']);
    });

    Route::get('/tenders/{tenderId}/export-bahp', [ReportController::class, 'exportBAHP']);

    Route::prefix('purchase-orders')->group(function () {
        Route::get('/', [PurchaseOrderController::class, 'index']);
        Route::get('{id}/export-pdf', [PurchaseOrderController::class, 'exportPDF']);
    });
});

/*
|--------------------------------------------------------------------------
| VENDOR ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', \App\Http\Middleware\EnsureUserIsVendor::class])->prefix('vendor')->group(function () {
    //Check Available Tenders
    Route::get('/tenders', [\App\Http\Controllers\Vendor\VendorTenderController::class, 'index']);
    Route::get('/tenders/my-tenders', [\App\Http\Controllers\Vendor\VendorTenderController::class, 'myTenders']);
    Route::get('/tenders/{id}', [\App\Http\Controllers\Vendor\VendorTenderController::class, 'show']);
    Route::post('/tenders/{id}/join', [\App\Http\Controllers\Vendor\VendorTenderController::class, 'join']);

    //bids
    Route::get('bids', [\App\Http\Controllers\Vendor\VendorBidController::class, 'mybids']);
    Route::get('/bids/{id}', [\App\Http\Controllers\Vendor\VendorBidController::class, 'show']);
    Route::post('/tenders/{id}/bid', [\App\Http\Controllers\Vendor\VendorBidController::class, 'submitBid']);

    //Tender Results
    Route::get('/results', [\App\Http\Controllers\Vendor\VendorResultController::class, 'index']);
    Route::get('/results/{tenderId', [\App\Http\Controllers\Vendor\VendorResultController::class, 'show']);
});
