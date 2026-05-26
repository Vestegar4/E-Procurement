<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Bid;
use App\Models\Tender;
use App\Models\VendorDocument;
use App\Models\TenderParticipant;
use App\Http\Controllers\Controller;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        $availableTenders = Tender::whereIn('status', [
            'open',
            'bidding'
        ])->count();

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

        return view('vendor.dashboard', compact(
            'availableTenders',
            'joinedTenders',
            'submittedBids',
            'documentsCount',
            'latestTenders'
        ));
    }
}
