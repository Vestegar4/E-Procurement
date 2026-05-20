<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['tender', 'vendor'])->get();

        return view('PO.PO', compact('purchaseOrders'));
    }
}