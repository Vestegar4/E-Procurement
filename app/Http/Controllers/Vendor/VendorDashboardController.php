<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Bid;
use App\Models\Tender;
use App\Models\VendorDocument;
use App\Models\TenderParticipant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $availableTenders = Tender::whereIn('status', [
            'open',
            'bidding'
        ])->count();

        $myActiveBidsValue = Bid::where('vendor_id', $vendor->id)
            ->whereHas('tender', function($q) {
                $q->whereIn('status', ['open', 'bidding']);
            })
            ->sum('bid_amount');

        $joinedTenders = TenderParticipant::where(
            'vendor_id',
            $vendor->id
        )->count();

        $submittedBids = Bid::where(
            'vendor_id',
            $vendor->id
        )->count();

        $documentsCount = VendorDocument::where(
            'vendor_id',
            $vendor->id
        )->count();

        $latestTenders = Tender::with('timeline')
            ->whereIn('status', ['open', 'bidding'])
            ->latest()
            ->take(5)
            ->get();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Dashboard data retrieved successfully',
                'data' => [
                    'potentialValue' => $myActiveBidsValue, // API mengirimkan total bid Anda
                    'activeTenderCount' => $availableTenders,
                ]
            ]);
        }

        return view('vendor.dashboard', compact(
            'availableTenders',
            'myActiveBidsValue',
            'joinedTenders',
            'submittedBids',
            'documentsCount',
            'latestTenders'
        ));
    }
}
