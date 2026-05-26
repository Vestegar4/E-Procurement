<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Vendor;
use App\Models\Tender;
<<<<<<< HEAD
use App\Http\Controllers\PurchaseOrderController;
=======
use App\Models\User;
>>>>>>> 6c5fab4 (big update frontend)
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsVendor;
use App\Http\Controllers\Vendor\VendorTenderController;
use App\Http\Controllers\Vendor\VendorBidController;
use App\Http\Controllers\Vendor\VendorDocumentController;
use App\Http\Controllers\Vendor\VendorReportController;
use App\Http\Controllers\Vendor\VendorProfileController;

// ============================================================
// AUTH & HOME ROUTES
// ============================================================
Auth::routes(['register' => true]);

// Redirect root ke dashboard sesuai role
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('vendor.dashboard');
    }
    return view('home.home');
})->name('home');

Route::get('/pending-approval', fn() => view('auth.pending'))->name('pending');

// ============================================================
// ADMIN ROUTES (Terhubung ke folder views/admin/)
// ============================================================
Route::middleware(['auth', EnsureUserIsAdmin::class])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        $vendorCount   = Schema::hasTable('vendors') ? Vendor::count() : 0;
        $tenderCount   = Schema::hasTable('tenders') ? Tender::count() : 0;
        $resultCount   = 0;
        $latestTenders = Schema::hasTable('tenders') ? Tender::latest()->take(5)->get() : collect();

        return view('admin.dashboard', compact('vendorCount', 'tenderCount', 'resultCount', 'latestTenders'));
    })->name('admin.dashboard');    
    Route::get('/users', function () {
        $users = Schema::hasTable('users') ? User::all() : [];
        return view('admin.users', compact('users'));
    })->name('admin.users');

    Route::get('/vendors', function () {
        $vendors = Schema::hasTable('vendors') ? Vendor::all() : [];
        return view('admin.vendors', compact('vendors'));
    })->name('admin.vendors');

    Route::get('/procurement', function () {
        return view('admin.procurement');
    })->name('admin.procurement');

    Route::get('/products', function () {
        return view('admin.products');
    })->name('admin.products');

    // INI RUTE YANG SEBELUMNYA HILANG DAN BIKIN ERROR 500
    Route::get('/purchase-order', function () {
        return view('admin.purchase-order');
    })->name('admin.purchase-order');

    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('admin.reports');

<<<<<<< HEAD
    Route::get('/products', fn() => view('admin.products'))->name('admin.products');

    Route::get('/po', function () {
    // Pastikan model PurchaseOrder dipanggil (jika tabelnya sudah ada)
    $purchaseOrders = \Illuminate\Support\Facades\Schema::hasTable('purchase_orders') 
        ? \App\Models\PurchaseOrder::latest()->get() 
        : collect();

    // Kirim datanya ke halaman blade menggunakan compact()
    return view('admin.purchase-order', compact('purchaseOrders'));
    })->name('admin.po');

    Route::get('/reports', fn() => view('admin.reports'))->name('admin.reports');
=======
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');
>>>>>>> 6c5fab4 (big update frontend)

    // Route Download Report
    Route::get('/reports/download/{type}', function ($type) {
        $filename = "laporan_{$type}_" . date('Ymd_His') . ".csv";
        $data = []; // Ambil dari DB sesuai kebutuhan
        
        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=$filename");

        fputcsv($handle, ['ID', 'Nama/Judul', 'Tanggal']);
        foreach ($data as $row) {
            fputcsv($handle, [$row->id ?? '-', $row->title ?? $row->name ?? '-', $row->created_at ?? '-']);
        }
        fclose($handle);
        exit;
    })->name('admin.reports.download');
    Route::get('/purchase-orders/{id}/export-pdf', [PurchaseOrderController::class, 'exportPDF']);

});

// ============================================================
// VENDOR ROUTES (Terhubung ke folder views/vendor/)
// ============================================================
<<<<<<< HEAD
Route::middleware(['auth', EnsureUserIsVendor::class])->group(function () {
    Route::get('/vendor/dashboard', [\App\Http\Controllers\Vendor\VendorDashboardController::class, 'index'])
        ->name('vendor.dashboard');
    Route::get('/vendor/tenders', fn() => view('vendor.tenders'))->name('vendor.tenders');
    Route::get('/vendor/bids', [VendorBidController::class, 'index'])->name('vendor.bids.index');
    Route::get('/vendor/documents', [VendorDocumentController::class, 'index'])->name('vendor.documents.index');
    Route::post('/vendor/documents', [VendorDocumentController::class, 'store'])->name('vendor.documents.store');
    Route::get('/vendor/documents/{id}/download', [VendorDocumentController::class, 'download'])
        ->name('vendor.documents.download');
    Route::delete('/vendor/documents/{id}', [VendorDocumentController::class, 'destroy'])
        ->name('vendor.documents.destroy');
    Route::get('/vendor/reports', [VendorReportController::class, 'index'])->name('vendor.reports');
    Route::get('/vendor/settings', [VendorProfileController::class, 'index'])->name('vendor.settings');
    Route::put('/vendor/settings/profile', [VendorProfileController::class, 'updateProfileWeb'])
        ->name('vendor.settings.profile.update');
    Route::put('/vendor/settings/password', [VendorProfileController::class, 'updatePasswordWeb'])
        ->name('vendor.settings.password.update');

    /*Vendor Tenders*/
    Route::get(
        '/vendor/tenders/{id}',
        [VendorTenderController::class, 'show']
    )->name('vendor.tenders.show');

    Route::post(
        '/vendor/tenders/{id}/join',
        [VendorTenderController::class, 'join']
    )->name('vendor.tenders.join');

    /*Vendor Bids*/
    Route::get(
        '/tenders/{id}/submit-bid',
        [VendorBidController::class, 'create']
    )->name('vendor.bids.create');

    Route::post(
        '/tenders/{id}/submit-bid',
        [VendorBidController::class, 'store']
    )->name('vendor.bids.store');
});

// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/pending-approval', fn() => view('auth.pending'))->name('pending');
=======
Route::middleware(['auth', EnsureUserIsVendor::class])->prefix('vendor')->group(function () {
    Route::get('/dashboard', fn() => view('vendor.dashboard'))->name('vendor.dashboard');
    Route::get('/tenders', fn() => view('vendor.tenders'))->name('vendor.tenders');
    Route::get('/bids', fn() => view('vendor.bids'))->name('vendor.bids');
    Route::get('/documents', fn() => view('vendor.documents'))->name('vendor.documents');
    Route::get('/reports', fn() => view('vendor.reports'))->name('vendor.reports');
    Route::get('/settings', fn() => view('vendor.settings'))->name('vendor.settings');
});
>>>>>>> 6c5fab4 (big update frontend)
