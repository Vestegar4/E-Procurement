<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard.dash');
});

Route::get('/procurement', function () {
    return view('procurement.proc');
});

Route::get('/purchase-orders', function () {
    return view('PO.PO');
});

Route::get('/contracts', function () {
    return view('contracts.contracts');
});

Route::get('/reports', function () {
    return view('reports.rep');
});

Route::get('/settings', function () {
    return view('settings.sett');
});