<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Vendor;
use App\Models\Tender;
use App\Models\User;
use App\Http\Controllers\PurchaseOrderController;
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

    Route::get('/purchase-order', function () {
        // 1. Coba ambil data asli dari database
        $purchaseOrders = \Illuminate\Support\Facades\Schema::hasTable('purchase_orders') 
            ? \App\Models\PurchaseOrder::latest()->get() 
            : collect();

        if ($purchaseOrders->isEmpty()) {
            $purchaseOrders = collect([
                (object) [
                    'id' => 1,
                    'tender_name' => 'Pengadaan Perangkat Komputer Server',
                    'vendor_name' => 'PT Vendor Teknologi Maju',
                    'status' => 'Pending'
                ]
            ]);
        }

        // 3. Kirim ke tampilan
        return view('admin.purchase-order', compact('purchaseOrders'));
    })->name('admin.purchase-order');

    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('admin.reports');

    // Rute Settings dari branch main dimasukkan
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');

    Route::get('/reports/download/{type}', function ($type) {
        $filename = "laporan_{$type}_" . date('Ymd_His') . ".csv";
        $data = []; 
        
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
    
    // Rute PDF PO buatan Anda tetap dipertahankan
    Route::get('/purchase-orders/{id}/export-pdf', [PurchaseOrderController::class, 'exportPDF']);

});

// ============================================================
// VENDOR ROUTES (Terhubung ke folder views/vendor/)
// ============================================================
// Kita menggunakan versi dari branch Anda karena jauh lebih lengkap dengan logic controller
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

    Route::get('/vendor/tenders/{id}', [VendorTenderController::class, 'show'])->name('vendor.tenders.show');
    Route::post('/vendor/tenders/{id}/join', [VendorTenderController::class, 'join'])->name('vendor.tenders.join');
    Route::get('/tenders/{id}/submit-bid', [VendorBidController::class, 'create'])->name('vendor.bids.create');
    Route::post('/tenders/{id}/submit-bid', [VendorBidController::class, 'store'])->name('vendor.bids.store');
});