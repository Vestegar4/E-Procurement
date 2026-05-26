<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Tender;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsVendor;
use App\Http\Controllers\Vendor\VendorTenderController;
use App\Http\Controllers\Vendor\VendorBidController;
use App\Http\Controllers\Vendor\VendorDocumentController;
use App\Http\Controllers\Vendor\VendorReportController;
use App\Http\Controllers\Vendor\VendorProfileController;

// ============================================================
// AUTH ROUTES
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

// ============================================================
// ADMIN ROUTES
// ============================================================
Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function () {

    Route::get('/admin/dashboard', function () {
        $vendorCount   = Schema::hasTable('vendors') ? Vendor::count() : 0;
        $tenderCount   = Schema::hasTable('tenders') ? Tender::count() : 0;
        $resultCount   = 0;
        $latestTenders = Schema::hasTable('tenders') ? Tender::latest()->take(5)->get() : collect();

        return view('admin.dashboard', compact('vendorCount', 'tenderCount', 'resultCount', 'latestTenders'));
    })->name('admin.dashboard');    
    Route::get('/users', function () {
        $users = Schema::hasTable('users') ? DB::table('users')->get() : collect();
        return view('admin.users', compact('users'));
    })->name('admin.users');

    Route::get('/vendors', function () {
        $vendors = Schema::hasTable('vendors') ? Vendor::all() : collect();
        return view('admin.vendors', compact('vendors'));
    })->name('admin.vendors');

    Route::post('/vendors/{id}/update-status', function (Request $request, $id) {
        Vendor::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Status vendor berhasil diperbarui!');
    })->name('admin.vendors.update-status');

    Route::get('/procurement', function () {
        $tenders = Schema::hasTable('tenders') ? Tender::latest()->get() : collect();
        return view('admin.procurement', compact('tenders'));
    })->name('admin.procurement');

    Route::post('/procurement/store', function (Request $request) {
        if (!Schema::hasTable('tenders')) {
            return back()->with('error', 'Tabel tenders tidak ditemukan.');
        }

        Tender::create([
            'title'      => $request->title,
            'status'     => 'draft',
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Tender baru berhasil dibuat!');
    })->name('admin.procurement.store');

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

    Route::get('/reports/download/{type}', function ($type) {
        $data = $type === 'procurement'
            ? (Schema::hasTable('tenders') ? Tender::all() : collect())
            : (Schema::hasTable('vendors') ? Vendor::all() : collect());

        $filename = ($type === 'procurement' ? 'laporan_pengadaan_' : 'laporan_keuangan_') . date('Ymd') . '.csv';

        $handle = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=$filename");

        fputcsv($handle, ['ID', 'Nama/Judul', 'Tanggal']);
        foreach ($data as $row) {
            fputcsv($handle, [$row->id, $row->title ?? $row->name ?? '-', $row->created_at]);
        }
        fclose($handle);
        exit;
    })->name('admin.reports.download');
    Route::get('/purchase-orders/{id}/export-pdf', [PurchaseOrderController::class, 'exportPDF']);

    Route::get('/settings', fn() => view('admin.settings'))->name('admin.settings');
});

// ============================================================
// VENDOR ROUTES
// ============================================================
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