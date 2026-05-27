<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\Tender;
use App\Models\TenderResult;

class BidMonitoringController extends Controller
{
    // list all bid
    public function index()
    {
        $bids = Bid::with([
            'vendor',
            'tender'
        ])
            ->latest()
            ->paginate(15);

        return response()->json([
            'message' => 'Bid list retrieved successfully',
            'data' => $bids
        ]);
    }

    // list bid by tender
    public function tenderBids($tenderId)
    {
        $tender = Tender::findOrFail($tenderId);

        $bids = Bid::with('vendor')
            ->where('tender_id', $tender->id)
            ->orderBy('bid_amount', 'asc')
            ->get();

        return response()->json([
            'message' => 'Tender bids retrieved successfully',

            'tender' => $tender,

            'data' => $bids
        ]);
    }

    public function tenderBidsWeb($tenderId)
    {
        $tender = Tender::with('timeline')->findOrFail($tenderId);

        $bids = Bid::with('vendor')
            ->where('tender_id', $tender->id)
            ->orderBy('bid_amount', 'asc')
            ->get();

        $result = TenderResult::with('winner')
            ->where('tender_id', $tender->id)
            ->first();

        return view('admin.tender-bids', compact('tender', 'bids', 'result'));
    }
}
