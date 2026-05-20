<?php

use Illuminate\Support\Facades\Route;

use App\Models\Vendor;
use App\Models\Tender;
use App\Models\TenderResult;
use App\Http\Controllers\Admin\TenderController;
use App\Models\PurchaseOrder;
use App\Http\Controllers\PurchaseOrderController;

Route::get('/', function () {

    $vendorCount = Vendor::count();

    $tenderCount = Tender::count();

    $resultCount = TenderResult::count();

    $latestTenders = Tender::latest()->take(5)->get();

    return view('dashboard.dash', compact(
        'vendorCount',
        'tenderCount',
        'resultCount',
        'latestTenders'
    ));
});

Route::get('/procurement', function () {

    $tenders = Tender::latest()->get();

    return view('procurement.proc', compact('tenders'));
});

Route::prefix('admin')->group(function () {

    Route::get('/tenders', [TenderController::class, 'index']);
    Route::post('/tenders', [TenderController::class, 'store']);
    Route::put('/tenders/{id}', [TenderController::class, 'update']);
    Route::delete('/tenders/{id}', [TenderController::class, 'destroy']);
    Route::get('/tenders/{id}', [TenderController::class, 'show']);
});

Route::get('/contracts', function () {

    $contracts = PurchaseOrder::latest()->get();
    return view('contracts.contracts', compact('contracts'));
});

Route::get('/po', [PurchaseOrderController::class, 'index']);

Route::get('/reports', function () {
    return view('reports.rep');
});

Route::get('/settings', function () {
    return view('settings.sett');
});
