<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bid;
use App\Models\Vendor;
use App\Models\Tender;
use App\Models\TenderResult;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    // dashboard summary
    public function index()
    {
        return response()->json([

            'total_vendors' => Vendor::count(),

            'approved_vendors' => Vendor::where(
                'status',
                'approved'
            )->count(),

            'pending_vendors' => Vendor::where(
                'status',
                'pending'
            )->count(),

            'total_tenders' => Tender::count(),

            'active_tenders' => Tender::whereIn(
                'status',
                ['open', 'bidding']
            )->count(),

            'finished_tenders' => Tender::where(
                'status',
                'finished'
            )->count(),

            'total_bids' => Bid::count(),

            'total_results' => TenderResult::count(),
        ]);
    }
}
