<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;

Route::post('tenders', [TenderController::class, 'store']);
Route::get('tenders', [TenderController::class, 'index']);
Route::get('tenders/{id}', [TenderController::class, 'show']);
Route::put('tenders/{id}', [TenderController::class, 'update']);
Route::delete('tenders/{id}', [TenderController::class, 'destroy']);

Route::post('admins', [AdminController::class, 'store']);
Route::get('admins', [AdminController::class, 'index']); 
Route::get('admins/{id}', [AdminController::class, 'show']);
Route::put('admins/{id}', [AdminController::class, 'update']);
Route::delete('admins/{id}', [AdminController::class, 'destroy']);

Route::post('vendor', [VendorController::class, 'store']);
Route::get('vendor', [VendorController::class, 'index']);
Route::get('vendor/{id}', [VendorController::class, 'show']);
Route::put('vendor/{id}', [VendorController::class, 'update']);
Route::delete('vendor/{id}', [VendorController::class, 'destroy']);